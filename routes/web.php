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
use App\Http\Controllers\Admin\PlaybookController;
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
use Illuminate\Support\Facades\Broadcast;
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

Route::get('/playbook/ultimate-funding/payment', function () {
    return view('playbook.payment');
})->name('playbook.payment');

Route::get('/playbook/ultimate-funding/checkout', function () {
    return view('playbook.checkout');
})->name('playbook.checkout');
// BRN
// Route::get('/brn', function () {
//     ClickTracker::track('BRN_Page');
//     return view('brn');
// })->name('brn');
Route::get('/noel54/interest-form', function () {
    ClickTracker::track('NOEL_Form_Page');
    return view('brn-form');
})->name('brn-form');

Route::post('/noel54/interest-form', [BrnController::class, 'store'])->name('brn.submit');
Route::post('/noel54/check-email', [BrnController::class, 'checkEmail'])->name('brn.check-email');

// CSRF Token Refresh Route - for long forms that may experience session timeout
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.refresh');

// Playbook Download Route
Route::post('/playbook/download', [\App\Http\Controllers\PlaybookController::class, 'download'])->name('playbook.download');

// Sales Book Download Route
Route::post('/sales-book/download', [\App\Http\Controllers\PlaybookController::class, 'salesBookDownload'])->name('sales-book.download');

// Ultimate Funding Download Route (Premium)
Route::post('/ultimate-funding/download', [\App\Http\Controllers\PlaybookController::class, 'ultimateFundingDownload'])->name('ultimate-funding.download');
Route::post('/ultimate-funding/payment-callback', [\App\Http\Controllers\PlaybookController::class, 'ultimateFundingPaymentCallback'])->name('ultimate-funding.payment-callback');

// Stripe Payment Routes
Route::post('/ultimate-funding/create-payment-intent', [\App\Http\Controllers\PlaybookController::class, 'createStripePaymentIntent'])->name('ultimate-funding.create-payment-intent');
Route::post('/ultimate-funding/stripe-payment-callback', [\App\Http\Controllers\PlaybookController::class, 'stripePaymentCallback'])->name('ultimate-funding.stripe-callback');

// Playbook Landing Page
Route::get('/founders-playbook', [\App\Http\Controllers\PlaybookController::class, 'landing'])->name('playbook.landing');
Route::get('/playbook/funding-readiness', [\App\Http\Controllers\PlaybookController::class, 'playbookOne'])->name('playbook.landing1');
Route::get('/playbook/ultimate-funding', [\App\Http\Controllers\PlaybookController::class, 'playbookTwo'])->name('playbook.landing2');


Route::get('/brn-form', function () {
    return redirect('/noel54/interest-form');
});



/* ========== TEST CSRF TOKEN ========== */
Route::get('/test-csrf', function () {
    return view('test-csrf');
});

Route::post('/test-csrf-submit', function () {
    return response()->json(['message' => 'CSRF protection working!']);
})->name('test.csrf');
/* ========== TEST CSRF TOKEN END ========== */



Route::get('/noel54', [BrnController::class, 'brnIndex'])->name('brn');

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




Route::get('/book-consultation', function () {
    return view('consultation.index');
})->name('consultation.index');
Route::get('/book', function () {
    return view('consultation.schedule');
})->name('consultation.schedule');

// Consultation Application Routes
Route::get('/book-consultation/form', [App\Http\Controllers\ConsultationController::class, 'showApplicationForm'])->name('consultation.application-forms');
Route::post('/book-consultation/submit', [App\Http\Controllers\ConsultationController::class, 'submitApplication'])->name('consultation.submit');
Route::post('/book-consultation/save-draft', [App\Http\Controllers\ConsultationController::class, 'saveDraft'])->name('consultation.save-draft');

// Test route for live form capture
Route::get('/test-live-capture', function () {
    return view('test-live-capture');
})->name('test.live-capture');

// Consultation Summary
Route::get('/book-consultation/summary', [App\Http\Controllers\ConsultationController::class, 'showSummary'])->name('consultation.consultation-summary');

// Payment Selection
Route::get('/book-consultation/payment-selection', [App\Http\Controllers\ConsultationController::class, 'showPaymentSelection'])->name('consultation.payment-selection');

