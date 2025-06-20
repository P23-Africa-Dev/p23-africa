<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $page = request()->get('page', 1); // Get the current page
        $perPage = 12;

        $blogs = Blog::latest()->get(); // Get all blogs

        $sliced = $blogs->slice(6 + ($page - 1) * $perPage, $perPage); // Skip first 6, then paginate
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $blogs->count() - 6,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('archive-list', ['olderBlogs' => $paginated]);
    }

    public function searchArchive(Request $request)
    {
        $search = $request->get('query');

        $results = DB::table('blogs')
            ->where('title', 'like', '%' . $search . '%')
            ->orWhere('subtitle', 'like', '%' . $search . '%')
            ->orWhere('content_1', 'like', '%' . $search . '%')
            ->orderByRaw("CASE 
                        WHEN title LIKE ? THEN 1
                        WHEN subtitle LIKE ? THEN 2
                        ELSE 3
                     END", ["%$search%", "%$search%"])
            ->limit(6)
            ->get();

        $html = '';
        foreach ($results as $blog) {
            $url = route('resource-show', $blog->slug);
            $html .= "
        <div class='search-suggestion'>
            <a href='{$url}'>
                <strong>{$blog->title}</strong><br>
                <small>{$blog->subtitle}</small>
            </a>
        </div>";
        }

        return response()->json(['html' => $html]);
    }
}
