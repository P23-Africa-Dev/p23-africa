<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\UserEventController;
use App\Mail\ChallengeSubmission;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;



Route::get('/admin/login', [LoginController::class, 'viewLogin'])->name('login');
// Route::get('/login', [LoginController::class, 'viewLogin'])->name('login');
// Route::get('/admin/login', function () {
//     if (Auth::check()) {
//         return redirect('/admin/dashboard');
//     }

//     // Breeze with Blade:
//     return view('auth.login');

// })->name('login');
// Route::get('/login', function () {
//     if (Auth::check()) {
//         return redirect('/admin/dashboard');
//     }

//     // Breeze with Blade:
//     return view('auth.login');
// })->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login_user');
Route::get('/login', function () {
    return redirect('/admin/login');
});


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
Route::get('/sales', function () {
    return view('sales');
})->name('sales');

Route::get('/quiz', function () {
    return view('sales-quiz');
})->name('quiz');

Route::get('/quiz/result/fragile-funnel', fn() => view('quiz-answers.result-a'))->name('fragile-funnel');
Route::get('/quiz/result/hidden-powerhouse', fn() => view('quiz-answers.result-b'))->name('hidden-powerhouse');
Route::get('/quiz/result/cloudy-climber', fn() => view('quiz-answers.result-c'))->name('cloudy-climber');


Route::get('/quiz/start', function () {
    return view('start-quiz');
})->name('start-quiz');

Route::get('/test-email', function () {
    return view('emails.challenge_result', ['label' => 'Hidden Powerhouse']);
});


// Route::post('/submit-challenge', function (Request $request) {
//     $data = $request->only(['challenge1', 'challenge2', 'challenge3', 'label']);

//     Mail::to('nurudeen@p23africa.com')->send(new ChallengeSubmission($data));

//     return response()->json(['status' => 'success']);
// });
Route::post('/submit-challenge', [QuizController::class, 'submitChallenge']);



// Route::post('/events/{event}/book-seat', [SeatController::class, 'store'])->name('seats.store');
Route::post('/book-seat/{event_id}', [SeatController::class, 'store'])->name('seats.store');

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

    Route::post('/events/{id}/send-reminder', [EventController::class, 'sendReminder'])
        ->name('events.sendReminder');


    Route::get('/seats', [\App\Http\Controllers\Admin\SeatController::class, 'index'])->name('admin.seats.index');

    Route::resource('/events', EventController::class);
    Route::get('/events/{event}/bookings', [EventController::class, 'showBookings'])->name('events.bookings');
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
