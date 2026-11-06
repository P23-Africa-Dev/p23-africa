<?php

namespace Tests\Feature;

use App\Mail\PaymentAssistanceRequestedMail;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ConsultationPaymentAssistanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
    }

    public function test_admin_payment_panel_shows_major_unit_amount_not_divided_by_100(): void
    {
        $application = $this->makeConsultationApplication(['status' => 'paid']);

        Payment::create([
            'consultation_application_id' => $application->id,
            'payment_method' => 'stripe',
            'payment_reference' => 'pi_test_amount_display',
            'amount' => 75.00,
            'currency' => 'GBP',
            'status' => 'successful',
            'paid_at' => now(),
        ]);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get('/admin/consultations/' . $application->id)
            ->assertOk()
            ->assertSee('GBP')
            ->assertSee('75.00')
            ->assertDontSee('$0.75')
            ->assertDontSee('0.75');
    }

    public function test_visitor_can_request_payment_assistance_and_request_is_idempotent(): void
    {
        Mail::fake();

        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        $payload = [
            'booking' => $application->booking_token,
            'note' => 'Card declined in my region',
        ];

        $first = $this->postJson('/book-consultation/payment/assistance', $payload);
        $first->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('already_requested', false)
            ->assertJsonFragment(['booking_token' => $application->booking_token]);

        $application->refresh();
        $this->assertSame('payment_assistance', $application->status);
        $this->assertNotNull($application->payment_assistance_requested_at);
        $this->assertSame('Card declined in my region', $application->payment_assistance_note);

        Mail::assertSent(PaymentAssistanceRequestedMail::class, function (PaymentAssistanceRequestedMail $mail) use ($application) {
            return $mail->application->is($application)
                && abs($mail->amount - 75.00) < 0.001
                && $mail->currency === 'GBP';
        });

        $second = $this->postJson('/book-consultation/payment/assistance', $payload);
        $second->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('already_requested', true);

        Mail::assertSent(PaymentAssistanceRequestedMail::class, 1);
    }

    public function test_payment_assistance_without_booking_is_rejected(): void
    {
        $this->postJson('/book-consultation/payment/assistance', [
            'note' => 'Need bank transfer',
        ])->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_admin_can_mark_assisted_payment_received(): void
    {
        $application = $this->makeConsultationApplication([
            'status' => 'payment_assistance',
            'payment_assistance_requested_at' => now(),
            'payment_assistance_note' => 'Prefer bank transfer',
        ]);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->post('/admin/consultations/' . $application->id . '/payment-assistance/mark-paid', [
                'reference' => 'BANK-REF-001',
                'notes' => 'Received via bank transfer',
            ])
            ->assertRedirect();

        $application->refresh();
        $this->assertSame('paid', $application->status);

        $payment = Payment::where('consultation_application_id', $application->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame('assisted', $payment->payment_method);
        $this->assertSame('successful', $payment->status);
        $this->assertSame(75.00, (float) $payment->amount);
        $this->assertSame('GBP', $payment->currency);
        $this->assertSame('BANK-REF-001', $payment->payment_reference);
    }

    public function test_payment_selection_shows_assistance_control(): void
    {
        $application = $this->makeConsultationApplication(['status' => 'payment_pending']);

        $this->get('/book-consultation/payment-selection?booking=' . $application->booking_token)
            ->assertOk()
            ->assertSee('Having trouble paying with Stripe?')
            ->assertSee('Request another payment option');
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
}
