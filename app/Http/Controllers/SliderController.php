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
        $images = ['first-slide.png', 'second-slide.png', 'third-slide.png'];
        $images2 = ['forth-slide.png', 'fifth-slide.png', 'sixth-slide.png'];

        return view('/student', compact('images', 'images2'));
    }
}
