<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use App\Models\ConsultationApplication;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContactFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Notification::fake();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
    }

    private function enquiry(): array
    {
        return [
            'form_flow' => 'contact-v2',
            'name' => 'Contact Flow Test', 'company_name' => 'Example Company',
            'email' => 'contact-flow@example.test', 'phone' => '8012345678', 'country_code' => '+234',
            'role' => 'Founder', 'industry' => 'Manufacturing',
            'business_description' => 'We manufacture commercial equipment.',
            'selected_services' => ['Market Entry & Expansion', 'Distributor & Partner Development'],
            'expansion_markets' => ['Nigeria', 'Ghana'], 'message' => 'Find a distributor and test buyer demand.',
            'referral_source' => 'LinkedIn', 'start_timeline' => 'Within the next month',
            'budget_range' => 12000, 'additional_notes' => 'Our team is based in London.',
        ];
    }

    public function test_completed_flow_stores_all_answers_and_sends_them_in_the_owner_email(): void
    {
        $this->postJson('/contact', $this->enquiry())->assertOk()->assertJson(['success' => true]);
        $submission = ContactSubmission::sole();
        $this->assertSame(['Market Entry & Expansion', 'Distributor & Partner Development'], $submission->services);
        $this->assertSame(['Nigeria', 'Ghana'], $submission->project_details['expansion_markets']);
        $this->assertSame('Founder', $submission->project_details['role']);
        $this->assertEquals(12000, $submission->budget_range);
        Mail::assertSent(ContactFormMail::class, function ($mail) {
            return str_contains($mail->render(), 'Our team is based in London.')
                && str_contains($mail->render(), 'Within the next month')
                && str_contains($mail->render(), 'Nigeria, Ghana');
        });
    }

    public function test_missing_step_answers_are_rejected_and_a_corrected_retry_is_saved(): void
    {
        $data = $this->enquiry();
        unset($data['business_description'], $data['start_timeline']);
        $data['selected_services'] = [];
        $this->postJson('/contact', $data)->assertUnprocessable()
            ->assertJsonValidationErrors(['business_description', 'selected_services', 'start_timeline']);
        $this->assertDatabaseCount('contact_submissions', 0);
        $this->postJson('/contact', $this->enquiry())->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_invalid_service_timeline_and_empty_markets_are_rejected(): void
    {
        $data = array_replace($this->enquiry(), [
            'selected_services' => ['Invalid service'], 'expansion_markets' => [], 'start_timeline' => 'Invalid timeline',
        ]);
        $this->postJson('/contact', $data)->assertUnprocessable()
            ->assertJsonValidationErrors(['selected_services.0', 'expansion_markets', 'start_timeline']);
        Mail::assertNothingSent();
    }

    public function test_other_referral_requires_details(): void
    {
        $data = array_replace($this->enquiry(), ['referral_source' => 'Other']);
        $this->postJson('/contact', $data)->assertUnprocessable()->assertJsonValidationErrors('referral_other');
    }

    public function test_duplicate_submissions_do_not_create_another_record_or_email(): void
    {
        $this->postJson('/contact', $this->enquiry())->assertOk();
        $this->postJson('/contact', $this->enquiry())->assertOk();
        $this->assertDatabaseCount('contact_submissions', 1);
        Mail::assertSent(ContactFormMail::class, 1);
    }

    public function test_legacy_contact_payloads_continue_to_work(): void
    {
        $this->postJson('/contact', [
            'name' => 'Legacy Test', 'company_name' => 'Example Company', 'email' => 'legacy@example.test',
            'phone' => '8012345678', 'country_code' => '+234', 'services' => 'Sales, Marketing',
            'message' => 'Please help us grow.', 'budget_range' => 500,
        ])->assertOk()->assertJson(['success' => true]);
        $this->assertNull(ContactSubmission::sole()->project_details);
    }

    public function test_contact_v2_route_two_flow_proceeds_through_summary_to_payment_success(): void
    {
        $payload = array_replace($this->enquiry(), ['budget_range' => 2000]);
        $response = $this->postJson('/contact', $payload)
            ->assertOk()
            ->assertJsonPath('outcome.route', 'consultation');

        $this->get($response->json('outcome.url'))->assertOk()->assertSee('Schedule Your Consultation');

        $slot = [
            'session_date' => now()->addDays(2)->format('Y-m-d'),
            'session_time' => '11:00',
            'timezone' => 'GMT',
        ];
        $this->postJson('/contact/consultation/schedule', $slot)->assertOk()->assertJsonPath('success', true);

        $this->get('/contact/consultation/challenges')->assertOk()->assertSee('Key Challenges');
        $this->post('/contact/consultation/challenges', [
            'key_challenges' => ['Weak marketing strategy', 'Scaling challenges'],
        ])->assertRedirect();

        $application = ConsultationApplication::sole();
        $this->get('/book-consultation/summary?booking=' . $application->booking_token)
            ->assertOk()
            ->assertSee('Nigeria')
            ->assertSee('Ghana')
            ->assertSee('Weak marketing strategy')
            ->assertSee('Scaling challenges');

        $initiateResponse = $this->post('/book-consultation/payment/initiate', [
            'booking' => $application->booking_token,
        ]);
        $initiateResponse->assertRedirect(route('consultation.payment-success', ['booking' => $application->booking_token]));

        $this->get('/book-consultation/payment-success?booking=' . $application->booking_token)
            ->assertOk()
            ->assertSee('Payment Successful');
    }

    public function test_contact_page_hides_navbar_and_stateless_assessment_tokens_render_route_one_and_two(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertDontSee('id="navbar"', false);

        // Simulate a stateless serverless container where only the ?assessment= token arrives on GET /contact/discovery-call
        $discoveryPayload = [
            'outcome' => 'discovery',
            'name' => 'Stateless Discovery User',
            'email' => 'discovery-stateless@example.test',
            'phone' => '8012345678',
            'country_code' => '+234',
            'company_name' => 'Stateless Co',
            'industry' => 'Technology',
            'business_description' => 'Testing stateless discovery routing.',
            'message' => 'Expand across West Africa.',
            'services' => ['Market Entry & Expansion'],
            'expansion_markets' => ['Nigeria'],
            'budget_range' => 3500,
        ];
        $discoveryToken = rtrim(strtr(base64_encode(json_encode($discoveryPayload)), '+/', '-_'), '=');

        $this->get('/contact/discovery-call?assessment=' . urlencode($discoveryToken))
            ->assertOk()
            ->assertSee('Schedule Your Discovery Call');

        // Simulate a stateless serverless container for Route 2 (1500 - 2500)
        $consultationPayload = array_replace($discoveryPayload, [
            'outcome' => 'consultation',
            'budget_range' => 2000,
        ]);
        $consultationToken = rtrim(strtr(base64_encode(json_encode($consultationPayload)), '+/', '-_'), '=');

        $this->get('/contact/consultation/schedule?assessment=' . urlencode($consultationToken))
            ->assertOk()
            ->assertSee('Schedule Your Consultation');
    }
}
