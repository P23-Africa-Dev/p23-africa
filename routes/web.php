<?php

use App\Helpers\ClickTracker;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClickReportController;
use App\Http\Controllers\Admin\TrackedLinkAnalyticsController;
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
use App\Http\Controllers\TrackedLinkRedirectController;
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

Route::get('/r/{tracking_code}', TrackedLinkRedirectController::class)
    ->name('tracked-links.redirect');


Route::get('/admin/login', [LoginController::class, 'viewLogin'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login_user');
Route::get('/login', function () {
    return redirect('/admin/login');
});

/*
|--------------------------------------------------------------------------
| Organization-Prefixed Login Routes
|--------------------------------------------------------------------------
| e.g. /acme-corp/admin/login — branded login for org users.
*/
Route::middleware('resolve.org')->prefix('{orgSlug}')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'viewLogin'])->name('org.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('org.login_user');
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

// Step-by-step form validation endpoints
Route::post('/noel54/validate-step/{step}', [BrnController::class, 'validateStep'])->name('brn.validate-step');
Route::post('/noel54/validate-all', [BrnController::class, 'validateAllSteps'])->name('brn.validate-all');

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
// Route::get('/email-test', function () {
//     return view('emails.consultation-scheduled');
// })->name('feedback.thankyou');

// Route::get('/events/all-events', function () {
//     $events = Event::all();
//     return view('all-events', compact('events'));
// })->name('events.all-events');

// Contact form - increased throttle to 15 requests per minute for better UX with retries
Route::post('/contact', [ContactController::class, 'submitForm'])->middleware('throttle:15,1')->name('contact.submit');

// Advanced tracking endpoint
Route::post('/track-advanced', [\App\Http\Controllers\AdvancedTrackingController::class, 'track'])->name('track.advanced');

Route::get('/events/{slug}', [UserEventController::class, 'show'])->name('events.show');

// Route::get('/', function () {
//     return view('welcome');
// });

// Admin-only contact management routes
Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::delete('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contact.destroy');
    Route::post('/contact/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDestroy'])->name('contact.bulk-delete');
});

