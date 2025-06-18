<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class ResourceHubController extends Controller
{
    public function resourceHub()
    {
        // $blogs = Blog::latest()->paginate(6);
        $recentBlogs = Blog::latest()->take(6)->get(); // Get 6 most recent blogs
        $olderBlogs = Blog::latest()->skip(6)->paginate(10); // Get the rest for archive

        return view('resource-hub', compact('recentBlogs', 'olderBlogs'));
    }

    public function resourceDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return view('view-resource', compact('blog'));
    }

    public function archivePage()
    {
        return view('archive');
    }

    public function archiveList()
    {
        $olderBlogs = Blog::orderBy('created_at', 'desc')
            ->skip(6)
            ->paginate(12);

        return view('archive-list', compact('olderBlogs'));
    }
}
