<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content_1' => 'required|string',
            'content_2' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $path = $request->file('image')?->store('blogs', 'public');

        Blog::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content_1' => $request->content_1,
            'content_2' => $request->content_2,
            'image_path' => $path,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content_1' => 'required|string',
            'content_2' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        // Remove current image if requested
        if ($request->has('remove_image') && $blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
            $blog->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($blog->image_path) Storage::disk('public')->delete($blog->image_path);
            $blog->image_path = $request->file('image')->store('blogs', 'public');
        }

        // Sanitize and slugify
        $validated['content_1'] = Purifier::clean($request->description);
        $validated['content_2'] = Purifier::clean($request->description);

        $blog->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content_1' => $request->content_1,
            'content_2' => $request->content_2,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image_path) Storage::disk('public')->delete($blog->image_path);
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted.');
    }
}
