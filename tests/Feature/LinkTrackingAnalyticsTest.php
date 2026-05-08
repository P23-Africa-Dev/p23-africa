<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\TrackedLinkAnalyticsController;
use App\Http\Controllers\TrackedLinkRedirectController;
use App\Models\Organization;
use App\Models\TrackedLink;
use App\Models\TrackedLinkClick;
use App\Models\User;
use App\Services\LinkTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LinkTrackingAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
        Role::findOrCreate('staff', 'web');
    }

    public function test_redirect_route_tracks_click_then_redirects(): void
    {
        $trackedLink = TrackedLink::create([
            'name' => 'Migration Link',
            'tracking_code' => 'crm-migration',
            'destination_url' => 'https://mydomain.com',
            'is_active' => true,
        ]);

        $request = Request::create('/r/crm-migration', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'REMOTE_ADDR' => '203.0.113.24',
            'HTTP_REFERER' => 'https://example-referrer.test/source',
        ]);

        $response = app(TrackedLinkRedirectController::class)(
            $request,
            'crm-migration',
            app(LinkTrackingService::class)
        );

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame('https://mydomain.com', $response->headers->get('Location'));

        $this->assertDatabaseHas('tracked_link_clicks', [
            'tracked_link_id' => $trackedLink->id,
            'ip_address' => '203.0.113.24',
            'is_unique_click' => 1,
        ]);

        $this->assertDatabaseHas('tracked_links', [
            'id' => $trackedLink->id,
            'total_clicks' => 1,
        ]);
    }

    public function test_service_marks_repeated_clicks_for_same_visitor(): void
    {
        $trackedLink = TrackedLink::create([
            'name' => 'Repeat Link',
            'tracking_code' => 'repeat-link',
            'destination_url' => 'https://mydomain.com',
            'is_active' => true,
        ]);

        $service = app(LinkTrackingService::class);

        $requestA = Request::create('/r/repeat-link', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_2)',
            'REMOTE_ADDR' => '198.51.100.10',
        ]);

        $requestB = Request::create('/r/repeat-link', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_2)',
            'REMOTE_ADDR' => '198.51.100.10',
        ]);

        $service->trackClick($trackedLink, $requestA);
        $service->trackClick($trackedLink, $requestB);

        $clicks = TrackedLinkClick::where('tracked_link_id', $trackedLink->id)
            ->orderBy('id')
            ->get();

        $this->assertCount(2, $clicks);
        $this->assertTrue($clicks[0]->is_unique_click);
        $this->assertFalse($clicks[1]->is_unique_click);
    }

    public function test_legacy_admin_can_access_link_tracking_dashboard(): void
    {
        $legacyAdmin = $this->createUserWithRole('admin', null);

        $this->actingAs($legacyAdmin);

        $response = app(TrackedLinkAnalyticsController::class)->index(
            Request::create('/admin/link-tracking', 'GET')
        );

        $this->assertSame('admin.link-tracking.index', $response->name());
    }

    public function test_staff_user_is_denied_link_tracking_dashboard_access(): void
    {
        $staff = $this->createUserWithRole('staff', null);

        $this->actingAs($staff);

        try {
            app(TrackedLinkAnalyticsController::class)->index(
                Request::create('/admin/link-tracking', 'GET')
            );

            $this->fail('Expected a 403 exception for staff user.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_non_legacy_admin_is_denied_link_tracking_dashboard_access(): void
    {
        $organization = Organization::create([
            'name' => 'Acme Org',
            'slug' => 'acme-org',
            'description' => 'Test organization',
        ]);

        $orgAdmin = $this->createUserWithRole('admin', $organization->id);

        $this->actingAs($orgAdmin);

        try {
            app(TrackedLinkAnalyticsController::class)->index(
                Request::create('/admin/link-tracking', 'GET')
            );

            $this->fail('Expected a 403 exception for non-legacy admin.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    private function createUserWithRole(string $role, ?int $organizationId): User
    {
        $user = User::factory()->create([
            'organization_id' => $organizationId,
            'email' => Str::lower((string) Str::uuid()) . '@example.test',
        ]);

        $user->assignRole($role);

        return $user;
    }
}