// Payment Routes
Route::post('/book-consultation/payment/initiate', [App\Http\Controllers\ConsultationController::class, 'initiatePayment'])->name('consultation.payment.initiate');
Route::post('/book-consultation/payment/process', [App\Http\Controllers\ConsultationController::class, 'processPayment'])->name('consultation.payment.process');
Route::post('/book-consultation/payment/callback', [App\Http\Controllers\ConsultationController::class, 'handlePaymentCallback'])->name('consultation.payment.callback');
Route::post('/book-consultation/payment/create-intent', [App\Http\Controllers\ConsultationController::class, 'createStripePaymentIntent'])->name('consultation.payment.create-intent');
Route::get('/book-consultation/payment/callback/{reference}/{status}', [App\Http\Controllers\ConsultationController::class, 'handlePaymentCallbackLegacy'])->name('consultation.payment.callback.legacy');

// Payment Success
Route::get('/book-consultation/payment-success', [App\Http\Controllers\ConsultationController::class, 'showPaymentSuccess'])->name('consultation.payment-success');

// Scheduling
Route::get('/book-consultation/schedule', [App\Http\Controllers\ConsultationController::class, 'showScheduling'])->name('consultation.schedule-consultation');
Route::post('/book-consultation/schedule', [App\Http\Controllers\ConsultationController::class, 'scheduleSession'])->name('consultation.schedule.submit');

// Test route for chat functionality (remove in production)
Route::get('/chat-test', function () {
    return view('chat-test');
})->name('chat.test');

// Calendar integration
Route::get('/book-consultation/download-ics/{filename}', [App\Http\Controllers\ConsultationController::class, 'downloadIcs'])->name('consultation.download-ics');
Route::get('/google/calendar/auth', [App\Http\Controllers\ConsultationController::class, 'googleCalendarAuth'])->name('google.calendar.auth');
Route::get('/google/calendar/callback', [App\Http\Controllers\ConsultationController::class, 'googleCalendarCallback'])->name('google.calendar.callback');
Route::get('/email-test', function () {
    return view('emails.consultation-scheduled');
})->name('feedback.thankyou');

// Route::get('/events/all-events', function () {
//     $events = Event::all();
//     return view('all-events', compact('events'));
// })->name('events.all-events');

// Contact form - increased throttle to 15 requests per minute for better UX with retries
Route::post('/contact', [ContactController::class, 'submitForm'])->middleware('throttle:15,1')->name('contact.submit');
// Save draft - increased throttle to 60 requests per minute for live form capture
Route::post('/contact/save-draft', [ContactController::class, 'saveDraft'])->middleware('throttle:60,1')->name('contact.save-draft');

// Advanced tracking endpoint
Route::post('/track-advanced', [\App\Http\Controllers\AdvancedTrackingController::class, 'track'])->name('track.advanced');

