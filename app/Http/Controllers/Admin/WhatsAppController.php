<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsApp\ConversationService;
use App\Services\WhatsApp\MessageStatusSyncService;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\TemplateService;
use App\Services\WhatsApp\WhatsAppRealtimeDebugService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
    public function overview(): View
    {
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

    public function conversations(Request $request, ConversationService $conversationService): View
    {
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

        if ($selectedConversationId > 0) {
            $selectedConversation = $conversationService->getConversationThread($selectedConversationId, 250);
            if ($selectedConversation->unread_count > 0) {
                $conversationService->markConversationRead($selectedConversation);
                $selectedConversation->refresh();
                $selectedConversation->load(['contact', 'assignedUser', 'messages', 'notes.admin']);
            }
        }

        return view('admin.whatsapp.conversations', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'filters' => $filters,
            'statuses' => WhatsAppConversation::statuses(),
            'assignees' => $conversationService->availableAssignees(),
            'templates' => WhatsAppTemplate::query()->orderBy('name')->get(),
            'pollInterval' => (int) config('whatsapp.poll_interval', 5),
            'statusPollIntervalMs' => max(1500, (int) config('whatsapp.status_poll_interval_ms', 3000)),
            'totalUnread' => (int) WhatsAppConversation::sum('unread_count'),
            'realtimeDebugEnabled' => app()->hasDebugModeEnabled() && (bool) config('whatsapp.realtime_debug', true),
            'pollCursor' => $selectedConversation?->messages->last()?->updated_at?->toIso8601String() ?: now()->subSeconds(((int) config('whatsapp.poll_interval', 5)) + 2)->toIso8601String(),
        ]);
    }

    public function sendMessage(Request $request, WhatsAppConversation $conversation, MessageService $messageService): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:text,image,video,document,audio,template,interactive'],
            'body' => ['nullable', 'string', 'max:4096'],
            'account_id' => ['nullable', 'integer', 'exists:whatsapp_accounts,id'],
        ]);

        if ($validated['type'] !== 'text') {
            return $this->response(
                $request,
                [
                    'success' => false,
                    'message' => 'Only text sending is enabled currently. Placeholder message types are prepared in architecture.',
                ],
                422,
                'Only text sending is enabled currently. Placeholder message types are prepared in architecture.'
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
        $conversationService->markConversationRead($conversation);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function poll(Request $request, ?WhatsAppRealtimeDebugService $debugService = null): JsonResponse
    {
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
                return [
                    'message_id' => $message->id,
                    'status' => $message->status,
                    'updated_at' => optional($message->status_updated_at ?: $message->updated_at)->toIso8601String(),
                ];
            })->values(),
        ]);
    }

    public function realtimeDebug(Request $request, WhatsAppRealtimeDebugService $debugService): JsonResponse
    {
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
        return view('admin.whatsapp.templates', [
            'templates' => $templateService->paginate(20),
            'statuses' => [
                WhatsAppTemplate::STATUS_DRAFT,
                WhatsAppTemplate::STATUS_PENDING,
                WhatsAppTemplate::STATUS_APPROVED,
                WhatsAppTemplate::STATUS_REJECTED,
            ],
        ]);
    }

    public function storeTemplate(Request $request, TemplateService $templateService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:draft,pending,approved,rejected'],
            'template_json' => ['required'],
        ]);

        $templateService->createOrUpdate([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'template_json' => $this->decodeJsonInput($validated['template_json']),
        ]);

        return back()->with('success', 'Template saved successfully.');
    }

    public function updateTemplate(Request $request, WhatsAppTemplate $template, TemplateService $templateService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:draft,pending,approved,rejected'],
            'template_json' => ['required'],
        ]);

        $templateService->updateTemplate($template, [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'template_json' => $this->decodeJsonInput($validated['template_json']),
        ]);

        return back()->with('success', 'Template updated successfully.');
    }

    public function contacts(Request $request): View
    {
        $search = $request->query('search');

        $contacts = WhatsAppContact::query()
            ->withCount('conversations')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.whatsapp.contacts', [
            'contacts' => $contacts,
            'search' => $search,
        ]);
    }

    public function updateContact(Request $request, WhatsAppContact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'url', 'max:2048'],
        ]);

        $contact->update($validated);

        return back()->with('success', 'Contact updated successfully.');
    }

    public function settings(): View
    {
        return view('admin.whatsapp.settings', [
            'activeAccount' => WhatsAppAccount::query()->active()->latest('id')->first(),
            'accounts' => WhatsAppAccount::query()->orderByDesc('id')->get(),
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
        $validated = $request->validate([
            'id' => ['nullable', 'integer', 'exists:whatsapp_accounts,id'],
            'business_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:40'],
            'phone_number_id' => ['required', 'string', 'max:80'],
            'waba_id' => ['nullable', 'string', 'max:80'],
            'access_token' => ['required', 'string'],
            'webhook_verify_token' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        WhatsAppAccount::updateOrCreate(
            ['id' => Arr::get($validated, 'id')],
            Arr::except($validated, ['id'])
        );

        return back()->with('success', 'WhatsApp account saved successfully.');
    }

    public function accountSetup(): View
    {
        return view('admin.whatsapp.account-setup', [
            'webhookUrl' => url('/api/whatsapp/webhook'),
            'graphVersion' => config('whatsapp.graph_version', 'v23.0'),
            'baseUrl' => config('whatsapp.base_url', 'https://graph.facebook.com'),
        ]);
    }

    public function webhookLogs(Request $request): View
    {
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
