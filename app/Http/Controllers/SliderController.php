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
}
