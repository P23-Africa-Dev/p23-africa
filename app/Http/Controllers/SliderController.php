<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function showSlider(){
        $images = ['donald.png', 'jane.jpeg', 'man.png', 'margaret.png'];
        $images2 = ['alhaji.png', 'lanre.jpeg', 'motun.png', 'victor.png'];

        return view('/welcome', compact('images', 'images2'));
    }

    public function showSliderStudent()
    {
        $images = ['driven-bg-1.png', 'driven-bg-4.png', 'driven-bg-1.png', 'driven-bg-4.png'];
        $images2 = ['driven-bg-2.png', 'driven-bg-3.png', 'driven-bg-2.png', 'driven-bg-3.png'];

        return view('/student', compact('images', 'images2'));
    }
}
