<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Models\Event;
use Illuminate\Http\Request;

class BrnController extends Controller
{
    public function brnIndex()
    {
        $events = Event::whereIn('display', ['open_to_all', 'brn_only'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->take(2) // or ->limit(5)
            ->get();

        ClickTracker::track('BRN_Page');

        return view('brn', compact('events'));
    }
}
