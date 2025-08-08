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
        try{
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:999',
                'content_1' => 'required|string',
                'content_2' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'pdf' => 'nullable|mimes:pdf|max:10240', // allow PDF up to 10MB
                'category_id' => 'nullable|exists:categories,id',
            ]);

            // ✅ Handle image upload
            if ($request->hasFile('image')) {
                try {
                    $data['image_path'] = $request->file('image')->store('blogs', 'public');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to upload image. Please try again.');
                }
                // $data['image_path'] = $request->file('image')->store('blogs', 'public');
            }

            if ($request->hasFile('pdf')) {
                try {
                    $data['pdf_path'] = $request->file('pdf')->store('blogs/pdfs', 'public');
                } catch (\Exception $e) {
                    // Clean up image if it was uploaded
                    if (isset($data['image_path'])) {
                        Storage::disk('public')->delete($data['image_path']);
                    }
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to upload PDF. Please try again.');
                }
                // $data['pdf_path'] = $request->file('pdf')->store('blogs/pdfs', 'public');
            }

            // ✅ Add user ID (admin creating the blog)
            $data['user_id'] = Auth::id();

            // ✅ Save the blog
            Blog::create($data);

            return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Clean up any uploaded files
            if (isset($data['image_path'])) {
                Storage::disk('public')->delete($data['image_path']);
            }
            if (isset($data['pdf_path'])) {
                Storage::disk('public')->delete($data['pdf_path']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the blog. Please try again.');
        }
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
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:999',
                'content_1' => 'required|string',
                'content_2' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'pdf' => 'nullable|mimes:pdf|max:10240',
                'created_at' => 'nullable|date',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            // Store original paths in case we need to rollback
            $originalImagePath = $blog->image_path;
            $originalPdfPath = $blog->pdf_path;

            try {
                // Handle image removal and upload
                if ($request->has('remove_image') && $blog->image_path) {
                    Storage::disk('public')->delete($blog->image_path);
                    $blog->image_path = null;
                }

                if ($request->hasFile('image')) {
                    if ($blog->image_path) {
                        Storage::disk('public')->delete($blog->image_path);
                    }
                    $data['image_path'] = $request->file('image')->store('blogs', 'public');
                }

                // Handle PDF removal and upload
                if ($request->has('remove_pdf') && $blog->pdf_path) {
                    Storage::disk('public')->delete($blog->pdf_path);
                    $blog->pdf_path = null;
                }

                if ($request->hasFile('pdf')) {
                    if ($blog->pdf_path) {
                        Storage::disk('public')->delete($blog->pdf_path);
                    }
                    $data['pdf_path'] = $request->file('pdf')->store('blogs/pdfs', 'public');
                }

                // Set created_at if provided
                if (!empty($data['created_at'])) {
                    $blog->created_at = $data['created_at'];
                }

                // Update blog
                $blog->update($data);

                return redirect()->route('admin.blogs.index')
                    ->with('success', 'Blog updated successfully.');
            } catch (\Exception $e) {
                // Rollback file changes if something went wrong
                if (isset($data['image_path'])) {
                    Storage::disk('public')->delete($data['image_path']);
                    if ($originalImagePath) {
                        Storage::disk('public')->put($originalImagePath, Storage::disk('public')->get($originalImagePath));
                    }
                }
                if (isset($data['pdf_path'])) {
                    Storage::disk('public')->delete($data['pdf_path']);
                    if ($originalPdfPath) {
                        Storage::disk('public')->put($originalPdfPath, Storage::disk('public')->get($originalPdfPath));
                    }
                }

                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the blog. Please try again.');
        }
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
