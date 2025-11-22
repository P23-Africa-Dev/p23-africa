<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index()
    {
        return view('admin.calendar.index');
    }
}
