<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class ResourceHubController extends Controller
{
    public function resourceHub(){
        $blogs = Blog::latest()->paginate(6);
        return view('resource-hub', compact('blogs'));
    }

    public function resourceDetails($slug){
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return view('view-resource', compact('blog'));
    }

    public function archivePage(){
        return view('archive');
    }
}
