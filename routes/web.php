<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserEventController;
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
Route::post('/events/{event}/book-seat', [SeatController::class, 'store'])->name('seats.store');
Route::get('/events', [UserEventController::class, 'eventPage'])->name('event');

// Route::get('/all-events', [UserEventController::class, 'allEvents'])->name('events.all-events');
// Route::get('/all-events/search', [UserEventController::class, 'search'])->name('events.search');

Route::get('/all-events', [UserEventController::class, 'allEvents'])->name('events.all-events');
Route::get('/events/search', [UserEventController::class, 'search'])->name('events.search');

Route::post('/event/join', [UserEventController::class, 'join'])->name('event.join');
// Route::get('/event/live/{seatCode}', [UserEventController::class, 'live'])->name('event.live');

Route::get('/event/live/{slug}/{code}', [UserEventController::class, 'liveEvent'])->name('private.access');


// Route::get('/events/all-events', function () {
//     $events = Event::all();
//     return view('all-events', compact('events'));
// })->name('events.all-events');

Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

Route::get('/events/{slug}', [UserEventController::class, 'show'])->name('events.show');

// Route::get('/', function () {
//     return view('welcome');
// });



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/seats', [\App\Http\Controllers\Admin\SeatController::class, 'index'])->name('admin.seats.index');

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
