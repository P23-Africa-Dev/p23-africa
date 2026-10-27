<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteError;
use Illuminate\Http\Request;

class SiteErrorController extends Controller
{
    public function index(Request $request)
    {
        $errors = SiteError::query()
            ->with('user:id,name,email')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->toString();
                $query->where(function ($inner) use ($term) {
                    $inner->where('path', 'like', '%'.$term.'%')
                        ->orWhere('message', 'like', '%'.$term.'%')
                        ->orWhere('exception_class', 'like', '%'.$term.'%');
                });
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.site-errors.index', compact('errors'));
    }

    public function show(SiteError $siteError)
    {
        $siteError->load('user:id,name,email');

        return view('admin.site-errors.show', compact('siteError'));
    }
}