Route::get('/events/{slug}', [UserEventController::class, 'show'])->name('events.show');

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware(['auth', 'check.suspended', 'role:admin|supervisor|staff'])->prefix('admin')->name('admin.')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin|supervisor')->group(function () {
        Route::resource('staff', StaffController::class);

        // Calendar
        Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('calendar.index');

        Route::post('/staff/{staff}/suspend', [StaffController::class, 'suspend'])->name('staff.suspend');
        Route::post('/staff/{staff}/reactivate', [StaffController::class, 'reactivate'])->name('staff.reactivate');
        Route::post('/staff/{staff}/promote', [StaffController::class, 'promote'])->name('staff.promote');

        // NOEL
        Route::get('/brn', [\App\Http\Controllers\Admin\BrnController::class, 'adminView'])->name('brn.index');
        Route::get('/brn/{id}', [\App\Http\Controllers\Admin\BrnController::class, 'show'])->name('brn.show');
        Route::delete('/brn/{id}', [\App\Http\Controllers\Admin\BrnController::class, 'destroy'])->name('brn.destroy');
        Route::post('/brn/{id}/approve', [\App\Http\Controllers\Admin\BrnController::class, 'approve'])->name('brn.approve');
        Route::post('/brn/{id}/decline', [\App\Http\Controllers\Admin\BrnController::class, 'decline'])->name('brn.decline');

        // Ultimate Funding
        Route::resource('/ultimatefunding', \App\Http\Controllers\Admin\UltimateFundingController::class);

        // Consultation Management
        Route::get('/consultations', [\App\Http\Controllers\Admin\ConsultationController::class, 'index'])->name('consultation.index');
        Route::get('/consultations/{id}', [\App\Http\Controllers\Admin\ConsultationController::class, 'show'])->name('consultation.show');
        Route::delete('/consultation/{id}', [\App\Http\Controllers\Admin\ConsultationController::class, 'destroy'])->name('consultation.destroy');
        Route::post('/consultation/bulk-delete', [\App\Http\Controllers\Admin\ConsultationController::class, 'bulkDelete'])->name('consultation.bulk-delete');
        Route::post('/consultations/{id}/update-status', [\App\Http\Controllers\Admin\ConsultationController::class, 'updateStatus'])->name('consultation.updateStatus');
        Route::post('/consultations/{id}/reply', [\App\Http\Controllers\Admin\ConsultationController::class, 'sendReply'])->name('consultation.reply');
        Route::post('/consultation/chat/mark-read/{messageId}', [\App\Http\Controllers\Admin\ConsultationController::class, 'markMessageAsRead'])->name('consultation.chat.markRead');
        Route::get('/consultation/draft-stats', [\App\Http\Controllers\Admin\ConsultationController::class, 'getDraftStats'])->name('consultation.draft-stats');
        Route::get('/consultation/applications-table', [\App\Http\Controllers\Admin\ConsultationController::class, 'getApplicationsTable'])->name('consultation.applications-table');
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
    Route::get('/events/booking-fields', [EventController::class, 'getBookingFields'])->name('events.booking-fields');

    Route::get('/events/{event}/clicks', [EventController::class, 'viewClicks'])->name('events.clicks');
    // });

    // Route::middleware('permission:resource_hub')->group(function () {
    Route::resource('blogs', BlogController::class);
    Route::resource('/blogs/categories', CategoryController::class);
    // });

    Route::resource('/playbooks', PlaybookController::class);
    Route::get('/playbooks-dashboard', [PlaybookController::class, 'dashboard'])->name('playbooks.dashboard');

    Route::resource('/salesbooks', \App\Http\Controllers\Admin\SalesBookController::class);



    // Contact management routes
    // Route::get('/contact', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
    // Route::get('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
    // Route::get('/contact/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contact.export');

    // Contact management routes (accessible to admin and staff)
    Route::get('/contact', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
    Route::get('/contact/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contact.export');
    Route::get('/contact/export-xlsx', [\App\Http\Controllers\Admin\ContactController::class, 'exportXlsx'])->name('contact.export.xlsx');

    // Admin-only contact management routes
    Route::middleware('role:admin')->group(function () {
        Route::delete('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contact.destroy');
        Route::post('/contact/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDestroy'])->name('contact.bulk-delete');
    });

    // Route::middleware('permission:view_students')->group(function () {
    Route::resource('students', StudentController::class);
    // });

    Route::get('/clicks/report', [ClickReportController::class, 'index'])->name('clicks.report');
    Route::get('/clicks/export', [ClickReportController::class, 'export'])->name('clicks.export');
    Route::post('/clicks/backfill-countries', [ClickReportController::class, 'backfillCountries'])->name('clicks.backfill-countries');

    // Playbook email resend functionality
    Route::post('/playbooks/resend-funding', [\App\Http\Controllers\PlaybookController::class, 'resendFundingReadinessPending'])->name('playbooks.resend-funding');

    // Project Management
    Route::get('/projects/gantt', [\App\Http\Controllers\Admin\ProjectController::class, 'gantt'])->name('projects.gantt');
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
    Route::patch('/projects/{project}/status', [\App\Http\Controllers\Admin\ProjectController::class, 'updateStatus'])->name('projects.update-status');

    // Task Management
    Route::get('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/kanban', [\App\Http\Controllers\Admin\TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::get('/tasks/create', [\App\Http\Controllers\Admin\TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [\App\Http\Controllers\Admin\TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::post('/tasks/update-order', [\App\Http\Controllers\Admin\TaskController::class, 'updateOrder'])->name('tasks.update-order');
    Route::post('/tasks/{task}/assign', [\App\Http\Controllers\Admin\TaskController::class, 'assign'])->name('tasks.assign');

    // Task Comments
    Route::get('/tasks/{task}/comments', [\App\Http\Controllers\Admin\TaskController::class, 'getComments'])->name('tasks.comments');
    Route::post('/tasks/{task}/comments', [\App\Http\Controllers\Admin\TaskController::class, 'addComment'])->name('tasks.add-comment');
    Route::delete('/tasks/{task}/comments/{comment}', [\App\Http\Controllers\Admin\TaskController::class, 'deleteComment'])->name('tasks.delete-comment');
});



Broadcast::routes(['middleware' => ['auth', 'check.suspended', 'role:admin|supervisor|staff']]);


Route::get('/admin/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.activity.logs');

Route::get('/admin/activity-logs/unseen-count', [\App\Http\Controllers\Admin\ActivityLogController::class, 'getUnseenCount'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.activity.unseen-count');

Route::get('/admin/activity-logs/new-activities', [\App\Http\Controllers\Admin\ActivityLogController::class, 'getNewActivities'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.activity.new-activities');

Route::post('/admin/activity-logs/mark-seen', [\App\Http\Controllers\Admin\ActivityLogController::class, 'markAsSeen'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.activity.mark-seen');

// Comprehensive Notification Routes (for all authenticated users)
Route::middleware(['auth', 'check.suspended'])->prefix('admin/notifications')->name('admin.notifications.')->group(function () {
    // Get all notifications
    Route::get('/all', [\App\Http\Controllers\Admin\NotificationController::class, 'getAllNotifications'])
        ->name('all');

    // Get all unread count
    Route::get('/all-unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getAllUnreadCount'])
        ->name('all-unread-count');

    // Get latest unread for browser notifications
    Route::get('/latest-unread', [\App\Http\Controllers\Admin\NotificationController::class, 'getLatestUnread'])
        ->name('latest-unread');

    // Legacy task notifications
    Route::get('/tasks', [\App\Http\Controllers\Admin\NotificationController::class, 'getTaskNotifications'])
        ->name('tasks');

    Route::get('/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])
        ->name('unread-count');

    Route::post('/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])
        ->name('mark-read');

    Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])
        ->name('mark-all-read');
});

Route::middleware(['auth', 'check_role:supervisor'])->group(function () {
    // Supervisor-only routes here
});



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add these routes in your admin section



require __DIR__ . '/auth.php';

Route::get('/test-admin-debug', function () {
    $applications = App\Models\ConsultationApplication::with(['payments', 'scheduledSessions'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    $output = 'Applications found: ' . $applications->count() . PHP_EOL . PHP_EOL;

    foreach ($applications as $app) {
        $output .= 'ID: ' . $app->id . PHP_EOL;
        $output .= 'Name: ' . ($app->name ?? 'NULL') . PHP_EOL;
        $output .= 'Email: ' . ($app->email ?? 'NULL') . PHP_EOL;
        $output .= 'Status: ' . $app->status . PHP_EOL;
        $output .= 'Is Draft: ' . ($app->isDraft() ? 'YES' : 'NO') . PHP_EOL;
        $output .= 'Created: ' . $app->created_at . PHP_EOL;
        $output .= 'Session ID: ' . ($app->session_id ?? 'NULL') . PHP_EOL;
        $output .= '---' . PHP_EOL;
    }

    return response($output, 200, ['Content-Type' => 'text/plain']);
});

Route::get('/test-admin-consultation', function () {
    // Simulate the exact admin controller logic
    $query = App\Models\ConsultationApplication::with(['payments', 'scheduledSessions'])
        ->orderBy('created_at', 'desc');

    $applications = $query->paginate(15);

    // Get statistics
    $controller = new App\Http\Controllers\Admin\ConsultationController();
    $stats = $controller->getConsultationStats();

    $html = '<h1>Admin Consultation Dashboard Test</h1>';
    $html .= '<p>Total Applications: ' . $stats['total_applications'] . '</p>';
    $html .= '<p>Total Drafts: ' . $stats['total_drafts'] . '</p>';
    $html .= '<p>Active Drafts: ' . $stats['active_drafts'] . '</p>';
    $html .= '<h2>Applications:</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Is Draft</th><th>Session ID</th></tr>';

    foreach ($applications as $app) {
        $html .= '<tr>';
        $html .= '<td>' . $app->id . '</td>';
        $html .= '<td>' . ($app->name ?? 'NULL') . '</td>';
        $html .= '<td>' . ($app->email ?? 'NULL') . '</td>';
        $html .= '<td>' . $app->status . '</td>';
        $html .= '<td>' . ($app->isDraft() ? 'YES' : 'NO') . '</td>';
        $html .= '<td>' . ($app->session_id ?? 'NULL') . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    return $html;
});

Route::get('/test-applications-table', function () {
    $applications = App\Models\ConsultationApplication::with(['payments', 'scheduledSessions'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('admin.consultation.partials.applications-table', compact('applications'))->render();
});
