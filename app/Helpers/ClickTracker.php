<?php
// app/Helpers/ClickTracker.php

namespace App\Helpers;

use App\Models\Click;
use Jenssegers\Agent\Agent;

class ClickTracker
{
    public static function track($routeName)
    {
        $agent = new Agent;
        $deviceType = $agent->isMobile() ? 'mobile' : 'desktop';

        $click = Click::where('route_name', $routeName)
            ->where('device_type', $deviceType)
            ->first();

        if ($click) {
            $click->increment('click_count');
            $click->touch();
        } else {
            Click::create([
                'route_name' => $routeName,
                'device_type' => $deviceType,
                'click_count' => 1,
            ]);
        }
    }
}
