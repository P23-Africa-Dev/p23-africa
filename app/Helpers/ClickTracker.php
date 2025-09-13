<?php
// app/Helpers/ClickTracker.php

namespace App\Helpers;

use App\Events\AdminClicksEvent;
use App\Models\Click;
use Jenssegers\Agent\Agent;

class ClickTracker
{
    public static function track($routeName)
    {
        $agent = new Agent;
        $deviceType = $agent->isMobile() ? 'mobile' : 'desktop';

        $clicks = Click::where('route_name', $routeName)
            ->where('device_type', $deviceType)
            ->first();

        if ($clicks) {
            $clicks->increment('click_count');
            $clicks->touch();
        } else {
            Click::create([
                'route_name' => $routeName,
                'device_type' => $deviceType,
                'click_count' => 1,
            ]);
        }

        event(new AdminClicksEvent($clicks));
    }
}
