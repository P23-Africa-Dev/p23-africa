<?php

use App\Helpers\ClickTracker;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClickReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrnController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResourceHubController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\submitStudent;
use App\Http\Controllers\UserEventController;
use App\Mail\ChallengeSubmission;
use App\Models\Click;
use App\Models\Event;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;


Route::get('/run-schedule', function () {
    if (request()->get('token') !== 'YOUR_SECRET_TOKEN') {
        abort(403, 'Unauthorized');
    }

    Artisan::call('schedule:run');
    return 'Schedule run executed!';
});


Route::get('/admin/login', [LoginController::class, 'viewLogin'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login_user');
Route::get('/login', function () {
    return redirect('/admin/login');
});

/**
 * This route increments the click counter and redirects to the target route.
 */
Route::get('/our-sales', function () {
    $routeName = 'sales';

    $click = Click::firstOrCreate(
        ['route_name' => $routeName],
        ['click_count' => 0]
    );

    $click->increment('click_count');

    return redirect()->route($routeName);
})->name('redirect.with.count');


Route::get('/', [SliderController::class, 'showSlider'])->name('homepage');

Route::get('/contact', function () {
    ClickTracker::track('Contact_Page');
    return view('contact');
});
Route::get('/about', function () {
    ClickTracker::track('About_Page');
    return view('about');
});
Route::get('/services', function () {
    ClickTracker::track('Service_Page');
    return view('services');
});
Route::get('/sales', function () {
    ClickTracker::track('Sales_Page');
    return view('sales');
})->name('sales');

Route::get('/quiz', function () {
    ClickTracker::track('Quiz_Page');
    return view('sales-quiz');
})->name('quiz');

Route::get('/device-display', function () {
    return view('device');
})->name('device');

// BRN
// Route::get('/brn', function () {
//     ClickTracker::track('BRN_Page');
//     return view('brn');
// })->name('brn');
Route::get('/brn-form', function () {
    ClickTracker::track('BRN_Form_Page');
    return view('brn-form');
})->name('brn-form');

Route::post('/brn-form', [BrnController::class, 'store'])->name('brn.submit');


Route::get('brn', [BrnController::class, 'brnIndex'])->name('brn');

// Student
Route::get('/student', [SliderController::class, 'showSliderStudent'])->name('student');
Route::post('/submit-student', [submitStudent::class, 'submitStudent']);


// Route::get('/student', function () {
//     return view('student');
// })->name('student');

/**
 * Route to display click counts.
 */


Route::get('/resource-hub', [ResourceHubController::class, 'resourceHub'])->name('resource-hub');
Route::get('/resource/{slug}', [ResourceHubController::class, 'resourceDetails'])->name('resource-show');
Route::get('/res/category/{slug}', [ResourceHubController::class, 'categoryView'])->name('archive.category');


Route::get('/res/archive', [ResourceHubController::class, 'archivePage'])->name('archive-page');
Route::get('/res/archive/list', [ResourceHubController::class, 'archiveList'])->name('archive-list');
Route::get('/res/archive/search', [ResourceHubController::class, 'searchArchive'])->name('archive.search');


Route::get('/quiz/result/fragile-funnel', fn() => view('quiz-answers.result-a'))->name('fragile-funnel');
Route::get('/quiz/result/hidden-powerhouse', fn() => view('quiz-answers.result-b'))->name('hidden-powerhouse');
Route::get('/quiz/result/cloudy-climber', fn() => view('quiz-answers.result-c'))->name('cloudy-climber');


Route::get('/quiz/start', function () {
    ClickTracker::track('Quiz_Start_Page');
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

Route::get('/download-ics/{booking}', [App\Http\Controllers\CalendarController::class, 'downloadIcs'])->name('download.ics');

Route::get('/feedback', [FeedbackController::class, 'showForm'])->name('feedback.form');
Route::post('/feedback', [FeedbackController::class, 'submit'])->name('feedback.submit');

Route::get('/feedback/thank-you', function () {
    return view('feedback.thankyou');
})->name('feedback.thankyou');

// Route::get('/events/all-events', function () {
//     $events = Event::all();
//     return view('all-events', compact('events'));
// })->name('events.all-events');

Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

Route::get('/events/{slug}', [UserEventController::class, 'show'])->name('events.show');

// Route::get('/', function () {
//     return view('welcome');
// });



Route::middleware(['auth', 'check.suspended', 'role:admin|staff'])->prefix('admin')->name('admin.')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('staff', StaffController::class);
        Route::post('/staff/{staff}/suspend', [StaffController::class, 'suspend'])->name('staff.suspend');
        Route::post('/staff/{staff}/reactivate', [StaffController::class, 'reactivate'])->name('staff.reactivate');
        Route::post('/staff/{staff}/promote', [StaffController::class, 'promote'])->name('staff.promote');
    });

    // Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    // Route::post('/staff/store', [StaffController::class, 'store'])->name('admin.staff.store');

    // Route::middleware('permission:manage_events')->group(function () {
        Route::post('/events/{id}/send-reminder', [EventController::class, 'sendReminder'])
            ->name('events.sendReminder');

        Route::post('/events/{event}/send-feedback', [EventController::class, 'sendFeedback'])->name('events.sendFeedback');

        Route::get('/events/{event}/feedbacks', [EventController::class, 'viewFeedbacks'])->name('events.feedbacks');

        Route::get('/seats', [\App\Http\Controllers\Admin\SeatController::class, 'index'])->name('admin.seats.index');

        Route::resource('/events', EventController::class);
        Route::get('/events/{event}/bookings', [EventController::class, 'showBookings'])->name('events.bookings');

        Route::get('/events/{event}/clicks', [EventController::class, 'viewClicks'])->name('events.clicks');
    // });

    // Route::middleware('permission:resource_hub')->group(function () {
        Route::resource('blogs', BlogController::class);
        Route::resource('/blogs/categories', CategoryController::class);
    // });

    // Route::middleware('permission:view_students')->group(function () {
        Route::resource('students', StudentController::class);
    // });

    Route::get('/clicks/report', [ClickReportController::class, 'index'])->name('clicks.report');

    Route::get('/brn', [\App\Http\Controllers\Admin\BrnController::class, 'adminView']);
    Route::post('/brn/{id}/approve', [\App\Http\Controllers\Admin\BrnController::class, 'approve'])->name('brn.approve');
    Route::post('/brn/{id}/decline', [\App\Http\Controllers\Admin\BrnController::class, 'decline'])->name('brn.decline');
});


Route::get('/admin/activity-logs', function () {
    $logs = \Spatie\Activitylog\Models\Activity::latest()->paginate(30);
    return view('admin.activity_logs', compact('logs'));
})->middleware(['auth', 'role:admin'])->name('admin.activity.logs');

Route::middleware(['auth', 'check_role:staff'])->group(function () {
    // Staff-only routes here
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
