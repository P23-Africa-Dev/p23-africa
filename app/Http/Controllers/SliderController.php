<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Models\Blog;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function showSlider(){
        $blogs = Blog::latest()->take(6)->get();

        $images = ['donald.png', 'jane.jpeg', 'man.png', 'margaret.png'];
        $images2 = ['alhaji.png', 'lanre.jpeg', 'motun.png', 'victor.png'];

        ClickTracker::track('Home_Page');

        return view('/welcome', compact('images', 'images2', 'blogs'));
    }

    public function showSliderStudent()
    {
        $images = ['first-slide.png', 'second-slide.png', 'third-slide.png'];
        $images2 = ['forth-slide.png', 'fifth-slide.png', 'sixth-slide.png'];

        ClickTracker::track('Student_Page');

        return view('/student', compact('images', 'images2'));
    }
}