/*
|--------------------------------------------------------------------------
| Organization-Prefixed Admin Routes
|--------------------------------------------------------------------------
| e.g. /acme-corp/admin/dashboard — all admin panel routes with org slug prefix.
| These mirror the standard /admin routes but with an {orgSlug} prefix.
| The resolve.org middleware loads the Organization model from the slug.
*/
Route::middleware(['auth', 'check.suspended', 'resolve.org', 'role:admin|supervisor|staff'])
    ->prefix('{orgSlug}/admin')
    ->name('org.admin.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:admin|supervisor')->group(function () {
            Route::resource('staff', StaffController::class);
            Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
            Route::post('/organizations/ajax', [\App\Http\Controllers\Admin\OrganizationController::class, 'storeAjax'])->name('organizations.store-ajax');

            Route::get('/contact', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
            Route::get('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
            Route::get('/contact/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contact.export');
            Route::get('/contact/export-xlsx', [\App\Http\Controllers\Admin\ContactController::class, 'exportXlsx'])->name('contact.export.xlsx');

            Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('calendar.index');

            Route::post('/staff/{staff}/suspend', [StaffController::class, 'suspend'])->name('staff.suspend');
            Route::post('/staff/{staff}/reactivate', [StaffController::class, 'reactivate'])->name('staff.reactivate');
            Route::post('/staff/{staff}/promote', [StaffController::class, 'promote'])->name('staff.promote');

            Route::get('/brn', [\App\Http\Controllers\Admin\BrnController::class, 'adminView'])->name('brn.index');
            Route::get('/brn/{id}', [\App\Http\Controllers\Admin\BrnController::class, 'show'])->name('brn.show');
            Route::delete('/brn/{id}', [\App\Http\Controllers\Admin\BrnController::class, 'destroy'])->name('brn.destroy');
            Route::post('/brn/{id}/approve', [\App\Http\Controllers\Admin\BrnController::class, 'approve'])->name('brn.approve');
            Route::post('/brn/{id}/decline', [\App\Http\Controllers\Admin\BrnController::class, 'decline'])->name('brn.decline');

            Route::resource('/ultimatefunding', \App\Http\Controllers\Admin\UltimateFundingController::class);

            Route::get('/consultations', [\App\Http\Controllers\Admin\ConsultationController::class, 'index'])->name('consultation.index');
            Route::get('/consultations/{id}', [\App\Http\Controllers\Admin\ConsultationController::class, 'show'])->name('consultation.show');
            Route::delete('/consultation/{id}', [\App\Http\Controllers\Admin\ConsultationController::class, 'destroy'])->name('consultation.destroy');
            Route::post('/consultation/bulk-delete', [\App\Http\Controllers\Admin\ConsultationController::class, 'bulkDelete'])->name('consultation.bulk-delete');
            Route::post('/consultations/{id}/update-status', [\App\Http\Controllers\Admin\ConsultationController::class, 'updateStatus'])->name('consultation.updateStatus');
            Route::post('/consultations/{id}/reply', [\App\Http\Controllers\Admin\ConsultationController::class, 'sendReply'])->name('consultation.reply');
            Route::post('/consultations/{id}/manual-schedule', [\App\Http\Controllers\Admin\ConsultationController::class, 'manualSchedule'])->name('consultation.manual-schedule');
            Route::post('/consultation/chat/mark-read/{messageId}', [\App\Http\Controllers\Admin\ConsultationController::class, 'markMessageAsRead'])->name('consultation.chat.markRead');
        });

        // WhatsApp module (admin only)
        Route::middleware('role:admin')->prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WhatsAppController::class, 'overview'])->name('overview');
            Route::get('/overview', [\App\Http\Controllers\Admin\WhatsAppController::class, 'overview'])->name('overview.index');
            Route::get('/conversations', [\App\Http\Controllers\Admin\WhatsAppController::class, 'conversations'])->name('conversations');
            Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Admin\WhatsAppController::class, 'sendMessage'])->name('messages.send');
            Route::patch('/conversations/{conversation}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateConversation'])->name('conversations.update');
            Route::post('/conversations/{conversation}/notes', [\App\Http\Controllers\Admin\WhatsAppController::class, 'addNote'])->name('notes.store');
            Route::post('/conversations/{conversation}/mark-read', [\App\Http\Controllers\Admin\WhatsAppController::class, 'markRead'])->name('conversations.mark-read');
            Route::get('/templates', [\App\Http\Controllers\Admin\WhatsAppController::class, 'templates'])->name('templates');
            Route::post('/templates', [\App\Http\Controllers\Admin\WhatsAppController::class, 'storeTemplate'])->name('templates.store');
            Route::put('/templates/{template}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateTemplate'])->name('templates.update');
            Route::get('/contacts', [\App\Http\Controllers\Admin\WhatsAppController::class, 'contacts'])->name('contacts');
            Route::patch('/contacts/{contact}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateContact'])->name('contacts.update');
            Route::get('/settings', [\App\Http\Controllers\Admin\WhatsAppController::class, 'settings'])->name('settings');
            Route::post('/settings/account', [\App\Http\Controllers\Admin\WhatsAppController::class, 'saveAccount'])->name('settings.account.save');
            Route::get('/account-setup', [\App\Http\Controllers\Admin\WhatsAppController::class, 'accountSetup'])->name('account-setup');
            Route::get('/webhook-logs', [\App\Http\Controllers\Admin\WhatsAppController::class, 'webhookLogs'])->name('webhook-logs');
            Route::get('/poll', [\App\Http\Controllers\Admin\WhatsAppController::class, 'poll'])->name('poll');
        });

        // CRM
        Route::get('/crm', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('crm.index');
        Route::get('/crm/create', [\App\Http\Controllers\Admin\LeadController::class, 'create'])->name('crm.create');
        Route::post('/crm', [\App\Http\Controllers\Admin\LeadController::class, 'store'])->name('crm.store');
        Route::get('/crm/pipeline', [\App\Http\Controllers\Admin\LeadController::class, 'pipeline'])->name('crm.pipeline');
        Route::get('/crm/export', [\App\Http\Controllers\Admin\LeadController::class, 'export'])->name('crm.export');
        Route::get('/crm/import', [\App\Http\Controllers\Admin\LeadImportController::class, 'showImport'])->name('crm.import');
        Route::get('/crm/import/preview', [\App\Http\Controllers\Admin\LeadImportController::class, 'showPreview'])->name('crm.import.preview-page');
        Route::post('/crm/import/preview', [\App\Http\Controllers\Admin\LeadImportController::class, 'preview'])->name('crm.import.preview');
        Route::post('/crm/import', [\App\Http\Controllers\Admin\LeadImportController::class, 'import'])->name('crm.import.process');
        Route::get('/crm/import/review', [\App\Http\Controllers\Admin\LeadImportController::class, 'showReview'])->name('crm.import.review');
        Route::post('/crm/import/retry', [\App\Http\Controllers\Admin\LeadImportController::class, 'retry'])->name('crm.import.retry');
        Route::post('/crm/import/retry-single', [\App\Http\Controllers\Admin\LeadImportController::class, 'retrySingle'])->name('crm.import.retry-single');
        Route::post('/crm/import/skip-all', [\App\Http\Controllers\Admin\LeadImportController::class, 'skipAll'])->name('crm.import.skip-all');
        Route::get('/crm/import/template', [\App\Http\Controllers\Admin\LeadImportController::class, 'downloadTemplate'])->name('crm.import.template');

        Route::get('/crm/drives', [\App\Http\Controllers\Admin\LeadDriveController::class, 'index'])->name('crm.drives.index');
        Route::post('/crm/drives', [\App\Http\Controllers\Admin\LeadDriveController::class, 'store'])->name('crm.drives.store');
        Route::post('/crm/drives/ajax', [\App\Http\Controllers\Admin\LeadDriveController::class, 'storeAjax'])->name('crm.drives.store-ajax');
        Route::put('/crm/drives/{drive}', [\App\Http\Controllers\Admin\LeadDriveController::class, 'update'])->name('crm.drives.update');
        Route::delete('/crm/drives/{drive}', [\App\Http\Controllers\Admin\LeadDriveController::class, 'destroy'])->name('crm.drives.destroy');

        // CRM - Lead Statuses (admin & supervisor only)
        Route::middleware('role:admin|supervisor')->group(function () {
            Route::get('/crm/statuses', [\App\Http\Controllers\Admin\LeadStatusController::class, 'index'])->name('crm.statuses.index');
            Route::post('/crm/statuses', [\App\Http\Controllers\Admin\LeadStatusController::class, 'store'])->name('crm.statuses.store');
            Route::put('/crm/statuses/{leadStatus}', [\App\Http\Controllers\Admin\LeadStatusController::class, 'update'])->name('crm.statuses.update');
            Route::delete('/crm/statuses/{leadStatus}', [\App\Http\Controllers\Admin\LeadStatusController::class, 'destroy'])->name('crm.statuses.destroy');
            Route::post('/crm/statuses/reorder', [\App\Http\Controllers\Admin\LeadStatusController::class, 'reorder'])->name('crm.statuses.reorder');
            // CRM Settings (currency, etc.)
            Route::get('/crm/settings', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'index'])->name('crm.settings');
            Route::post('/crm/settings', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'update'])->name('crm.settings.update');
        });

        Route::middleware('role:admin|supervisor')->group(function () {
            Route::get('/crm/import-activity', [\App\Http\Controllers\Admin\LeadController::class, 'importActivity'])->name('crm.import-activity');
            Route::get('/crm/user-activity/{userId}', [\App\Http\Controllers\Admin\LeadController::class, 'userActivity'])->name('crm.user-activity');
        });

        Route::get('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'show'])->name('crm.show');
        Route::get('/crm/{lead}/edit', [\App\Http\Controllers\Admin\LeadController::class, 'edit'])->name('crm.edit');
        Route::put('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'update'])->name('crm.update');
        Route::delete('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'destroy'])->name('crm.destroy');
        Route::patch('/crm/{lead}/status', [\App\Http\Controllers\Admin\LeadController::class, 'updateStatus'])->name('crm.update-status');
        Route::post('/crm/{lead}/assign', [\App\Http\Controllers\Admin\LeadController::class, 'assign'])->name('crm.assign');
        Route::post('/crm/{lead}/activity', [\App\Http\Controllers\Admin\LeadController::class, 'addActivity'])->name('crm.add-activity');
        Route::patch('/crm/activity/{activity}', [\App\Http\Controllers\Admin\LeadController::class, 'updateActivity'])->name('crm.update-activity');
        Route::post('/crm/{lead}/follow-up', [\App\Http\Controllers\Admin\LeadController::class, 'setFollowUp'])->name('crm.set-follow-up');
        Route::post('/crm/activity/{activity}/complete', [\App\Http\Controllers\Admin\LeadController::class, 'completeActivity'])->name('crm.complete-activity');
        Route::post('/crm/bulk-update', [\App\Http\Controllers\Admin\LeadController::class, 'bulkUpdate'])->name('crm.bulk-update');
        Route::post('/crm/convert/contact/{contact}', [\App\Http\Controllers\Admin\LeadController::class, 'convertFromContact'])->name('crm.convert-contact');
        Route::post('/crm/convert/consultation/{consultation}', [\App\Http\Controllers\Admin\LeadController::class, 'convertFromConsultation'])->name('crm.convert-consultation');

        Route::get('/crm/{lead}/emails', [\App\Http\Controllers\Admin\LeadController::class, 'getEmails'])->name('crm.get-emails');
        Route::post('/crm/{lead}/email', [\App\Http\Controllers\Admin\LeadController::class, 'sendEmail'])->name('crm.send-email');
        Route::get('/crm/{lead}/emails/thread/{threadId}', [\App\Http\Controllers\Admin\LeadController::class, 'getEmailThread'])->name('crm.get-email-thread');
        Route::get('/crm/{lead}/emails/unread-count', [\App\Http\Controllers\Admin\LeadController::class, 'getUnreadEmailCount'])->name('crm.unread-email-count');

        Route::post('/events/{id}/send-reminder', [EventController::class, 'sendReminder'])->name('events.sendReminder');
        Route::post('/events/{event}/send-feedback', [EventController::class, 'sendFeedback'])->name('events.sendFeedback');
        Route::get('/events/{event}/feedbacks', [EventController::class, 'viewFeedbacks'])->name('events.feedbacks');
        Route::get('/seats', [\App\Http\Controllers\Admin\SeatController::class, 'index'])->name('admin.seats.index');
        Route::resource('/events', EventController::class);
        Route::get('/events/{event}/bookings', [EventController::class, 'showBookings'])->name('events.bookings');
        Route::get('/events/booking-fields', [EventController::class, 'getBookingFields'])->name('events.booking-fields');
        Route::get('/events/{event}/clicks', [EventController::class, 'viewClicks'])->name('events.clicks');

        Route::resource('blogs', BlogController::class);
        Route::resource('/blogs/categories', CategoryController::class);
        Route::resource('/playbooks', PlaybookController::class);
        Route::get('/playbooks-dashboard', [PlaybookController::class, 'dashboard'])->name('playbooks.dashboard');
        Route::resource('/salesbooks', \App\Http\Controllers\Admin\SalesBookController::class);
        Route::resource('students', StudentController::class);

        Route::get('/clicks/report', [ClickReportController::class, 'index'])->name('clicks.report');
        Route::get('/clicks/export', [ClickReportController::class, 'export'])->name('clicks.export');
        Route::post('/clicks/backfill-countries', [ClickReportController::class, 'backfillCountries'])->name('clicks.backfill-countries');

        Route::middleware('legacy.analytics')->group(function () {
            Route::get('/link-tracking', [TrackedLinkAnalyticsController::class, 'index'])->name('link-tracking.index');
            Route::get('/link-tracking/export', [TrackedLinkAnalyticsController::class, 'export'])->name('link-tracking.export');
        });

        Route::post('/playbooks/resend-funding', [\App\Http\Controllers\PlaybookController::class, 'resendFundingReadinessPending'])->name('playbooks.resend-funding');

        // Projects
        Route::get('/projects/gantt', [\App\Http\Controllers\Admin\ProjectController::class, 'gantt'])->name('projects.gantt');
        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);
        Route::patch('/projects/{project}/status', [\App\Http\Controllers\Admin\ProjectController::class, 'updateStatus'])->name('projects.update-status');

        // Tasks
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

        Route::get('/tasks/{task}/comments', [\App\Http\Controllers\Admin\TaskController::class, 'getComments'])->name('tasks.comments');
        Route::post('/tasks/{task}/comments', [\App\Http\Controllers\Admin\TaskController::class, 'addComment'])->name('tasks.add-comment');
        Route::delete('/tasks/{task}/comments/{comment}', [\App\Http\Controllers\Admin\TaskController::class, 'deleteComment'])->name('tasks.delete-comment');
        Route::get('/tasks/{task}/mentionable-users', [\App\Http\Controllers\Admin\TaskController::class, 'getAssigneesForMention'])->name('tasks.mentionable-users');

        // Attendance & Leave Management
        Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/sign-in', [\App\Http\Controllers\Admin\AttendanceController::class, 'signIn'])->name('attendance.sign-in');
        Route::post('/attendance/sign-out', [\App\Http\Controllers\Admin\AttendanceController::class, 'signOut'])->name('attendance.sign-out');
        Route::get('/attendance/today-status', [\App\Http\Controllers\Admin\AttendanceController::class, 'todayStatus'])->name('attendance.today-status');
        Route::get('/attendance/calendar-data', [\App\Http\Controllers\Admin\AttendanceController::class, 'calendarData'])->name('attendance.calendar-data');

        Route::get('/leave-requests', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('/leave-requests/create', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('/leave-requests', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('/leave-requests/calendar', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'calendar'])->name('leave-requests.calendar');
        Route::get('/leave-requests/calendar-data', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'calendarData'])->name('leave-requests.calendar-data');
        Route::get('/leave-requests/{leaveRequest}', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('/leave-requests/{leaveRequest}/modify', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'modify'])->name('leave-requests.modify');
        Route::post('/leave-requests/{leaveRequest}/respond', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'senderRespond'])->name('leave-requests.respond');
        Route::post('/leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

        // Spreadsheets
        Route::get('/spreadsheets', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'index'])->name('spreadsheets.index');
        Route::get('/spreadsheets/create', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'create'])->name('spreadsheets.create');
        Route::post('/spreadsheets', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'store'])->name('spreadsheets.store');
        Route::get('/spreadsheets/passcode-info', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'passcodeInfo'])->name('spreadsheets.passcode-info');
        Route::post('/spreadsheets/generate-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'generatePasscode'])->name('spreadsheets.generate-passcode');
        Route::get('/spreadsheets/{spreadsheet}', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'show'])->name('spreadsheets.show');
        Route::get('/spreadsheets/{spreadsheet}/data', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'getData'])->name('spreadsheets.data');
        Route::post('/spreadsheets/{spreadsheet}/save', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'save'])->name('spreadsheets.save');
        Route::get('/spreadsheets/{spreadsheet}/export', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'export'])->name('spreadsheets.export');
        Route::delete('/spreadsheets/{spreadsheet}', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'destroy'])->name('spreadsheets.destroy');
        Route::post('/spreadsheets/{spreadsheet}/verify-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'verifyPasscode'])->name('spreadsheets.verify-passcode');
        Route::post('/spreadsheets/{spreadsheet}/request-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'requestPasscode'])->name('spreadsheets.request-passcode');
    });

