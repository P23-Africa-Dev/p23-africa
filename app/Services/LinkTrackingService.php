<?php

namespace App\Services;

use App\Models\TrackedLink;
use App\Models\TrackedLinkClick;
use App\Models\TrackedLinkDailyStat;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class LinkTrackingService
{
    public function trackClick(TrackedLink $trackedLink, Request $request): TrackedLinkClick
    {
        $agent = new Agent();

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;
        $referrer = $request->headers->get('referer');
        $clickedAt = now();

        $deviceType = $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop');
        $browser = $agent->browser();
        $browserVersion = $browser ? $agent->version($browser) : null;
        $platform = $agent->platform();
        $platformVersion = $platform ? $agent->version($platform) : null;

        $fingerprint = hash('sha256', implode('|', [
            $trackedLink->id,
            strtolower((string) $ipAddress),
            strtolower((string) $userAgent),
        ]));

        $isUniqueClick = !TrackedLinkClick::query()
            ->where('tracked_link_id', $trackedLink->id)
            ->where('visitor_fingerprint', $fingerprint)
            ->exists();

        $click = TrackedLinkClick::create([
            'tracked_link_id' => $trackedLink->id,
            'clicked_at' => $clickedAt,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'browser' => $browser,
            'browser_version' => $browserVersion,
            'platform' => $platform,
            'platform_version' => $platformVersion,
            'referrer' => $referrer,
            'session_id' => $sessionId,
            'visitor_fingerprint' => $fingerprint,
            'is_unique_click' => $isUniqueClick,
        ]);

        $trackedLink->increment('total_clicks');

        $date = $clickedAt->toDateString();

        $dailyStat = TrackedLinkDailyStat::query()
            ->where('tracked_link_id', $trackedLink->id)
            ->whereDate('date', $date)
            ->first();

        if (!$dailyStat) {
            $dailyStat = TrackedLinkDailyStat::create([
                'tracked_link_id' => $trackedLink->id,
                'date' => $date,
                'total_clicks' => 0,
                'unique_clicks' => 0,
                'repeated_clicks' => 0,
            ]);
        }

        $dailyStat->increment('total_clicks');
        $dailyStat->increment($isUniqueClick ? 'unique_clicks' : 'repeated_clicks');

        return $click;
    }
}
