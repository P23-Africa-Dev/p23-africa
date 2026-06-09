<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncWhatsAppBusinessProfileJob;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsApp\ConversationService;
use App\Services\WhatsApp\ContactManagerService;
use App\Services\WhatsApp\DeveloperPasscodeService;
use App\Services\WhatsApp\MessageStatusSyncService;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\MetaTemplateBuilderService;
use App\Services\WhatsApp\TemplateComposerService;
use App\Services\WhatsApp\TemplateService;
use App\Services\WhatsApp\TemplateRendererService;
use App\Services\WhatsApp\WhatsAppNumberValidationService;
use App\Services\WhatsApp\WhatsAppBusinessProfileService;
use App\Services\WhatsApp\WhatsAppRealtimeDebugService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
    public function overview(): View
    {
        $this->authorize('whatsapp.view');

        $stats = [
            'total_conversations' => WhatsAppConversation::count(),
            'open_conversations' => WhatsAppConversation::where('status', WhatsAppConversation::STATUS_OPEN)->count(),
            'pending_conversations' => WhatsAppConversation::where('status', WhatsAppConversation::STATUS_PENDING)->count(),
            'closed_conversations' => WhatsAppConversation::where('status', WhatsAppConversation::STATUS_CLOSED)->count(),
            'total_contacts' => WhatsAppContact::count(),
            'total_unread' => (int) WhatsAppConversation::sum('unread_count'),
            'incoming_today' => WhatsAppMessage::query()
                ->where('sender_type', WhatsAppMessage::SENDER_CONTACT)
                ->whereDate('created_at', now()->toDateString())
                ->count(),
            'outgoing_today' => WhatsAppMessage::query()
                ->where('sender_type', WhatsAppMessage::SENDER_ADMIN)
                ->whereDate('created_at', now()->toDateString())
                ->count(),
        ];

        $recentConversations = WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        return view('admin.whatsapp.overview', [
            'stats' => $stats,
            'recentConversations' => $recentConversations,
        ]);
    }

    public function conversations(
        Request $request,
        ConversationService $conversationService,
        TemplateComposerService $templateComposerService
    ): View {
        $this->authorize('whatsapp.view');

        $filters = [
            'search' => $request->query('search'),
            'status' => $request->query('status'),
            'assigned_to' => $request->query('assigned_to'),
        ];

        $conversations = $conversationService->listConversations($filters, 20);

        $conversationQuery = $request->query('conversation');
        $selectedConversationId = 0;
        if ($conversationQuery === 'none') {
            $selectedConversationId = 0;
        } elseif ($conversationQuery !== null && $conversationQuery !== '') {
            $selectedConversationId = (int) $conversationQuery;
        } else {
            $selectedConversationId = (int) optional($conversations->items()[0] ?? null)->id;
        }
        $selectedConversation = null;

        $initialMessageLimit = 30;

        if ($selectedConversationId > 0) {
            $selectedConversation = $conversationService->getConversationThread($selectedConversationId, $initialMessageLimit);
            if ($selectedConversation->unread_count > 0) {
                $conversationService->markConversationRead($selectedConversation);
                $selectedConversation = $conversationService->getConversationThread($selectedConversationId, $initialMessageLimit);
            }
        }

        $approvedTemplates = WhatsAppTemplate::query()
            ->where('status', WhatsAppTemplate::STATUS_APPROVED)
            ->orderBy('name')
            ->get();

        return view('admin.whatsapp.conversations', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'filters' => $filters,
            'statuses' => WhatsAppConversation::statuses(),
            'assignees' => $conversationService->availableAssignees(),
            'templates' => $approvedTemplates,
            'templatePickerTemplates' => $approvedTemplates
                ->map(fn(WhatsAppTemplate $template) => $templateComposerService->toClientTemplate($template))
                ->values(),
            'pollInterval' => (int) config('whatsapp.poll_interval', 5),
            'statusPollIntervalMs' => max(1500, (int) config('whatsapp.status_poll_interval_ms', 3000)),
            'historyPageSize' => $initialMessageLimit,
            'totalUnread' => (int) WhatsAppConversation::sum('unread_count'),
            'realtimeDebugEnabled' => app()->hasDebugModeEnabled() && (bool) config('whatsapp.realtime_debug', true),
            'pollCursor' => $selectedConversation?->messages->last()?->updated_at?->toIso8601String() ?: now()->subSeconds(((int) config('whatsapp.poll_interval', 5)) + 2)->toIso8601String(),
        ]);
    }

    public function conversationHistory(Request $request, WhatsAppConversation $conversation): JsonResponse
    {
        $this->authorize('whatsapp.view');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:10', 'max:100'],
            'before_message_id' => ['nullable', 'integer'],
            'cursor' => ['nullable', 'date'],
        ]);

        $limit = (int) Arr::get($validated, 'limit', 30);
        $beforeMessageId = Arr::get($validated, 'before_message_id');
        $cursor = Arr::get($validated, 'cursor');

        $query = WhatsAppMessage::query()
            ->where('conversation_id', $conversation->id)
            ->orderByDesc('id');

        if ($beforeMessageId) {
            $query->where('id', '<', (int) $beforeMessageId);
        }

        if ($cursor) {
            try {
                $query->where('created_at', '<', Carbon::parse((string) $cursor));
            } catch (\Throwable) {
                // Ignore bad cursor values; limit and id cursor still constrain the query.
            }
        }

        $messages = $query->limit($limit + 1)->get();
        $hasMore = $messages->count() > $limit;
        $messages = $messages->take($limit)->sortBy('created_at')->values();

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'has_more' => $hasMore,
            'oldest_message_id' => optional($messages->first())->id,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request, WhatsAppConversation $conversation, MessageService $messageService): JsonResponse|RedirectResponse
    {
        $this->authorize('whatsapp.send');

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:text,image,video,document,audio,template,interactive'],
            'body' => ['nullable', 'string', 'max:4096'],
            'template_id' => [
                'nullable',
                'integer',
                Rule::exists('whatsapp_templates', 'id')->where(function ($query) {
                    $query->where('status', WhatsAppTemplate::STATUS_APPROVED);
                }),
            ],
            'account_id' => ['nullable', 'integer', 'exists:whatsapp_accounts,id'],
            'template_variables' => ['nullable'],
        ]);

        if ($validated['type'] === 'template') {
            $templateId = (int) Arr::get($validated, 'template_id', 0);

            if ($templateId <= 0) {
                throw ValidationException::withMessages([
                    'template_id' => 'Select an approved template to send.',
                ]);
            }

            $message = $messageService->queueTemplateMessage(
                $conversation,
                (int) $request->user()->id,
                $templateId,
                Arr::get($validated, 'account_id'),
                Arr::get($validated, 'template_variables')
            );
        } else {
            if ($validated['type'] !== 'text') {
                return $this->response(
                    $request,
                    [
                        'success' => false,
                        'message' => 'Only text and approved template sending are enabled currently.',
                    ],
                    422,
                    'Only text and approved template sending are enabled currently.'
                );
            }

            $body = trim((string) ($validated['body'] ?? ''));
            if ($body === '') {
                throw ValidationException::withMessages(['body' => 'Message body is required for text messages.']);
            }

            $message = $messageService->queueTextMessage(
                $conversation,
                (int) $request->user()->id,
                $body,
                Arr::get($validated, 'account_id')
            );
        }

        return $this->response(
            $request,
            [
                'success' => true,
                'message' => 'Message queued successfully.',
                'data' => [
                    'id' => $message->id,
                    'status' => $message->status,
                    'conversation_id' => $message->conversation_id,
                    'sender_type' => $message->sender_type,
                    'type' => $message->type,
                    'body' => $message->body,
                    'created_at' => optional($message->created_at)->toIso8601String(),
                    'updated_at' => optional($message->updated_at)->toIso8601String(),
                ],
            ],
            200,
            'Message queued successfully.'
        );
    }

    public function updateConversation(Request $request, WhatsAppConversation $conversation, ConversationService $conversationService): JsonResponse|RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:open,pending,closed'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (array_key_exists('status', $validated) && $validated['status']) {
            $conversationService->markConversationStatus($conversation, (string) $validated['status']);
        }

        if (array_key_exists('assigned_to', $validated)) {
            $conversationService->assignConversation($conversation, $validated['assigned_to']);
        }

        return $this->response(
            $request,
            [
                'success' => true,
                'message' => 'Conversation updated successfully.',
                'data' => [
                    'conversation_id' => $conversation->id,
                    'status' => $conversation->status,
                    'assigned_to' => $conversation->assigned_to,
                    'unread_count' => $conversation->unread_count,
                    'updated_at' => optional($conversation->updated_at)->toIso8601String(),
                ],
            ],
            200,
            'Conversation updated successfully.'
        );
    }

    public function addNote(Request $request, WhatsAppConversation $conversation, ConversationService $conversationService): JsonResponse|RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $note = $conversationService->addInternalNote($conversation, (int) $request->user()->id, $validated['note']);

        return $this->response(
            $request,
            [
                'success' => true,
                'message' => 'Note added successfully.',
                'data' => [
                    'id' => $note->id,
                    'note' => $note->note,
                    'admin_name' => $note->admin?->name ?: $request->user()->name,
                    'created_at' => now()->toIso8601String(),
                ],
            ],
            201,
            'Note added successfully.'
        );
    }

    public function markRead(WhatsAppConversation $conversation, ConversationService $conversationService): JsonResponse
    {
        $this->authorize('whatsapp.manage');

        $conversationService->markConversationRead($conversation);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function clearConversation(Request $request, WhatsAppConversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        WhatsAppMessage::query()
            ->where('conversation_id', $conversation->id)
            ->delete();

        $conversation->notes()->delete();

        $conversation->update([
            'last_message' => null,
            'unread_count' => 0,
        ]);

        return $this->response(
            $request,
            [
                'success' => true,
                'message' => 'Conversation cleared successfully.',
                'data' => [
                    'conversation_id' => $conversation->id,
                ],
            ],
            200,
            'Conversation cleared successfully.'
        );
    }

    public function poll(Request $request, ?WhatsAppRealtimeDebugService $debugService = null): JsonResponse
    {
        $this->authorize('whatsapp.view');

        $debugService ??= app(WhatsAppRealtimeDebugService::class);

        $conversationId = (int) $request->query('conversation_id', 0);
        $scope = strtolower((string) $request->query('scope', 'messages'));
        $since = $request->query('since');

        try {
            $sinceAt = $since ? Carbon::parse($since) : now()->subSeconds(((int) config('whatsapp.poll_interval', 5)) + 2);
        } catch (\Throwable) {
            $sinceAt = now()->subMinutes(1);
        }

        if ($conversationId > 0) {
            $conversation = WhatsAppConversation::query()
                ->with(['contact', 'assignedUser'])
                ->find($conversationId);

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found.',
                ], 404);
            }

            $messages = WhatsAppMessage::query()
                ->where('conversation_id', $conversationId)
                ->where('created_at', '>', $sinceAt)
                ->orderBy('created_at')
                ->get();

            $debugService->logPollExecution('messages', $conversationId, [
                'count' => $messages->count(),
                'since' => optional($sinceAt)->toIso8601String(),
            ]);
            $debugService->logMessageRefresh($conversationId, $messages->count(), ['scope' => 'messages']);

            return response()->json([
                'success' => true,
                'server_time' => now()->toIso8601String(),
                'unread_total' => (int) WhatsAppConversation::sum('unread_count'),
                'conversation' => [
                    'id' => $conversation->id,
                    'status' => $conversation->status,
                    'unread_count' => $conversation->unread_count,
                    'last_message' => $conversation->last_message,
                    'updated_at' => optional($conversation->updated_at)->toIso8601String(),
                    'contact' => [
                        'name' => $conversation->contact?->name,
                        'phone' => $conversation->contact?->phone,
                    ],
                    'assigned_user' => [
                        'id' => $conversation->assignedUser?->id,
                        'name' => $conversation->assignedUser?->name,
                    ],
                ],
                'messages' => $messages,
            ]);
        }

        $updatedConversations = WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->where('updated_at', '>=', $sinceAt)
            ->orderByDesc('updated_at')
            ->limit(100)
            ->get();

        if ($scope === 'conversations') {
            $debugService->logPollExecution('conversations', null, [
                'count' => $updatedConversations->count(),
                'since' => optional($sinceAt)->toIso8601String(),
            ]);

            return response()->json([
                'success' => true,
                'server_time' => now()->toIso8601String(),
                'unread_total' => (int) WhatsAppConversation::sum('unread_count'),
                'conversations' => $updatedConversations,
            ]);
        }

        $conversationIds = $updatedConversations->pluck('id')->values()->all();

        $messages = [];
        if (!empty($conversationIds)) {
            $messages = WhatsAppMessage::query()
                ->whereIn('conversation_id', $conversationIds)
                ->where('created_at', '>=', $sinceAt)
                ->orderBy('created_at')
                ->get();
        }

        $debugService->logPollExecution('dashboard', null, [
            'conversation_count' => $updatedConversations->count(),
            'message_count' => count($messages),
            'since' => optional($sinceAt)->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'server_time' => now()->toIso8601String(),
            'unread_total' => (int) WhatsAppConversation::sum('unread_count'),
            'conversations' => $updatedConversations,
            'messages' => $messages,
        ]);
    }

    public function statusPoll(
        Request $request,
        MessageStatusSyncService $statusSyncService,
        ?WhatsAppRealtimeDebugService $debugService = null
    ): JsonResponse {
        $this->authorize('whatsapp.view');

        $debugService ??= app(WhatsAppRealtimeDebugService::class);

        $conversationId = (int) $request->query('conversation_id', 0);
        $since = $request->query('since');

        if ($conversationId <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'conversation_id is required.',
            ], 422);
        }

        $conversationExists = WhatsAppConversation::query()->whereKey($conversationId)->exists();
        if (!$conversationExists) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found.',
            ], 404);
        }

        $statusUpdates = $statusSyncService->getPendingStatusUpdates($conversationId, $since);
        $statusSyncService->markStatusUpdatesDelivered($statusUpdates->pluck('id'));

        $debugService->logPollExecution('status', $conversationId, [
            'count' => $statusUpdates->count(),
            'since' => $since,
        ]);

        return response()->json([
            'success' => true,
            'server_time' => now()->toIso8601String(),
            'conversation_id' => $conversationId,
            'statuses' => $statusUpdates->map(static function (WhatsAppMessage $message) {
                $statusError = data_get($message->payload, 'status_error.errors.0');

                return [
                    'message_id' => $message->id,
                    'status' => $message->status,
                    'updated_at' => optional($message->status_updated_at ?: $message->updated_at)->toIso8601String(),
                    'failure_reason' => $message->status === WhatsAppMessage::STATUS_FAILED ? [
                        'code' => data_get($statusError, 'code'),
                        'title' => data_get($statusError, 'title'),
                        'message' => data_get($statusError, 'message'),
                        'details' => data_get($statusError, 'error_data.details'),
                    ] : null,
                ];
            })->values(),
        ]);
    }

    public function realtimeDebug(Request $request, WhatsAppRealtimeDebugService $debugService): JsonResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'event' => ['required', 'string', 'max:120'],
            'context' => ['nullable', 'array'],
        ]);

        $debugService->log((string) $validated['event'], [
            'source' => 'frontend',
            'context' => Arr::get($validated, 'context', []),
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'enabled' => $debugService->enabled(),
        ]);
    }

    public function templates(TemplateService $templateService): View
    {
        $this->authorize('whatsapp.view');

        return view('admin.whatsapp.templates', [
            'templates' => $templateService->paginate(20),
            'formats' => [
                TemplateRendererService::FORMAT_JSON,
                TemplateRendererService::FORMAT_RICH_TEXT,
                TemplateRendererService::FORMAT_HTML,
            ],
            'builderCategories' => [
                'marketing' => 'Promotional broadcasts, offers, campaigns, and announcements.',
                'utility' => 'Transactional updates, reminders, confirmations, and support.',
                'authentication' => 'One-time passcodes and login/security verification messages.',
            ],
            'builderLanguages' => [
                'en',
                'en_US',
                'en_GB',
                'fr',
                'pt_BR',
                'ar',
            ],
            'builderButtonTypes' => [
                'quick_reply' => 'Quick Reply',
                'visit_website' => 'Visit Website',
                'call_phone' => 'Call Phone',
                'copy_coupon' => 'Copy Coupon',
                'flow' => 'Flow',
            ],
            'defaultVariables' => [
                'name',
                'location',
                'company',
                'date',
                'event',
                'event_name',
                'event_date',
                'seat_code',
                'email',
                'phone',
                'booking_date',
            ],
        ]);
    }

    public function templateStatusPoll(Request $request): JsonResponse
    {
        $this->authorize('whatsapp.view');

        $validated = $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer', 'exists:whatsapp_templates,id'],
        ]);

        $ids = array_values(array_filter(array_map('intval', (array) Arr::get($validated, 'ids', []))));

        $query = WhatsAppTemplate::query()
            ->select(['id', 'name', 'status', 'language', 'updated_at'])
            ->orderByDesc('updated_at');

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $templates = $query->limit(200)->get()->map(static function (WhatsAppTemplate $template): array {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'status' => $template->status,
                'language' => $template->language,
                'updated_at' => optional($template->updated_at)->toIso8601String(),
            ];
        })->values()->all();

        return response()->json([
            'success' => true,
            'data' => [
                'templates' => $templates,
                'server_time' => now()->toIso8601String(),
            ],
        ]);
    }

    public function storeTemplate(
        Request $request,
        TemplateService $templateService,
        TemplateRendererService $rendererService,
        MetaTemplateBuilderService $builderService
    ): RedirectResponse {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:512'],
            'category' => ['required', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:20'],
            'template_format' => ['nullable', 'string', 'in:json,rich_text,html'],
            'builder_mode' => ['nullable', 'string', 'in:simple,advanced'],
            'variable_type' => ['nullable', 'string', 'in:number,named'],
            'template_json' => ['nullable'],
            'rich_text_content' => ['nullable', 'string'],
            'html_content' => ['nullable', 'string'],
            'editor_metadata' => ['nullable'],
            'buttons_config' => ['nullable'],
            'simple_header_type' => ['nullable', 'string', 'in:none,text,image,video,document'],
            'simple_header_text' => ['nullable', 'string'],
            'simple_header_media_format' => ['nullable', 'string', 'in:IMAGE,VIDEO,DOCUMENT'],
            'simple_body_text' => ['nullable', 'string'],
            'simple_footer_text' => ['nullable', 'string'],
            'media_sample_type' => ['nullable', 'string', 'in:image,video,document'],
            'media_sample_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,pdf,doc,docx', 'max:20480'],
            'remove_media_sample' => ['nullable', 'boolean'],
        ]);

        $normalizedName = $builderService->normalizeTemplateName((string) $validated['name']);
        if ($normalizedName === '') {
            throw ValidationException::withMessages([
                'name' => 'Template name is invalid. Use letters, numbers, and underscore only.',
            ]);
        }

        $normalizedLanguage = strtolower(trim((string) $validated['language']));
        $normalizedCategory = $builderService->normalizeCategory((string) $validated['category']);

        $existingTemplate = WhatsAppTemplate::query()
            ->where('name', $normalizedName)
            ->where('language', $normalizedLanguage)
            ->first();

        if ($existingTemplate) {
            throw ValidationException::withMessages([
                'name' => 'A template with this normalized name and language already exists. Load and update it instead.',
            ]);
        }

        $editorMetadata = $this->decodeEditorMetadata(Arr::get($validated, 'editor_metadata'));
        $editorMetadata = $this->applyMediaSampleMetadata($request, $editorMetadata);
        $validated['editor_metadata'] = $editorMetadata;

        $templatePayload = $this->prepareTemplatePayload($validated, $rendererService, $builderService);

        $templateService->createOrUpdate([
            'name' => $normalizedName,
            'category' => $normalizedCategory,
            'language' => $normalizedLanguage,
            'status' => WhatsAppTemplate::STATUS_DRAFT,
            'template_format' => $templatePayload['template_format'],
            'template_json' => $templatePayload['template_json'],
            'rendered_content' => $templatePayload['rendered_content'],
            'preview_content' => $templatePayload['preview_content'],
            'editor_metadata' => $templatePayload['editor_metadata'],
        ]);

        return back()->with('success', 'Template saved successfully.');
    }

    public function updateTemplate(
        Request $request,
        WhatsAppTemplate $template,
        TemplateService $templateService,
        TemplateRendererService $rendererService,
        MetaTemplateBuilderService $builderService
    ): RedirectResponse {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:512'],
            'category' => ['required', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:20'],
            'template_format' => ['nullable', 'string', 'in:json,rich_text,html'],
            'builder_mode' => ['nullable', 'string', 'in:simple,advanced'],
            'variable_type' => ['nullable', 'string', 'in:number,named'],
            'template_json' => ['nullable'],
            'rich_text_content' => ['nullable', 'string'],
            'html_content' => ['nullable', 'string'],
            'editor_metadata' => ['nullable'],
            'buttons_config' => ['nullable'],
            'simple_header_type' => ['nullable', 'string', 'in:none,text,image,video,document'],
            'simple_header_text' => ['nullable', 'string'],
            'simple_header_media_format' => ['nullable', 'string', 'in:IMAGE,VIDEO,DOCUMENT'],
            'simple_body_text' => ['nullable', 'string'],
            'simple_footer_text' => ['nullable', 'string'],
            'media_sample_type' => ['nullable', 'string', 'in:image,video,document'],
            'media_sample_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,pdf,doc,docx', 'max:20480'],
            'remove_media_sample' => ['nullable', 'boolean'],
        ]);

        $normalizedName = $builderService->normalizeTemplateName((string) $validated['name']);
        if ($normalizedName === '') {
            throw ValidationException::withMessages([
                'name' => 'Template name is invalid. Use letters, numbers, and underscore only.',
            ]);
        }

        $normalizedLanguage = strtolower(trim((string) $validated['language']));
        $normalizedCategory = $builderService->normalizeCategory((string) $validated['category']);

        $duplicate = WhatsAppTemplate::query()
            ->where('name', $normalizedName)
            ->where('language', $normalizedLanguage)
            ->where('id', '!=', $template->id)
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'name' => 'Another template already uses this normalized name and language.',
            ]);
        }

        $editorMetadata = $this->decodeEditorMetadata(Arr::get($validated, 'editor_metadata'));
        $editorMetadata = $this->applyMediaSampleMetadata($request, $editorMetadata, $template);
        $validated['editor_metadata'] = $editorMetadata;

        $templatePayload = $this->prepareTemplatePayload($validated, $rendererService, $builderService);

        $templateService->updateTemplate($template, [
            'name' => $normalizedName,
            'category' => $normalizedCategory,
            'language' => $normalizedLanguage,
            'template_format' => $templatePayload['template_format'],
            'template_json' => $templatePayload['template_json'],
            'rendered_content' => $templatePayload['rendered_content'],
            'preview_content' => $templatePayload['preview_content'],
            'editor_metadata' => $templatePayload['editor_metadata'],
        ]);

        return back()->with('success', 'Template updated successfully.');
    }

    public function contacts(Request $request): View
    {
        $this->authorize('whatsapp.view');

        $search = $request->query('search');

        $contacts = app(ContactManagerService::class)->listContacts($search, 20);

        return view('admin.whatsapp.contacts', [
            'contacts' => $contacts,
            'search' => $search,
        ]);
    }

    public function storeContact(
        Request $request,
        ContactManagerService $contactManagerService,
        WhatsAppNumberValidationService $numberValidationService
    ): RedirectResponse {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'avatar' => ['nullable', 'url', 'max:2048'],
            'avatar_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $normalizedPhone = $numberValidationService->normalizeNumber(
            (string) $validated['phone'],
            (string) ($validated['country_code'] ?? '')
        );

        $digitsOnly = preg_replace('/\D+/', '', $normalizedPhone) ?? '';
        $duplicate = WhatsAppContact::query()
            ->where('phone', $normalizedPhone)
            ->when($digitsOnly !== '', fn($query) => $query->orWhere('phone', $digitsOnly))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'phone' => 'A contact with this phone already exists.',
            ]);
        }

        $validated['phone'] = $normalizedPhone;

        $contactManagerService->createContact($validated);

        return back()->with('success', 'Contact created successfully.');
    }

    public function updateContact(Request $request, WhatsAppContact $contact, ContactManagerService $contactManagerService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'avatar' => ['nullable', 'url', 'max:2048'],
            'avatar_file' => ['nullable', 'image', 'max:5120'],
            'profile_picture' => ['nullable', 'url', 'max:2048'],
            'unsaved_contact' => ['nullable', 'boolean'],
        ]);

        $contactManagerService->updateContact($contact, $validated);

        return back()->with('success', 'Contact updated successfully.');
    }

    public function destroyContact(WhatsAppContact $contact, ContactManagerService $contactManagerService): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $contactManagerService->deleteContact($contact);

        return back()->with('success', 'Contact deleted successfully.');
    }

    public function settings(Request $request, DeveloperPasscodeService $passcodeService): View
    {
        $this->authorize('whatsapp.view');

        $activeAccount = WhatsAppAccount::query()->latest('id')->first();

        return view('admin.whatsapp.settings', [
            'activeAccount' => $activeAccount,
            'accounts' => WhatsAppAccount::query()->orderByDesc('id')->get(),
            'sensitiveMasked' => $passcodeService->maskedSensitiveFields($activeAccount),
            'unlocked' => $passcodeService->isUnlocked($request),
            'canManageDeveloperSecurity' => $passcodeService->isDeveloperManager($request->user()),
            'config' => [
                'enabled' => (bool) config('whatsapp.enabled'),
                'graph_version' => config('whatsapp.graph_version'),
                'base_url' => config('whatsapp.base_url'),
                'queue' => (bool) config('whatsapp.queue'),
                'poll_interval' => (int) config('whatsapp.poll_interval', 5),
            ],
        ]);
    }

    public function saveAccount(Request $request): RedirectResponse
    {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:whatsapp_accounts,id'],
            'business_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:40'],
            'profile_image' => ['nullable', 'image', 'max:5120'],
        ]);

        $account = WhatsAppAccount::query()->findOrFail((int) Arr::get($validated, 'id'));

        $syncRequired = $account->business_name !== $validated['business_name'];
        $profileImageUrl = $account->profile_image_url;

        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('whatsapp/profile-images', 'public');
            $profileImageUrl = Storage::url($profileImagePath);
            $syncRequired = true;
        }

        $account->update([
            'business_name' => $validated['business_name'],
            'profile_image_url' => $profileImageUrl,
            'display_name_status' => $syncRequired
                ? WhatsAppBusinessProfileService::STATUS_PENDING_APPROVAL
                : $account->display_name_status,
            'profile_sync_status' => $syncRequired
                ? WhatsAppBusinessProfileService::STATUS_PENDING_APPROVAL
                : $account->profile_sync_status,
            'phone_number' => $validated['phone_number'],
        ]);

        if ($syncRequired) {
            SyncWhatsAppBusinessProfileJob::dispatch($account->id)->onQueue('whatsapp');
        }

        return back()->with('success', 'WhatsApp business profile updated successfully.');
    }

    public function accountSetup(): View
    {
        $this->authorize('whatsapp.view');

        return view('admin.whatsapp.account-setup', [
            'webhookUrl' => url('/api/whatsapp/webhook'),
            'graphVersion' => config('whatsapp.graph_version', 'v23.0'),
            'baseUrl' => config('whatsapp.base_url', 'https://graph.facebook.com'),
        ]);
    }

    public function webhookLogs(Request $request): View
    {
        $this->authorize('whatsapp.view');

        $status = $request->query('status');

        $logs = WhatsAppWebhookLog::query()
            ->when($status, fn($query) => $query->where('status', $status))
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.whatsapp.webhook-logs', [
            'logs' => $logs,
            'status' => $status,
        ]);
    }

    protected function decodeJsonInput(mixed $input): array
    {
        if (is_array($input)) {
            return $input;
        }

        $decoded = json_decode((string) $input, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw ValidationException::withMessages([
                'template_json' => 'Template JSON must be valid JSON object or array.',
            ]);
        }

        return $decoded;
    }

    protected function prepareTemplatePayload(
        array $validated,
        TemplateRendererService $rendererService,
        MetaTemplateBuilderService $builderService
    ): array {
        $format = $rendererService->normalizeFormat((string) Arr::get($validated, 'template_format', 'json'));
        $editorMetadata = $this->decodeEditorMetadata(Arr::get($validated, 'editor_metadata', []));
        $builderMode = strtolower(trim((string) Arr::get($validated, 'builder_mode', 'simple')));
        $variableType = $builderService->normalizeVariableType((string) Arr::get($validated, 'variable_type', MetaTemplateBuilderService::VARIABLE_TYPE_NUMBER));

        if ($format === TemplateRendererService::FORMAT_JSON) {
            if ($builderMode === 'simple') {
                $jsonPayload = $builderService->buildSimpleTemplate([
                    'variable_type' => $variableType,
                    'header_type' => Arr::get($validated, 'simple_header_type', 'none'),
                    'header_text' => Arr::get($validated, 'simple_header_text'),
                    'header_media_format' => Arr::get($validated, 'simple_header_media_format'),
                    'body_text' => Arr::get($validated, 'simple_body_text'),
                    'footer_text' => Arr::get($validated, 'simple_footer_text'),
                    'buttons' => $this->decodeButtonsConfig(Arr::get($validated, 'buttons_config')),
                ]);
            } else {
                $jsonPayload = $builderService->validateAndNormalizeTemplateJson(
                    $this->decodeJsonInput(Arr::get($validated, 'template_json')),
                    $variableType
                );
            }

            $rendered = $rendererService->renderJson($jsonPayload);

            return [
                'template_format' => TemplateRendererService::FORMAT_JSON,
                'template_json' => $jsonPayload,
                'rendered_content' => json_encode($rendered['rendered_content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'preview_content' => (string) $rendered['preview_content'],
                'editor_metadata' => array_merge($editorMetadata, [
                    'builder_mode' => $builderMode,
                    'variable_type' => $variableType,
                ]),
            ];
        }

        $content = $format === TemplateRendererService::FORMAT_HTML
            ? (string) Arr::get($validated, 'html_content', '')
            : (string) Arr::get($validated, 'rich_text_content', '');

        if (trim($content) === '') {
            throw ValidationException::withMessages([
                $format === TemplateRendererService::FORMAT_HTML ? 'html_content' : 'rich_text_content' => 'Template content is required.',
            ]);
        }

        $rendered = $rendererService->render($format, $content);

        return [
            'template_format' => $format,
            'template_json' => $rendered['whatsapp_payload'],
            'rendered_content' => (string) $rendered['stored_content'],
            'preview_content' => (string) $rendered['preview_content'],
            'editor_metadata' => array_merge($editorMetadata, [
                'source_content' => (string) $content,
            ]),
        ];
    }

    protected function decodeEditorMetadata(mixed $metadata): array
    {
        if (is_string($metadata) && trim($metadata) !== '') {
            $decodedMetadata = json_decode($metadata, true);
            $metadata = is_array($decodedMetadata) ? $decodedMetadata : [];
        }

        if (!is_array($metadata)) {
            $metadata = [];
        }

        return $metadata;
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function decodeButtonsConfig(mixed $buttons): array
    {
        if (is_string($buttons) && trim($buttons) !== '') {
            $decoded = json_decode($buttons, true);
            $buttons = is_array($decoded) ? $decoded : [];
        }

        return is_array($buttons) ? $buttons : [];
    }

    /**
     * @param array<string, mixed> $editorMetadata
     * @return array<string, mixed>
     */
    protected function applyMediaSampleMetadata(
        Request $request,
        array $editorMetadata,
        ?WhatsAppTemplate $template = null
    ): array {
        $existingMedia = Arr::get($template?->editor_metadata ?? [], 'media_sample');

        if ($request->boolean('remove_media_sample')) {
            $this->deleteMediaSampleFile($existingMedia);
            unset($editorMetadata['media_sample']);

            return $editorMetadata;
        }

        if (!$request->hasFile('media_sample_file')) {
            if ($existingMedia && !isset($editorMetadata['media_sample'])) {
                $editorMetadata['media_sample'] = $existingMedia;
            }

            return $editorMetadata;
        }

        $uploaded = $request->file('media_sample_file');
        if (!$uploaded) {
            return $editorMetadata;
        }

        $this->deleteMediaSampleFile($existingMedia);

        $path = $uploaded->store('whatsapp/template-samples', 'public');
        $mediaType = strtolower(trim((string) $request->input('media_sample_type', '')));
        if ($mediaType === '') {
            $mime = strtolower((string) $uploaded->getMimeType());
            $mediaType = str_starts_with($mime, 'video/')
                ? 'video'
                : (str_starts_with($mime, 'image/') ? 'image' : 'document');
        }

        $editorMetadata['media_sample'] = [
            'type' => $mediaType,
            'disk' => 'public',
            'path' => $path,
            'url' => Storage::url($path),
            'name' => $uploaded->getClientOriginalName(),
            'mime' => $uploaded->getMimeType(),
            'size' => $uploaded->getSize(),
            'uploaded_at' => now()->toIso8601String(),
        ];

        return $editorMetadata;
    }

    protected function deleteMediaSampleFile(mixed $mediaSample): void
    {
        if (!is_array($mediaSample)) {
            return;
        }

        $disk = (string) Arr::get($mediaSample, 'disk', 'public');
        $path = (string) Arr::get($mediaSample, 'path', '');

        if ($path !== '' && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    protected function response(Request $request, array $payload, int $statusCode, string $flashMessage): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($payload, $statusCode);
        }

        if (($payload['success'] ?? false) === true) {
            return back()->with('success', $flashMessage);
        }

        return back()->withErrors(['whatsapp' => $flashMessage]);
    }
}
