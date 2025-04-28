<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SliderController::class, 'showSlider']);

Route::get('/contact', function () {
    return view('contact');
});
Route::get('/about', function () {
    return view('about');
});
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
