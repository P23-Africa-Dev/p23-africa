<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:999',
                'content_1' => 'required|string',
                'content_2' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
                'pdf' => 'nullable|mimes:pdf|max:10240',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $validatedData['image_path'] = $request->file('image')->store('blogs', 'public');
                Log::info('Image uploaded successfully', ['path' => $validatedData['image_path']]);
            }

            // Handle PDF upload
            if ($request->hasFile('pdf')) {
                $validatedData['pdf_path'] = $request->file('pdf')->store('blogs/pdfs', 'public');
                Log::info('PDF uploaded successfully', ['path' => $validatedData['pdf_path']]);
            }

            // Handle empty category_id - convert empty string to NULL
            if (isset($validatedData['category_id']) && $validatedData['category_id'] === '') {
                $validatedData['category_id'] = null;
            }

            // Add user ID (admin creating the blog)
            $validatedData['user_id'] = Auth::id();

            // Create the blog
            $blog = Blog::create($validatedData);

            Log::info('Blog created successfully', ['blog_id' => $blog->id, 'title' => $blog->title]);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Blog creation validation failed', ['errors' => $e->validator->errors()->toArray()]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Blog creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Clean up uploaded files if they exist
            if (isset($validatedData['image_path'])) {
                Storage::disk('public')->delete($validatedData['image_path']);
            }
            if (isset($validatedData['pdf_path'])) {
                Storage::disk('public')->delete($validatedData['pdf_path']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create blog: ' . $e->getMessage());
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
            // Log the incoming request data for debugging
            Log::info('Blog update request received', [
                'blog_id' => $blog->id,
                'request_data' => $request->all()
            ]);

            // Validate the request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:999',
                'content_1' => 'required|string',
                'content_2' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
                'pdf' => 'nullable|mimes:pdf|max:10240',
                'created_at' => 'nullable|date',
                'category_id' => 'nullable|exists:categories,id',
                'remove_image' => 'nullable|boolean',
                'remove_pdf' => 'nullable|boolean',
            ]);

            Log::info('Validation passed', ['validated_data' => $validatedData]);

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                Log::info('Processing image removal', ['current_image' => $blog->image_path]);
                
                if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
                    Storage::disk('public')->delete($blog->image_path);
                    Log::info('Old image deleted', ['path' => $blog->image_path]);
                }
                $validatedData['image_path'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists and not already removed
                if ($blog->image_path && Storage::disk('public')->exists($blog->image_path) && !($request->has('remove_image') && $request->remove_image == '1')) {
                    Storage::disk('public')->delete($blog->image_path);
                    Log::info('Old image replaced', ['old_path' => $blog->image_path]);
                }

                // Upload new image
                $validatedData['image_path'] = $request->file('image')->store('blogs', 'public');
                Log::info('New image uploaded', ['path' => $validatedData['image_path']]);
            }

            // Handle PDF removal
            if ($request->has('remove_pdf') && $request->remove_pdf == '1') {
                Log::info('Processing PDF removal', ['current_pdf' => $blog->pdf_path]);
                
                if ($blog->pdf_path && Storage::disk('public')->exists($blog->pdf_path)) {
                    Storage::disk('public')->delete($blog->pdf_path);
                    Log::info('Old PDF deleted', ['path' => $blog->pdf_path]);
                }
                $validatedData['pdf_path'] = null;
            }

            // Handle new PDF upload
            if ($request->hasFile('pdf')) {
                // Delete old PDF if exists and not already removed
                if ($blog->pdf_path && Storage::disk('public')->exists($blog->pdf_path) && !($request->has('remove_pdf') && $request->remove_pdf == '1')) {
                    Storage::disk('public')->delete($blog->pdf_path);
                    Log::info('Old PDF replaced', ['old_path' => $blog->pdf_path]);
                }

                // Upload new PDF
                $validatedData['pdf_path'] = $request->file('pdf')->store('blogs/pdfs', 'public');
                Log::info('New PDF uploaded', ['path' => $validatedData['pdf_path']]);
            }

            // Handle created_at field
            if (!empty($validatedData['created_at'])) {
                // Convert the datetime-local format to proper datetime
                $validatedData['created_at'] = date('Y-m-d H:i:s', strtotime($validatedData['created_at']));
            } else {
                // Remove from array if not provided to avoid overwriting
                unset($validatedData['created_at']);
            }

            // Handle empty category_id - convert empty string to NULL
            if (isset($validatedData['category_id']) && $validatedData['category_id'] === '') {
                $validatedData['category_id'] = null;
            }

            // Remove the removal flags from data as they're not database fields
            unset($validatedData['remove_image'], $validatedData['remove_pdf']);

            // Update the blog
            $blog->update($validatedData);

            Log::info('Blog updated successfully', [
                'blog_id' => $blog->id,
                'title' => $blog->title,
                'updated_fields' => array_keys($validatedData)
            ]);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Blog update validation failed', [
                'blog_id' => $blog->id,
                'errors' => $e->validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Blog update failed', [
                'blog_id' => $blog->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update blog: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        try {
            // Delete associated files
            if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
                Storage::disk('public')->delete($blog->image_path);
                Log::info('Blog image deleted', ['path' => $blog->image_path]);
            }

            if ($blog->pdf_path && Storage::disk('public')->exists($blog->pdf_path)) {
                Storage::disk('public')->delete($blog->pdf_path);
                Log::info('Blog PDF deleted', ['path' => $blog->pdf_path]);
            }

            // Delete the blog record
            $blogTitle = $blog->title;
            $blog->delete();

            Log::info('Blog deleted successfully', ['title' => $blogTitle]);

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Blog deletion failed', [
                'blog_id' => $blog->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('admin.blogs.index')
                ->with('error', 'Failed to delete blog: ' . $e->getMessage());
        }
    }
}
