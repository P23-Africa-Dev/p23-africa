<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use Illuminate\Http\Request;

class ClickReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Click::query();

        if ($filter === 'today') {
            $query->whereDate('updated_at', now());
        } elseif ($filter === '7days') {
            $query->where('updated_at', '>=', now()->subDays(7));
        }

        $clicks = $query->orderByDesc('updated_at')->get();

        return view('admin.clicks.index', compact('clicks', 'filter'));
    }
}
