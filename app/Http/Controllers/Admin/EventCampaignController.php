<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventWhatsAppCampaign;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\EventAudienceService;
use App\Services\WhatsApp\EventWhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

        $audience = $this->paginateCollection($audienceCollection, 20, (int) $request->query('audience_page', 1), 'audience_page');

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
                ->limit(200)
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
            'template_id' => ['required', 'integer', 'exists:whatsapp_templates,id'],
        ]);

        $preview = $eventWhatsAppService->previewTemplateForAudience($validated, 6);

        return response()->json([
            'success' => true,
            'data' => $preview,
        ]);
    }

    public function sendBroadcast(Request $request, EventWhatsAppService $eventWhatsAppService): RedirectResponse
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
            'template_id' => ['required', 'integer', 'exists:whatsapp_templates,id'],
        ]);

        if ($validated['source_type'] === 'manual_contacts' && trim((string) Arr::get($validated, 'manual_contacts', '')) === '') {
            throw ValidationException::withMessages([
                'manual_contacts' => 'Provide at least one phone number for manual source.',
            ]);
        }

        $campaign = $eventWhatsAppService->queueCampaign($validated);

        return orgRedirect('admin.whatsapp.events.campaign-history', [
            'campaign' => $campaign->id,
        ])->with('success', 'Event broadcast queued successfully.');
    }

    public function campaignHistory(Request $request): View
    {
        $this->authorize('whatsapp.view');

        $campaigns = EventWhatsAppCampaign::query()
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

    protected function paginateCollection(Collection $items, int $perPage, int $page, string $pageName): LengthAwarePaginator
    {
        $page = max(1, $page);
        $total = $items->count();
        $results = $items->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'pageName' => $pageName,
            ],
        );
    }
}