// Org-prefixed notification routes
Route::middleware(['auth', 'check.suspended', 'resolve.org'])->prefix('{orgSlug}/admin/notifications')->name('org.admin.notifications.')->group(function () {
    Route::get('/all', [\App\Http\Controllers\Admin\NotificationController::class, 'getAllNotifications'])->name('all');
    Route::get('/all-unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getAllUnreadCount'])->name('all-unread-count');
    Route::get('/latest-unread', [\App\Http\Controllers\Admin\NotificationController::class, 'getLatestUnread'])->name('latest-unread');
    Route::get('/tasks', [\App\Http\Controllers\Admin\NotificationController::class, 'getTaskNotifications'])->name('tasks');
    Route::get('/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::post('/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
});

Route::middleware(['auth', 'check.suspended', 'resolve.org', 'role:admin|supervisor|staff'])->prefix('{orgSlug}/admin')->name('org.admin.')->group(function () {});

Route::middleware(['auth', 'check.suspended', 'role:admin|supervisor|staff'])->prefix('admin')->name('admin.')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin|supervisor')->group(function () {
        Route::resource('staff', StaffController::class);
        // Organization management (admins can create/select orgs when creating staff)
        Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
        Route::post('/organizations/ajax', [\App\Http\Controllers\Admin\OrganizationController::class, 'storeAjax'])->name('organizations.store-ajax');

        // Contact management routes (accessible to admin and supervisor)
        Route::get('/contact', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
        Route::get('/contact/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
        Route::get('/contact/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contact.export');
        Route::get('/contact/export-xlsx', [\App\Http\Controllers\Admin\ContactController::class, 'exportXlsx'])->name('contact.export.xlsx');


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
        Route::post('/consultations/{id}/manual-schedule', [\App\Http\Controllers\Admin\ConsultationController::class, 'manualSchedule'])->name('consultation.manual-schedule');
        Route::post('/consultation/chat/mark-read/{messageId}', [\App\Http\Controllers\Admin\ConsultationController::class, 'markMessageAsRead'])->name('consultation.chat.markRead');
    });

    // WhatsApp module (admin only)
    Route::middleware('role:admin')->prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\WhatsAppController::class, 'overview'])->name('overview');
        Route::get('/overview', [\App\Http\Controllers\Admin\WhatsAppController::class, 'overview'])->name('overview.index');
        Route::get('/conversations', [\App\Http\Controllers\Admin\WhatsAppController::class, 'conversations'])->name('conversations');
        Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Admin\WhatsAppController::class, 'sendMessage'])->name('messages.send');
        Route::patch('/conversations/{conversation}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateConversation'])->name('conversations.update');
        Route::post('/conversations/{conversation}/notes', [\App\Http\Controllers\Admin\WhatsAppController::class, 'addNote'])->name('notes.store');
        Route::post('/conversations/{conversation}/mark-read', [\App\Http\Controllers\Admin\WhatsAppController::class, 'markRead'])->name('conversations.mark-read');
        Route::get('/templates', [\App\Http\Controllers\Admin\WhatsAppController::class, 'templates'])->name('templates');
        Route::post('/templates', [\App\Http\Controllers\Admin\WhatsAppController::class, 'storeTemplate'])->name('templates.store');
        Route::put('/templates/{template}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateTemplate'])->name('templates.update');
        Route::get('/contacts', [\App\Http\Controllers\Admin\WhatsAppController::class, 'contacts'])->name('contacts');
        Route::patch('/contacts/{contact}', [\App\Http\Controllers\Admin\WhatsAppController::class, 'updateContact'])->name('contacts.update');
        Route::get('/settings', [\App\Http\Controllers\Admin\WhatsAppController::class, 'settings'])->name('settings');
        Route::post('/settings/account', [\App\Http\Controllers\Admin\WhatsAppController::class, 'saveAccount'])->name('settings.account.save');
        Route::get('/account-setup', [\App\Http\Controllers\Admin\WhatsAppController::class, 'accountSetup'])->name('account-setup');
        Route::get('/webhook-logs', [\App\Http\Controllers\Admin\WhatsAppController::class, 'webhookLogs'])->name('webhook-logs');
        Route::get('/poll', [\App\Http\Controllers\Admin\WhatsAppController::class, 'poll'])->name('poll');
    });

    // CRM - Lead Management
    Route::get('/crm', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('crm.index');
    Route::get('/crm/create', [\App\Http\Controllers\Admin\LeadController::class, 'create'])->name('crm.create');
    Route::post('/crm', [\App\Http\Controllers\Admin\LeadController::class, 'store'])->name('crm.store');
    Route::get('/crm/pipeline', [\App\Http\Controllers\Admin\LeadController::class, 'pipeline'])->name('crm.pipeline');
    Route::get('/crm/export', [\App\Http\Controllers\Admin\LeadController::class, 'export'])->name('crm.export');
    Route::get('/crm/import', [\App\Http\Controllers\Admin\LeadImportController::class, 'showImport'])->name('crm.import');
    Route::get('/crm/import/preview', [\App\Http\Controllers\Admin\LeadImportController::class, 'showPreview'])->name('crm.import.preview-page');
    Route::post('/crm/import/preview', [\App\Http\Controllers\Admin\LeadImportController::class, 'preview'])->name('crm.import.preview');
    Route::post('/crm/import', [\App\Http\Controllers\Admin\LeadImportController::class, 'import'])->name('crm.import.process');
    Route::get('/crm/import/review', [\App\Http\Controllers\Admin\LeadImportController::class, 'showReview'])->name('crm.import.review');
    Route::post('/crm/import/retry', [\App\Http\Controllers\Admin\LeadImportController::class, 'retry'])->name('crm.import.retry');
    Route::post('/crm/import/retry-single', [\App\Http\Controllers\Admin\LeadImportController::class, 'retrySingle'])->name('crm.import.retry-single');
    Route::post('/crm/import/skip-all', [\App\Http\Controllers\Admin\LeadImportController::class, 'skipAll'])->name('crm.import.skip-all');
    Route::get('/crm/import/template', [\App\Http\Controllers\Admin\LeadImportController::class, 'downloadTemplate'])->name('crm.import.template');

    // CRM - Lead Drives
    Route::get('/crm/drives', [\App\Http\Controllers\Admin\LeadDriveController::class, 'index'])->name('crm.drives.index');
    Route::post('/crm/drives', [\App\Http\Controllers\Admin\LeadDriveController::class, 'store'])->name('crm.drives.store');
    Route::post('/crm/drives/ajax', [\App\Http\Controllers\Admin\LeadDriveController::class, 'storeAjax'])->name('crm.drives.store-ajax');
    Route::put('/crm/drives/{drive}', [\App\Http\Controllers\Admin\LeadDriveController::class, 'update'])->name('crm.drives.update');
    Route::delete('/crm/drives/{drive}', [\App\Http\Controllers\Admin\LeadDriveController::class, 'destroy'])->name('crm.drives.destroy');

    // CRM - Lead Statuses (admin & supervisor only)
    Route::middleware('role:admin|supervisor')->group(function () {
        Route::get('/crm/statuses', [\App\Http\Controllers\Admin\LeadStatusController::class, 'index'])->name('crm.statuses.index');
        Route::post('/crm/statuses', [\App\Http\Controllers\Admin\LeadStatusController::class, 'store'])->name('crm.statuses.store');
        Route::put('/crm/statuses/{leadStatus}', [\App\Http\Controllers\Admin\LeadStatusController::class, 'update'])->name('crm.statuses.update');
        Route::delete('/crm/statuses/{leadStatus}', [\App\Http\Controllers\Admin\LeadStatusController::class, 'destroy'])->name('crm.statuses.destroy');
        Route::post('/crm/statuses/reorder', [\App\Http\Controllers\Admin\LeadStatusController::class, 'reorder'])->name('crm.statuses.reorder');
        // CRM Settings (currency, etc.)
        Route::get('/crm/settings', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'index'])->name('crm.settings');
        Route::post('/crm/settings', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'update'])->name('crm.settings.update');
    });

    Route::middleware('role:admin|supervisor')->group(function () {
        Route::get('/crm/import-activity', [\App\Http\Controllers\Admin\LeadController::class, 'importActivity'])->name('crm.import-activity');
        Route::get('/crm/user-activity/{userId}', [\App\Http\Controllers\Admin\LeadController::class, 'userActivity'])->name('crm.user-activity');
    });

    Route::get('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'show'])->name('crm.show');
    Route::get('/crm/{lead}/edit', [\App\Http\Controllers\Admin\LeadController::class, 'edit'])->name('crm.edit');
    Route::put('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'update'])->name('crm.update');
    Route::delete('/crm/{lead}', [\App\Http\Controllers\Admin\LeadController::class, 'destroy'])->name('crm.destroy');
    Route::patch('/crm/{lead}/status', [\App\Http\Controllers\Admin\LeadController::class, 'updateStatus'])->name('crm.update-status');
    Route::post('/crm/{lead}/assign', [\App\Http\Controllers\Admin\LeadController::class, 'assign'])->name('crm.assign');
    Route::post('/crm/{lead}/activity', [\App\Http\Controllers\Admin\LeadController::class, 'addActivity'])->name('crm.add-activity');
    Route::patch('/crm/activity/{activity}', [\App\Http\Controllers\Admin\LeadController::class, 'updateActivity'])->name('crm.update-activity');
    Route::post('/crm/{lead}/follow-up', [\App\Http\Controllers\Admin\LeadController::class, 'setFollowUp'])->name('crm.set-follow-up');
    Route::post('/crm/activity/{activity}/complete', [\App\Http\Controllers\Admin\LeadController::class, 'completeActivity'])->name('crm.complete-activity');
    Route::post('/crm/bulk-update', [\App\Http\Controllers\Admin\LeadController::class, 'bulkUpdate'])->name('crm.bulk-update');
    Route::post('/crm/convert/contact/{contact}', [\App\Http\Controllers\Admin\LeadController::class, 'convertFromContact'])->name('crm.convert-contact');
    Route::post('/crm/convert/consultation/{consultation}', [\App\Http\Controllers\Admin\LeadController::class, 'convertFromConsultation'])->name('crm.convert-consultation');

    // CRM Email functionality
    Route::get('/crm/{lead}/emails', [\App\Http\Controllers\Admin\LeadController::class, 'getEmails'])->name('crm.get-emails');
    Route::post('/crm/{lead}/email', [\App\Http\Controllers\Admin\LeadController::class, 'sendEmail'])->name('crm.send-email');
    Route::get('/crm/{lead}/emails/thread/{threadId}', [\App\Http\Controllers\Admin\LeadController::class, 'getEmailThread'])->name('crm.get-email-thread');
    Route::get('/crm/{lead}/emails/unread-count', [\App\Http\Controllers\Admin\LeadController::class, 'getUnreadEmailCount'])->name('crm.unread-email-count');

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


    // Route::middleware('permission:view_students')->group(function () {
    Route::resource('students', StudentController::class);
    // });

    Route::get('/clicks/report', [ClickReportController::class, 'index'])->name('clicks.report');
    Route::get('/clicks/export', [ClickReportController::class, 'export'])->name('clicks.export');
    Route::post('/clicks/backfill-countries', [ClickReportController::class, 'backfillCountries'])->name('clicks.backfill-countries');

    Route::middleware('legacy.analytics')->group(function () {
        Route::get('/link-tracking', [TrackedLinkAnalyticsController::class, 'index'])->name('link-tracking.index');
        Route::get('/link-tracking/export', [TrackedLinkAnalyticsController::class, 'export'])->name('link-tracking.export');
    });

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
    Route::get('/tasks/{task}/mentionable-users', [\App\Http\Controllers\Admin\TaskController::class, 'getAssigneesForMention'])->name('tasks.mentionable-users');

    // Attendance & Leave Management
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/sign-in', [\App\Http\Controllers\Admin\AttendanceController::class, 'signIn'])->name('attendance.sign-in');
    Route::post('/attendance/sign-out', [\App\Http\Controllers\Admin\AttendanceController::class, 'signOut'])->name('attendance.sign-out');
    Route::get('/attendance/today-status', [\App\Http\Controllers\Admin\AttendanceController::class, 'todayStatus'])->name('attendance.today-status');
    Route::get('/attendance/calendar-data', [\App\Http\Controllers\Admin\AttendanceController::class, 'calendarData'])->name('attendance.calendar-data');

    Route::get('/leave-requests', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/calendar', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'calendar'])->name('leave-requests.calendar');
    Route::get('/leave-requests/calendar-data', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'calendarData'])->name('leave-requests.calendar-data');
    Route::get('/leave-requests/{leaveRequest}', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('/leave-requests/{leaveRequest}/modify', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'modify'])->name('leave-requests.modify');
    Route::post('/leave-requests/{leaveRequest}/respond', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'senderRespond'])->name('leave-requests.respond');
    Route::post('/leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

    // Spreadsheets
    Route::get('/spreadsheets', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'index'])->name('spreadsheets.index');
    Route::get('/spreadsheets/create', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'create'])->name('spreadsheets.create');
    Route::post('/spreadsheets', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'store'])->name('spreadsheets.store');
    Route::get('/spreadsheets/passcode-info', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'passcodeInfo'])->name('spreadsheets.passcode-info');
    Route::post('/spreadsheets/generate-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'generatePasscode'])->name('spreadsheets.generate-passcode');
    Route::get('/spreadsheets/{spreadsheet}', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'show'])->name('spreadsheets.show');
    Route::get('/spreadsheets/{spreadsheet}/data', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'getData'])->name('spreadsheets.data');
    Route::post('/spreadsheets/{spreadsheet}/save', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'save'])->name('spreadsheets.save');
    Route::get('/spreadsheets/{spreadsheet}/export', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'export'])->name('spreadsheets.export');
    Route::delete('/spreadsheets/{spreadsheet}', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'destroy'])->name('spreadsheets.destroy');
    Route::post('/spreadsheets/{spreadsheet}/verify-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'verifyPasscode'])->name('spreadsheets.verify-passcode');
    Route::post('/spreadsheets/{spreadsheet}/request-passcode', [\App\Http\Controllers\Admin\SpreadsheetController::class, 'requestPasscode'])->name('spreadsheets.request-passcode');
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
