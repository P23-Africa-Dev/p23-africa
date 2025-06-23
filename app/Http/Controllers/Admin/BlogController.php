<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
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
        $categories = Category::all();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:999',
            'content_1' => 'required|string',
            'content_2' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // ✅ Clean content if needed
        $data['content_1'] = Purifier::clean($data['content_1']);
        $data['content_2'] = Purifier::clean($data['content_2']);

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }

        // ✅ Add user ID (admin creating the blog)
        $data['user_id'] = Auth::id();

        // ✅ Save the blog
        Blog::create($data);

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
        $categories = Category::all();

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:999',
            'content_1' => 'required|string',
            'content_2' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Remove image if requested
        if ($request->has('remove_image') && $blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
            $blog->image_path = null;
        }

        // Upload new image
        if ($request->hasFile('image')) {
            if ($blog->image_path) {
                Storage::disk('public')->delete($blog->image_path);
            }
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }

        // 🛠️ Purify and assign to $data
        $data['content_1'] = Purifier::clean($data['content_1']);
        $data['content_2'] = Purifier::clean($data['content_2']);

        // Update blog
        $blog->update($data);

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
