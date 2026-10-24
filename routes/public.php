<?php

use App\Helpers\ClickTracker;
use App\Http\Controllers\BrnController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResourceHubController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\submitStudent;
use Illuminate\Support\Facades\Route;

return function (bool $localized): void {
    $name = fn (string $routeName): string => $localized ? 'locale.'.$routeName : $routeName;

    Route::get('/', [SliderController::class, 'showSlider'])->name($name('homepage'));

    Route::get('/case-studies', [\App\Http\Controllers\CaseStudyController::class, 'show'])->name($name('case-studies'));
    Route::get('/case-studies/{slug}', [\App\Http\Controllers\CaseStudyController::class, 'show'])->name($name('case-studies.show'));

    Route::get('/contact', function () {
        ClickTracker::track('Contact_Page');

        return view('contact');
    })->name($name('contact'));

    Route::get('/about', function () {
        ClickTracker::track('About_Page');

        return view('about', ['caseStudies' => \App\Support\CaseStudies::all()]);
    })->name($name('about'));

    Route::get('/services', function () {
        ClickTracker::track('Service_Page');

        return view('services');
    })->name($name('services'));

    Route::get('/market-entry', function () {
        ClickTracker::track('Market_Entry_Page');

        return view('market-entry.index');
    })->name($name('services.market-entry'));

    Route::get('/market-entry/nigeria', function () {
        ClickTracker::track('Market_Entry_Nigeria_Page');

        return view('market-entry.nigeria');
    })->name($name('services.market-entry.nigeria'));

    Route::get('/market-entry/ghana', function () {
        ClickTracker::track('Market_Entry_Ghana_Page');

        return view('market-entry.ghana');
    })->name($name('services.market-entry.ghana'));

    Route::get('/distributor-partner-development', function () {
        ClickTracker::track('Distributor_Partner_Page');

        return view('services.distributor-partner-development');
    })->name($name('services.distributor-partner'));

    Route::get('/outbound-sales-demand-testing', function () {
        ClickTracker::track('Outbound_Sales_Page');

        return view('services.outbound-sales-demand-testing');
    })->name($name('services.outbound-sales'));

    Route::get('/services/market-entry', fn () => redirect()->to(locale_route('services.market-entry')));
    Route::get('/services/market-entry/nigeria', fn () => redirect()->to(locale_route('services.market-entry.nigeria')));
    Route::get('/services/market-entry/ghana', fn () => redirect()->to(locale_route('services.market-entry.ghana')));

    Route::get('/sales', function () {
        ClickTracker::track('Sales_Page');

        return response()
            ->view('sales')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    })->name($name('sales'));

    Route::get('/quiz', function () {
        ClickTracker::track('Quiz_Page');

        return view('sales-quiz');
    })->name($name('quiz'));

    Route::get('/device-display', function () {
        return view('device');
    })->name($name('device'));

    Route::get('/playbook/ultimate-funding/payment', function () {
        return view('playbook.payment');
    })->name($name('playbook.payment'));

    Route::get('/playbook/ultimate-funding/checkout', function () {
        return view('playbook.checkout');
    })->name($name('playbook.checkout'));

    Route::get('/noel54/interest-form', function () {
        ClickTracker::track('NOEL_Form_Page');

        return view('brn-form');
    })->name($name('brn-form'));

    Route::post('/noel54/interest-form', [BrnController::class, 'store'])->name($name('brn.submit'));
    Route::post('/noel54/check-email', [BrnController::class, 'checkEmail'])->name($name('brn.check-email'));
    Route::post('/noel54/validate-step/{step}', [BrnController::class, 'validateStep'])->name($name('brn.validate-step'));
    Route::post('/noel54/validate-all', [BrnController::class, 'validateAllSteps'])->name($name('brn.validate-all'));

    Route::post('/playbook/download', [\App\Http\Controllers\PlaybookController::class, 'download'])->name($name('playbook.download'));
    Route::post('/sales-book/download', [\App\Http\Controllers\PlaybookController::class, 'salesBookDownload'])->name($name('sales-book.download'));
    Route::post('/ultimate-funding/download', [\App\Http\Controllers\PlaybookController::class, 'ultimateFundingDownload'])->name($name('ultimate-funding.download'));

    Route::get('/founders-playbook', [\App\Http\Controllers\PlaybookController::class, 'landing'])->name($name('playbook.landing'));
    Route::get('/playbook/funding-readiness', [\App\Http\Controllers\PlaybookController::class, 'playbookOne'])->name($name('playbook.landing1'));
    Route::get('/playbook/ultimate-funding', [\App\Http\Controllers\PlaybookController::class, 'playbookTwo'])->name($name('playbook.landing2'));

    Route::get('/brn-form', function () {
        return redirect()->to(locale_route('brn-form'));
    });

    Route::get('/noel54', [BrnController::class, 'brnIndex'])->name($name('brn'));

    Route::get('/student', [SliderController::class, 'showSliderStudent'])->name($name('student'));
    Route::post('/submit-student', [submitStudent::class, 'submitStudent']);

    Route::get('/resource-hub', [ResourceHubController::class, 'resourceHub'])->name($name('resource-hub'));
    Route::get('/resource/{slug}', [ResourceHubController::class, 'resourceDetails'])->name($name('resource-show'));
    Route::get('/res/category/{slug}', [ResourceHubController::class, 'categoryView'])->name($name('archive.category'));
    Route::get('/res/archive', [ResourceHubController::class, 'archivePage'])->name($name('archive-page'));
    Route::get('/res/archive/list', [ResourceHubController::class, 'archiveList'])->name($name('archive-list'));
    Route::get('/res/archive/search', [ResourceHubController::class, 'searchArchive'])->name($name('archive.search'));

    Route::get('/quiz/result/fragile-funnel', fn () => view('quiz-answers.result-a'))->name($name('fragile-funnel'));
    Route::get('/quiz/result/hidden-powerhouse', fn () => view('quiz-answers.result-b'))->name($name('hidden-powerhouse'));
    Route::get('/quiz/result/cloudy-climber', fn () => view('quiz-answers.result-c'))->name($name('cloudy-climber'));

    Route::get('/quiz/start', function () {
        ClickTracker::track('Quiz_Start_Page');

        return view('start-quiz');
    })->name($name('start-quiz'));

    Route::post('/submit-challenge', [QuizController::class, 'submitChallenge']);

    Route::post('/book-seat/{event_id}', [SeatController::class, 'store'])->name($name('seats.store'));

    Route::get('/events', [UserEventController::class, 'eventPage'])->name($name('event'));
    Route::get('/all-events', [UserEventController::class, 'allEvents'])->name($name('events.all-events'));
    Route::get('/events/search', [UserEventController::class, 'search'])->name($name('events.search'));
    Route::post('/event/join', [UserEventController::class, 'join'])->name($name('event.join'));
    Route::get('/event/live/{slug}/{code}', [UserEventController::class, 'liveEvent'])->name($name('private.access'));
    Route::get('/download-ics/{booking}', [\App\Http\Controllers\CalendarController::class, 'downloadIcs'])->name($name('download.ics'));

    Route::get('/feedback', [FeedbackController::class, 'showForm'])->name($name('feedback.form'));
    Route::post('/feedback', [FeedbackController::class, 'submit'])->name($name('feedback.submit'));
    Route::get('/feedback/thank-you', function () {
        return view('feedback.thankyou');
    })->name($name('feedback.thankyou'));

    Route::get('/book-consultation', function () {
        return redirect()->to(locale_route('contact'));
    })->name($name('consultation.index'));

    Route::get('/book', function () {
        return view('consultation.schedule');
    })->name($name('consultation.schedule'));

    Route::get('/book-consultation/form', [\App\Http\Controllers\ConsultationController::class, 'showApplicationForm'])->name($name('consultation.application-forms'));
    Route::post('/book-consultation/submit', [\App\Http\Controllers\ConsultationController::class, 'submitApplication'])->name($name('consultation.submit'));
    Route::get('/book-consultation/summary', [\App\Http\Controllers\ConsultationController::class, 'showSummary'])->name($name('consultation.consultation-summary'));
    Route::get('/book-consultation/payment-selection', [\App\Http\Controllers\ConsultationController::class, 'showPaymentSelection'])->name($name('consultation.payment-selection'));
    Route::get('/book-consultation/payment-success', [\App\Http\Controllers\ConsultationController::class, 'showPaymentSuccess'])->name($name('consultation.payment-success'));
    Route::get('/book-consultation/schedule', [\App\Http\Controllers\ConsultationController::class, 'showScheduling'])->name($name('consultation.schedule-consultation'));
    Route::post('/book-consultation/schedule', [\App\Http\Controllers\ConsultationController::class, 'scheduleSession'])->name($name('consultation.schedule.submit'));
    Route::get('/book-consultation/download-ics/{filename}', [\App\Http\Controllers\ConsultationController::class, 'downloadIcs'])->name($name('consultation.download-ics'));

    Route::post('/contact', [ContactController::class, 'submitForm'])->middleware('throttle:15,1')->name($name('contact.submit'));

    Route::get('/events/{slug}', [UserEventController::class, 'show'])->name($name('events.show'));
};
