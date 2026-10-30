<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
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
}
