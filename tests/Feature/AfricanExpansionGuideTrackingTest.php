<?php

namespace Tests\Feature;

use App\Models\PageVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AfricanExpansionGuideTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        URL::forceRootUrl('http://localhost');
    }

    public function test_opening_the_guide_records_a_download_visit_and_returns_the_pdf(): void
    {
        $this->assertFileExists(public_path('documents/African.pdf'));

        $response = $this->get(route('lead-magnets.african-expansion.guide'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $this->assertDatabaseHas('page_visits', [
            'route_name' => 'African_Expansion_Guide_Download',
            'page_title' => 'African Expansion Guide PDF',
        ]);
    }

    public function test_an_admin_can_open_the_expansion_guide_report(): void
    {
        Role::findOrCreate('admin');
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        PageVisit::create([
            'route_name' => 'African_Expansion_Landing_Page',
            'page_title' => 'African Expansion for Manufacturers',
            'page_url' => 'http://localhost/african-expansion-for-manufacturers',
            'device_type' => 'desktop',
            'ip_address' => '1.2.3.4',
            'user_agent' => 'PHPUnit',
            'browser' => 'Chrome',
            'session_id' => 'session-visit-1',
            'country' => 'Nigeria',
            'city' => 'Lagos',
            'is_unique_visitor' => true,
        ]);

        PageVisit::create([
            'route_name' => 'African_Expansion_Guide_Download',
            'page_title' => 'African Expansion Guide PDF',
            'page_url' => 'http://localhost/african-expansion-for-manufacturers/guide',
            'device_type' => 'mobile',
            'ip_address' => '1.2.3.4',
            'user_agent' => 'PHPUnit',
            'browser' => 'Safari',
            'session_id' => 'session-download-1',
            'country' => 'Nigeria',
            'city' => 'Lagos',
            'is_unique_visitor' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.african-expansion.index'));

        $response->assertOk();
        $response->assertSee('African Expansion Guide', false);
        $response->assertSee('Nigeria', false);
        $response->assertSee('Guide open', false);
        $response->assertSee('Visit', false);
    }
}
