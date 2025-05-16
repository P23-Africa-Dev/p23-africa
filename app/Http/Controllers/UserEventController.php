<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class UserEventController extends Controller
{
    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('show-event', compact('event'));
    }
}
