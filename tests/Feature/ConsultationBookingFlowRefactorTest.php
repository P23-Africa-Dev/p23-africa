<?php

namespace Tests\Feature;

use App\Http\Controllers\ConsultationController;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Mockery;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;
 
class ConsultationBookingFlowRefactorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
    }

    public function test_submit_application_generates_booking_token_and_redirects_with_booking_query(): void
    {
        $controller = app(ConsultationController::class);

        $request = Request::create('/book-consultation/submit', 'POST', [
            'full_name' => 'Jane Founder',
            'email' => 'jane@example.com',
            'phone' => '+2348012345678',
            'company_brand_name' => 'Acme Labs',
            'website_or_handle' => 'https://example.com',
            'industry' => 'Fintech',
            'business_description' => 'We provide payments infrastructure for SMEs across Africa.',
            'business_goals' => ['Scale operations'],
            'key_challenges' => ['Fundraising'],
            'motivation' => 'We need strategic advice before our next growth phase.',
            'success_definition' => 'A clear roadmap with practical next steps.',
        ]);
        $this->attachSession($request);

        $response = $controller->submitApplication($request);

        $applicationId = Session::get('consultation_application_id');
        $application = $applicationId ? ConsultationApplication::find($applicationId) : null;

        $this->assertNotNull($application);
        $this->assertNotNull($application->booking_token);
        $this->assertSame($application->booking_token, Session::get('consultation_booking_token'));
        $this->assertSame($application->id, $applicationId);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString(
            'booking=' . $application->booking_token,
            $response->getTargetUrl()
        );
    }

    public function test_show_summary_can_resolve_booking_by_token_without_existing_session_context(): void
    {
        $application = $this->makeConsultationApplication();
        Session::flush();

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/summary', 'GET', [
            'booking' => $application->booking_token,
        ]);
        $this->attachSession($request);

        $response = $controller->showSummary($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertSame($application->id, $response->getData()['application']->id);
        $this->assertSame($application->booking_token, Session::get('consultation_booking_token'));
    }

    public function test_payment_callback_resolves_by_payment_reference_without_session(): void
    {
        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        $payment = Payment::create([
            'consultation_application_id' => $application->id,
            'payment_method' => 'stripe',
            'payment_reference' => 'pi_ref_test_123',
            'amount' => 75.00,
            'currency' => 'GBP',
            'status' => 'pending',
        ]);

        Session::flush();

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/callback', 'POST', [
            'gateway' => 'stripe',
            'transaction_id' => 'pi_ref_test_123',
            'status' => 'successful',
            'payment_data' => json_encode(['event' => 'payment_intent.succeeded']),
        ]);
        $this->attachSession($request);

        $response = $controller->handlePaymentCallback($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString(
            'booking=' . $application->booking_token,
            $response->getTargetUrl()
        );

        $this->assertSame('successful', $payment->fresh()->status);
        $this->assertSame('paid', $application->fresh()->status);
        $this->assertTrue(Session::has('payment_success'));
    }

    public function test_payment_callback_rejects_non_stripe_gateway(): void
    {
        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/callback', 'POST', [
            'gateway' => 'flutterwave',
            'transaction_id' => 'FLW_ref_123',
            'status' => 'successful',
            'payment_data' => json_encode(['status' => 'successful']),
        ]);
        $this->attachSession($request);

        $response = $controller->handlePaymentCallback($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/book-consultation/payment-selection', $response->getTargetUrl());
    }

    public function test_fallback_callback_creation_uses_75_gbp_defaults(): void
    {
        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        Session::flush();
        Session::put('consultation_booking_token', $application->booking_token);

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/callback', 'POST', [
            'gateway' => 'stripe',
            'transaction_id' => 'pi_new_ref_987',
            'status' => 'pending',
            'payment_data' => json_encode(['event' => 'payment_intent.processing']),
        ]);
        $this->attachSession($request);

        $response = $controller->handlePaymentCallback($request);

        $payment = Payment::where('payment_reference', 'pi_new_ref_987')->first();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('payment-success', $response->getTargetUrl());
        $this->assertNotNull($payment);
        $this->assertSame(75.00, (float) $payment->amount);
        $this->assertSame('GBP', $payment->currency);
        $this->assertSame('pending', $payment->fresh()->status);
        $this->assertSame('paid', $application->fresh()->status);
        $this->assertTrue(Session::has('payment_success'));
        $this->assertTrue(Session::has('is_pending_verification'));
    }

    public function test_create_stripe_payment_intent_uses_consultation_fixed_amount_not_env_amount(): void
    {
        config()->set('services.stripe.amount', 1);
        config()->set('services.stripe.currency', 'NGN');
        config()->set('services.stripe.secret_key', 'sk_test_mock');

        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        Mockery::mock('alias:Stripe\\Stripe')
            ->shouldReceive('setApiKey')
            ->once()
            ->with('sk_test_mock');

        $intent = new \stdClass();
        $intent->id = 'pi_test_consultation_123';
        $intent->client_secret = 'pi_test_consultation_123_secret_abc';

        Mockery::mock('alias:Stripe\\PaymentIntent')
            ->shouldReceive('create')
            ->once()
            ->withArgs(function (array $payload) use ($application) {
                return $payload['amount'] === 7500
                    && $payload['currency'] === 'gbp'
                    && ($payload['metadata']['application_id'] ?? null) === $application->id;
            })
            ->andReturn($intent);

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/create-intent', 'POST', [
            'booking' => $application->booking_token,
            'payment_method' => 'stripe',
        ]);
        $this->attachSession($request);

        $response = $controller->createStripePaymentIntent($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('pi_test_consultation_123', $response->getData(true)['payment_intent_id']);

        $payment = Payment::where('payment_reference', 'pi_test_consultation_123')->first();
        $this->assertNotNull($payment);
        $this->assertSame(75.00, (float) $payment->amount);
        $this->assertSame('GBP', $payment->currency);
    }

    public function test_create_stripe_payment_intent_returns_safe_error_message_when_stripe_fails(): void
    {
        config()->set('services.stripe.secret_key', 'sk_test_mock');

        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        Mockery::mock('alias:Stripe\\Stripe')
            ->shouldReceive('setApiKey')
            ->once()
            ->with('sk_test_mock');

        Mockery::mock('alias:Stripe\\PaymentIntent')
            ->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('Amount must convert to at least 30 pence.')); // raw provider error

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/create-intent', 'POST', [
            'booking' => $application->booking_token,
            'payment_method' => 'stripe',
        ]);
        $this->attachSession($request);

        $response = $controller->createStripePaymentIntent($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(500, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertSame('Failed to create payment intent', $data['error']);
        $this->assertSame('Unable to initialize card payment right now. Please try again in a moment.', $data['message']);
        $this->assertSame('payment_intent_creation_failed', $data['code']);
    }

    public function test_create_stripe_payment_intent_can_convert_to_local_currency_when_supported(): void
    {
        config()->set('services.stripe.secret_key', 'sk_test_mock');
        config()->set('services.stripe.allowed_currencies', 'GBP,NGN');
        config()->set('services.stripe.fx_rate_url', 'https://fx.example.test/convert');

        Http::fake([
            'https://fx.example.test/convert*' => Http::response([
                'success' => true,
                'result' => 2000,
            ], 200),
        ]);

        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        Mockery::mock('alias:Stripe\\Stripe')
            ->shouldReceive('setApiKey')
            ->once()
            ->with('sk_test_mock');

        $intent = new \stdClass();
        $intent->id = 'pi_test_ngn_123';
        $intent->client_secret = 'pi_test_ngn_123_secret_abc';

        Mockery::mock('alias:Stripe\\PaymentIntent')
            ->shouldReceive('create')
            ->once()
            ->withArgs(function (array $payload) use ($application) {
                return $payload['amount'] === 15000000
                    && $payload['currency'] === 'ngn'
                    && ($payload['metadata']['application_id'] ?? null) === $application->id
                    && (($payload['automatic_payment_methods']['enabled'] ?? false) === true);
            })
            ->andReturn($intent);

        $controller = app(ConsultationController::class);
        $request = Request::create('/book-consultation/payment/create-intent', 'POST', [
            'booking' => $application->booking_token,
            'payment_method' => 'stripe',
            'preferred_currency' => 'NGN',
        ]);
        $this->attachSession($request);

        $response = $controller->createStripePaymentIntent($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertSame('NGN', $data['currency']);
        $this->assertSame('NGN 150,000.00', $data['display_amount']);

        $payment = Payment::where('payment_reference', 'pi_test_ngn_123')->first();
        $this->assertNotNull($payment);
        $this->assertSame(150000.00, (float) $payment->amount);
        $this->assertSame('NGN', $payment->currency);
    }

    private function makeConsultationApplication(array $overrides = []): ConsultationApplication
    {
        return ConsultationApplication::create(array_merge([
            'booking_token' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Jane Founder',
            'email' => 'jane@example.com',
            'phone' => '+2348012345678',
            'company' => 'Acme Labs',
            'industry' => 'Fintech',
            'business_description' => 'Business description',
            'selected_goals' => ['Scale operations'],
            'selected_challenges' => ['Fundraising'],
            'desired_outcomes' => json_encode([
                'motivation' => 'Need strategic clarity',
                'success_definition' => 'Actionable roadmap',
            ]),
            'status' => 'pending',
            'submitted_at' => now(),
        ], $overrides));
    }

    private function attachSession(Request $request): void
    {
        $session = app('session')->driver();
        $session->start();
        $request->setLaravelSession($session);
    }
}
