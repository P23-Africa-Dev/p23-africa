<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventWhatsAppCampaign;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\EventAudienceService;
use App\Services\WhatsApp\EventWhatsAppService;
use App\Services\WhatsApp\MetaTemplateBuilderService;
use App\Services\WhatsApp\WhatsAppAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EventCampaignController extends Controller
{
    public function eventContacts(Request $request, EventAudienceService $audienceService): View
    {
        $this->authorize('whatsapp.view');

        $filters = [
            'event_filter' => $audienceService->normalizeFilterType((string) $request->query('event_filter', EventAudienceService::FILTER_UPCOMING)),
            'event_id' => (int) $request->query('event_id', 0),
            'event_ids' => array_values(array_filter(array_map('intval', (array) $request->query('event_ids', [])))),
        ];

        $events = $audienceService->listEventsWithAudienceStats($filters, 10);

        $search = trim((string) $request->query('search', ''));
        $audienceCollection = $audienceService->extractValidatedAudience($filters);

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $audienceCollection = $audienceCollection->filter(function (array $row) use ($needle) {
                $haystack = mb_strtolower((string) (
                    ($row['name'] ?? '') . ' ' .
                    ($row['phone'] ?? '') . ' ' .
                    ($row['email'] ?? '') . ' ' .
                    ($row['event_name'] ?? '') . ' ' .
                    ($row['seat_code'] ?? '')
                ));

                return str_contains($haystack, $needle);
            })->values();
        }

        $audience = $this->paginateCollection(
            $audienceCollection,
            20,
            (int) $request->query('audience_page', 1),
            'audience_page',
            $request->url(),
            $request->query()
        );

        return view('admin.whatsapp.EventAudienceSelector', [
            'filters' => $filters,
            'events' => $events,
            'audience' => $audience,
            'search' => $search,
            'eventFilterOptions' => [
                EventAudienceService::FILTER_SINGLE,
                EventAudienceService::FILTER_MULTIPLE,
                EventAudienceService::FILTER_PAST,
                EventAudienceService::FILTER_UPCOMING,
                EventAudienceService::FILTER_ALL,
            ],
        ]);
    }

    public function eventBroadcast(Request $request, EventAudienceService $audienceService): View
    {
        $this->authorize('whatsapp.view');

        $sourceType = strtolower((string) $request->query('source_type', 'event_contacts'));

        return view('admin.whatsapp.EventBroadcast', [
            'sourceType' => in_array($sourceType, ['event_contacts', 'saved_contacts', 'manual_contacts'], true) ? $sourceType : 'event_contacts',
            'templates' => WhatsAppTemplate::query()->where('status', WhatsAppTemplate::STATUS_APPROVED)->orderBy('name')->get(),
            'allTemplates' => WhatsAppTemplate::query()->orderBy('name')->get(),
            'events' => Event::query()->orderByDesc('event_date')->orderByDesc('event_time')->get(['id', 'title', 'event_date', 'event_time']),
            'savedContacts' => WhatsAppContact::query()
                ->whereNotNull('phone')
                ->where('phone', '!=', '')
                ->orderByDesc('last_chat_at')
                ->get(['id', 'name', 'phone', 'validation_status', 'is_whatsapp']),
            'eventFilterOptions' => [
                EventAudienceService::FILTER_SINGLE,
                EventAudienceService::FILTER_MULTIPLE,
                EventAudienceService::FILTER_PAST,
                EventAudienceService::FILTER_UPCOMING,
                EventAudienceService::FILTER_ALL,
            ],
            'defaultFilter' => EventAudienceService::FILTER_UPCOMING,
        ]);
    }

    public function audienceCount(Request $request, EventWhatsAppService $eventWhatsAppService): JsonResponse
    {
        $this->authorize('whatsapp.send');

        $validated = $request->validate([
            'source_type'        => ['required', 'string', 'in:event_contacts,saved_contacts,manual_contacts'],
            'event_filter'       => ['nullable', 'string', 'in:single,multiple,past,upcoming,all'],
            'event_id'           => ['nullable', 'integer'],
            'event_ids'          => ['nullable', 'array'],
            'event_ids.*'        => ['integer'],
            'saved_contact_ids'  => ['nullable', 'array'],
            'saved_contact_ids.*' => ['integer'],
            'manual_contacts'    => ['nullable', 'string', 'max:20000'],
        ]);

        try {
            $preview = $eventWhatsAppService->previewAudienceCount($validated);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'data' => $preview]);
    }

    public function submitTemplateToMeta(
        Request $request,
        \App\Services\WhatsApp\MetaTemplateSubmissionService $submissionService,
        MetaTemplateBuilderService $builderService
    ): JsonResponse {
        $this->authorize('whatsapp.manage');

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:512'],
            'category' => ['required', 'string', 'max:80'],
            'language' => ['required', 'string', 'max:20'],
            'mode' => ['nullable', 'string', 'in:create,update'],
            'variable_type' => ['nullable', 'string', 'in:number,named'],
            'components' => ['required'],
        ]);

        $componentsInput = $request->input('components');
        if (is_string($componentsInput)) {
            $decoded = json_decode($componentsInput, true);
            $componentsInput = is_array($decoded) ? $decoded : null;
        }

        if (!is_array($componentsInput) || empty($componentsInput)) {
            return response()->json([
                'success' => false,
                'message' => 'components must be a non-empty array.',
            ], 422);
        }

        $mode = strtolower((string) ($validated['mode'] ?? 'create'));
        $variableType = $builderService->normalizeVariableType((string) Arr::get($validated, 'variable_type', MetaTemplateBuilderService::VARIABLE_TYPE_NUMBER));
        $normalizedName = $builderService->normalizeTemplateName((string) $validated['name']);
        if ($normalizedName === '') {
            return response()->json([
                'success' => false,
                'message' => 'Template name is invalid. Use letters, numbers, and underscore only.',
            ], 422);
        }

        $normalizedComponents = $builderService->validateAndNormalizeTemplateJson([
            'components' => $componentsInput,
        ], $variableType);

        $normalizedCategory = strtoupper($builderService->normalizeCategory((string) $validated['category']));
        $normalizedLanguage = strtolower(trim((string) $validated['language']));

        try {
            if ($mode === 'update') {
                $result = $submissionService->submitTemplateUpdate(
                    $normalizedName,
                    $normalizedCategory,
                    $normalizedLanguage,
                    (array) Arr::get($normalizedComponents, 'components', []),
                );
            } else {
                $result = $submissionService->submitTemplate(
                    $normalizedName,
                    $normalizedCategory,
                    $normalizedLanguage,
                    (array) Arr::get($normalizedComponents, 'components', []),
                );
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function audiencePreview(Request $request, EventWhatsAppService $eventWhatsAppService): JsonResponse
    {
        $this->authorize('whatsapp.send');

        $validated = $request->validate([
            'source_type' => ['required', 'string', 'in:event_contacts,saved_contacts,manual_contacts'],
            'event_filter' => ['nullable', 'string', 'in:single,multiple,past,upcoming,all'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'event_ids' => ['nullable', 'array'],
            'event_ids.*' => ['integer', 'exists:events,id'],
            'saved_contact_ids' => ['nullable', 'array'],
            'saved_contact_ids.*' => ['integer', 'exists:whatsapp_contacts,id'],
            'manual_contacts' => ['nullable', 'string', 'max:20000'],
            'template_id' => [
                'required',
                'integer',
                Rule::exists('whatsapp_templates', 'id')->where(fn($query) => $query->where('status', WhatsAppTemplate::STATUS_APPROVED)),
            ],
        ]);

        if (($validated['source_type'] ?? null) === 'event_contacts') {
            $request->validate([
                'event_id' => ['nullable', 'integer', 'exists:events,id'],
                'event_ids.*' => ['integer', 'exists:events,id'],
            ]);
        }

        $preview = $eventWhatsAppService->previewTemplateForAudience($validated, 6);

        return response()->json([
            'success' => true,
            'data' => $preview,
        ]);
    }

    public function sendBroadcast(
        Request $request,
        EventWhatsAppService $eventWhatsAppService,
        WhatsAppAccountService $accountService
    ): RedirectResponse
    {
        $this->authorize('whatsapp.send');

        $validated = $request->validate([
            'source_type' => ['required', 'string', 'in:event_contacts,saved_contacts,manual_contacts'],
            'event_filter' => ['nullable', 'string', 'in:single,multiple,past,upcoming,all'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
            'event_ids' => ['nullable', 'array'],
            'event_ids.*' => ['integer', 'exists:events,id'],
            'saved_contact_ids' => ['nullable', 'array'],
            'saved_contact_ids.*' => ['integer', 'exists:whatsapp_contacts,id'],
            'manual_contacts' => ['nullable', 'string', 'max:20000'],
            'template_id' => [
                'required',
                'integer',
                Rule::exists('whatsapp_templates', 'id')->where(fn($query) => $query->where('status', WhatsAppTemplate::STATUS_APPROVED)),
            ],
        ]);

        if (($validated['source_type'] ?? null) === 'event_contacts') {
            $request->validate([
                'event_id' => ['nullable', 'integer', 'exists:events,id'],
                'event_ids.*' => ['integer', 'exists:events,id'],
            ]);
        }

        if ($validated['source_type'] === 'manual_contacts' && trim((string) Arr::get($validated, 'manual_contacts', '')) === '') {
            throw ValidationException::withMessages([
                'manual_contacts' => 'Provide at least one phone number for manual source.',
            ]);
        }

        $activeAccount = $accountService->getActiveAccountForUser($request->user());

        $campaign = $eventWhatsAppService->queueCampaign(array_merge($validated, [
            'account_id' => $activeAccount?->id,
        ]));

        return orgRedirect('admin.whatsapp.events.campaign-history', [
            'campaign' => $campaign->id,
        ])->with('success', 'Event broadcast queued successfully.');
    }

    public function campaignHistory(Request $request, WhatsAppAccountService $accountService): View
    {
        $this->authorize('whatsapp.view');

        $activeAccount = $accountService->getActiveAccountForUser($request->user());
        $activeAccountId = $activeAccount?->id;

        $campaigns = EventWhatsAppCampaign::query()
            ->when($activeAccountId !== null, fn($query) => $query->where('account_id', $activeAccountId))
            ->with(['event:id,title', 'template:id,name,language'])
            ->withCount([
                'recipients as queued_count' => fn($query) => $query->where('status', 'queued'),
                'recipients as sent_count' => fn($query) => $query->where('status', 'sent'),
                'recipients as failed_count' => fn($query) => $query->where('status', 'failed'),
                'recipients as delivered_count' => fn($query) => $query->where('status', 'delivered'),
            ])
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $selectedCampaign = null;
        $selectedCampaignId = (int) $request->query('campaign', 0);

        if ($selectedCampaignId > 0) {
            $selectedCampaign = EventWhatsAppCampaign::query()
                ->when($activeAccountId !== null, fn($query) => $query->where('account_id', $activeAccountId))
                ->with(['event:id,title,event_date,event_time', 'template:id,name,language'])
                ->find($selectedCampaignId);
        }

        $selectedRecipients = collect();

        if ($selectedCampaign) {
            $selectedRecipients = $selectedCampaign->recipients()
                ->with(['registration:id,event_id,name,email,seat_code,created_at', 'registration.event:id,title,event_date', 'contact:id,name'])
                ->orderByDesc('id')
                ->limit(120)
                ->get();
        }

        return view('admin.whatsapp.CampaignHistory', [
            'campaigns' => $campaigns,
            'selectedCampaign' => $selectedCampaign,
            'selectedRecipients' => $selectedRecipients,
        ]);
    }

    protected function paginateCollection(
        Collection $items,
        int $perPage,
        int $page,
        string $pageName,
        ?string $path = null,
        array $query = [],
    ): LengthAwarePaginator {
        $page = max(1, $page);
        $total = $items->count();
        $results = $items->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'pageName' => $pageName,
                'path' => $path ?? LengthAwarePaginator::resolveCurrentPath(),
            ],
        );

        // Re-append all existing query string params so pagination links stay
        // on the correct admin URL rather than defaulting to the site root.
        if (!empty($query)) {
            $paginator->appends($query);
        }

        return $paginator;
    }
}
