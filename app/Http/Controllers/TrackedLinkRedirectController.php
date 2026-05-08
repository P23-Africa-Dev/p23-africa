<?php

namespace App\Http\Controllers;

use App\Models\TrackedLink;
use App\Services\LinkTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackedLinkRedirectController extends Controller
{
    /**
     * Track click activity then redirect to the external destination.
     */
    public function __invoke(Request $request, string $tracking_code, LinkTrackingService $linkTrackingService): RedirectResponse
    {
        $trackedLink = TrackedLink::query()
            ->where('tracking_code', $tracking_code)
            ->where('is_active', true)
            ->firstOrFail();

        try {
            $linkTrackingService->trackClick($trackedLink, $request);
        } catch (\Throwable $exception) {
            // Do not block redirects when tracking fails.
            Log::error('Tracked link click logging failed', [
                'tracking_code' => $tracking_code,
                'link_id' => $trackedLink->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()->away($trackedLink->destination_url);
    }
}
