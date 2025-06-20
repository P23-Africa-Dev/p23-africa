<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
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
        $categories = Category::orderBy('id')->get(); // or any preferred ordering
        return view('archive', compact('categories'));
    }

    public function archiveList(Request $request)
    {
        $page = $request->get('page', 1); // Get the current page
        $perPage = 12;

        $categoryName = $request->query('category');

        if ($categoryName) {
            // Filter by category if query exists
            $category = \App\Models\Category::where('name', $categoryName)->firstOrFail();
            $blogs = \App\Models\Blog::where('category_id', $category->id)
                ->latest()
                ->get();

            // No skipping for category view
            $sliced = $blogs->slice(($page - 1) * $perPage, $perPage);
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $sliced,
                $blogs->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $otherCategories = \App\Models\Category::where('id', '!=', $category->id)->get();

            return view('archive-list', [
                'blogs' => $paginated,
                'category' => $category,
                'otherCategories' => $otherCategories,
            ]);
        }

        // Default archive listing (skipping 6 recent)
        $blogs = Blog::with(['category', 'user'])->latest()->get();

        $sliced = $blogs->slice(6 + ($page - 1) * $perPage, $perPage);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $blogs->count() - 6,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $otherCategories = \App\Models\Category::all();

        return view('archive-list', [
            'blogs' => $paginated,
            'category' => null,
            'otherCategories' => $otherCategories,
        ]);
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

    public function categoryView($slug)
    {
        $category = Category::where('name', $slug)->firstOrFail();
        $blogs = $category->blogs()->latest()->paginate(10);
        $otherCategories = Category::where('id', '!=', $category->id)->get();

        return view('category-blogs', compact('category', 'blogs', 'otherCategories'));
    }
}
