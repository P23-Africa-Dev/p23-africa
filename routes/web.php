<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SliderController::class, 'showSlider'])->name('homepage');

Route::get('/contact', function () {
    return view('contact');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/services', function () {
    return view('services');
});
Route::get('/event', function () {
    return view('event');
});
Route::get('/all-events', function () {
    return view('all-events');
});

Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
