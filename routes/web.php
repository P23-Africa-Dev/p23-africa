<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Admin\EventController;
use App\Models\Event;

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

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/events', function () {
//     $events = Event::all();
//     return view('events.index', compact('events'));
// });


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('/events', EventController::class);
});



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
