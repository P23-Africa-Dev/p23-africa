<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use App\Models\ConsultationApplication;
use App\Models\ContactSubmission;
use App\Services\GoogleCalendarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ContactAssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Notification::fake();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
        // Never reach the real Google Calendar from tests.
        $this->mock(GoogleCalendarService::class, fn ($mock) => $mock->shouldReceive('createEvent')->andReturn([
            'success' => true, 'event_id' => 'evt_test', 'meet_link' => 'https://meet.google.com/abc-defg-hij', 'html_link' => 'https://calendar.google.com/event?eid=test',
        ]));
    }

    private function answers(array $overrides = []): array
    {
        return array_replace([
            'form_flow' => 'contact-v3', 'name' => 'Assessment Test', 'email' => 'assessment@example.test',
            'phone' => '8012345678', 'country_code' => '+234', 'company_name' => 'Example Company',
            'company_website' => 'https://example.test', 'industry' => 'Manufacturing', 'role' => 'Founder',
            'selected_goals' => ['expand', 'partners'], 'message' => 'Find a distributor to launch our product in Nigeria.',
            'start_timeline' => 'Within the next month', 'budget_range' => 5000,
            'referral_source' => 'LinkedIn',
        ], $overrides);
    }

    private function slot(array $overrides = []): array
    {
        return array_replace([
            'session_date' => now()->addDays(2)->format('Y-m-d'), 'session_time' => '10:00', 'timezone' => 'GMT',
        ], $overrides);
    }

    public function test_budget_above_the_consultation_band_routes_to_the_discovery_call(): void
    {
        $response = $this->postJson('/contact', $this->answers())->assertOk()->assertJsonPath('outcome.route', 'discovery');
        $this->assertStringContainsString('/contact/discovery-call', $response->json('outcome.url'));
        $submission = ContactSubmission::sole();
        $this->assertSame(5000, (int) $submission->budget_range);
        $this->assertSame('GBP', $submission->project_details['budget_currency']);
        $this->assertSame('LinkedIn', $submission->project_details['referral_source']);
        $this->assertSame('https://example.test', $submission->project_details['company_website']);
        $this->assertSame('discovery', $submission->project_details['assessment_outcome']);
        $this->assertContains('Find distributors and local partners', $submission->project_details['selected_goals']);
        Mail::assertSent(ContactFormMail::class, fn ($mail) => str_contains($mail->render(), 'https://example.test'));
        $this->get($response->json('outcome.url'))->assertOk()->assertSee('Schedule Your Discovery Call');
    }

    public function test_budget_bands_route_to_resources_consultation_and_discovery(): void
    {
        foreach ([0 => 'resources', 1499 => 'resources', 1500 => 'consultation', 2500 => 'consultation', 2501 => 'discovery'] as $budget => $route) {
            $this->postJson('/contact', $this->answers(['budget_range' => $budget, 'email' => "budget{$budget}@example.test"]))
                ->assertOk()->assertJsonPath('outcome.route', $route);
        }
    }

    public function test_resources_route_points_to_the_resource_hub(): void
    {
        $response = $this->postJson('/contact', $this->answers(['budget_range' => 800]))->assertOk();
        $this->assertStringContainsString('/resource-hub', $response->json('outcome.url'));
    }

    public function test_consultation_route_schedules_then_collects_challenges_then_reaches_the_summary(): void
    {
        $response = $this->postJson('/contact', $this->answers(['budget_range' => 2000]))
            ->assertOk()->assertJsonPath('outcome.route', 'consultation');
        $this->get($response->json('outcome.url'))->assertOk()->assertSee('Schedule Your Consultation');

        $this->get('/contact/consultation/challenges')->assertRedirect();
        $slot = $this->postJson('/contact/consultation/schedule', $this->slot())->assertOk()->assertJsonPath('success', true);
        $this->assertStringContainsString('/contact/consultation/challenges', $slot->json('redirect'));
        $this->get('/contact/consultation/challenges')->assertOk()->assertSee('Key Challenges');

        $this->post('/contact/consultation/challenges', ['key_challenges' => ['Low or inconsistent sales', 'Scaling challenges']])
            ->assertRedirect();
        $application = ConsultationApplication::sole();
        $this->assertSame(['Low or inconsistent sales', 'Scaling challenges'], $application->selected_challenges);
        $this->assertSame('10:00', json_decode($application->desired_outcomes, true)['preferred_slot']['session_time']);
        $this->get('/book-consultation/summary?booking='.$application->booking_token)->assertOk();
    }

    public function test_key_challenges_are_limited_to_three_known_options(): void
    {
        $this->postJson('/contact', $this->answers(['budget_range' => 2000]))->assertOk();
        $this->postJson('/contact/consultation/schedule', $this->slot())->assertOk();
        $this->postJson('/contact/consultation/challenges', ['key_challenges' => ['Low or inconsistent sales', 'Scaling challenges', 'Operational inefficiencies', 'Weak marketing strategy']])
            ->assertUnprocessable()->assertJsonValidationErrors('key_challenges');
        $this->postJson('/contact/consultation/challenges', ['key_challenges' => ['Something unknown']])
            ->assertUnprocessable()->assertJsonValidationErrors('key_challenges.0');
        $this->assertDatabaseCount('consultation_applications', 0);
    }

    public function test_invalid_budget_and_website_are_rejected_before_saving(): void
    {
        $this->postJson('/contact', $this->answers([
            'selected_goals' => ['unknown'], 'budget_range' => -5, 'company_website' => 'javascript:alert(1)',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['selected_goals.0', 'budget_range', 'company_website']);
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_discovery_request_is_saved_only_for_a_discovery_assessment(): void
    {
        $this->postJson('/contact', $this->answers())->assertOk();
        $this->postJson('/contact/discovery-request', $this->slot())->assertOk()->assertJsonPath('success', true);
        $request = ContactSubmission::sole()->project_details['discovery_request'];
        $this->assertSame('10:00', $request['preferred_time']);
        $this->assertSame('scheduled', $request['status']);
        $this->assertSame('https://meet.google.com/abc-defg-hij', $request['meet_link']);
    }

    public function test_discovery_request_is_kept_when_the_calendar_is_unavailable(): void
    {
        $this->mock(GoogleCalendarService::class, fn ($mock) => $mock->shouldReceive('createEvent')->andReturn(['success' => false, 'error' => 'offline']));
        $this->postJson('/contact', $this->answers())->assertOk();
        $this->postJson('/contact/discovery-request', $this->slot(['timezone' => 'Africa/Lagos']))->assertOk()->assertJsonPath('success', true);
        $this->assertSame('requested', ContactSubmission::sole()->project_details['discovery_request']['status']);
    }

    public function test_paid_route_two_booking_opens_scheduling_with_the_chosen_time(): void
    {
        $this->postJson('/contact', $this->answers(['budget_range' => 2000]))->assertOk();
        $date = now()->addDays(3)->format('Y-m-d');
        $this->postJson('/contact/consultation/schedule', $this->slot(['session_date' => $date, 'session_time' => '14:30']))->assertOk();
        $this->post('/contact/consultation/challenges', ['key_challenges' => ['Scaling challenges']])->assertRedirect();
        $application = ConsultationApplication::sole();
        $application->update(['status' => 'paid']);

        $this->get('/book-consultation/schedule?booking='.$application->booking_token)
            ->assertOk()->assertSee('"session_date":"'.$date.'"', false)->assertSee('"session_time":"14:30"', false);
    }

    public function test_consultation_time_must_be_after_today_to_match_paid_booking(): void
    {
        $this->postJson('/contact', $this->answers(['budget_range' => 2000]))->assertOk();
        $this->postJson('/contact/consultation/schedule', $this->slot(['session_date' => now()->format('Y-m-d')]))
            ->assertUnprocessable()->assertJsonValidationErrors('session_date');
    }

    public function test_other_routes_cannot_use_each_others_steps(): void
    {
        $this->postJson('/contact', $this->answers(['budget_range' => 500]))->assertOk();
        $this->postJson('/contact/discovery-request', $this->slot())->assertForbidden();
        $this->postJson('/contact/consultation/schedule', $this->slot())->assertForbidden();
        $this->get('/contact/discovery-call')->assertRedirect();
    }

    public function test_discovery_calendar_rejects_past_dates(): void
    {
        $this->postJson('/contact', $this->answers())->assertOk();
        $this->postJson('/contact/discovery-request', $this->slot(['session_date' => now()->subDay()->format('Y-m-d')]))
            ->assertUnprocessable()->assertJsonValidationErrors('session_date');
    }

    public function test_repeat_clicks_are_deduplicated_but_changed_answers_produce_a_new_outcome(): void
    {
        $this->postJson('/contact', $this->answers())->assertOk()->assertJsonPath('outcome.route', 'discovery');
        $this->postJson('/contact', $this->answers())->assertOk()->assertJsonPath('outcome.route', 'discovery');
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->postJson('/contact', $this->answers(['budget_range' => 300]))
            ->assertOk()->assertJsonPath('outcome.route', 'resources');
        $this->assertDatabaseCount('contact_submissions', 2);
    }
}
