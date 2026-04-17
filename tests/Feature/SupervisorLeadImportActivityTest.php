<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\LeadController;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SupervisorLeadImportActivityTest extends TestCase
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

    public function test_supervisor_sees_aggregated_staff_lead_activity_for_own_organization(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $supervisor = $this->createUserWithRole('supervisor', $orgA->id);
        $staffA1 = $this->createUserWithRole('staff', $orgA->id);
        $staffA2 = $this->createUserWithRole('staff', $orgA->id);
        $staffB = $this->createUserWithRole('staff', $orgB->id);

        $this->createLead($orgA->id, $staffA1->id, now()->subDays(2), 'file_import');
        $this->createLead($orgA->id, $staffA1->id, now()->subDays(1), null);
        $this->createLead($orgA->id, $staffA2->id, now()->subDays(3), 'file_import');
        $this->createLead($orgB->id, $staffB->id, now()->subDay(), 'file_import');

        $this->actingAs($supervisor);

        $response = app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET', [
            'period' => 'monthly',
        ]));

        $stats = collect($response->getData()['staffLeadStats'])->keyBy(fn ($row) => $row['user']->id);

        $this->assertTrue($stats->has($staffA1->id));
        $this->assertTrue($stats->has($staffA2->id));
        $this->assertFalse($stats->has($staffB->id));

        $this->assertSame(2, $stats[$staffA1->id]['total_leads']);
        $this->assertSame(1, $stats[$staffA1->id]['imported_leads']);
        $this->assertSame(1, $stats[$staffA1->id]['manual_leads']);

        $this->assertSame(1, $stats[$staffA2->id]['total_leads']);
        $this->assertSame(1, $stats[$staffA2->id]['imported_leads']);
        $this->assertSame(0, $stats[$staffA2->id]['manual_leads']);
    }

    public function test_custom_date_range_filters_counts_correctly(): void
    {
        Carbon::setTestNow('2026-04-17 10:00:00');

        $org = $this->createOrganization('Org Date Filter');
        $supervisor = $this->createUserWithRole('supervisor', $org->id);
        $staff = $this->createUserWithRole('staff', $org->id);

        $this->createLead($org->id, $staff->id, Carbon::parse('2026-04-10 08:00:00'), 'file_import');
        $this->createLead($org->id, $staff->id, Carbon::parse('2026-04-12 09:00:00'), null);
        $this->createLead($org->id, $staff->id, Carbon::parse('2026-04-15 10:00:00'), null);

        $this->actingAs($supervisor);

        $response = app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET', [
            'period' => 'custom',
            'date_from' => '2026-04-11',
            'date_to' => '2026-04-14',
        ]));

        $row = collect($response->getData()['staffLeadStats'])->firstWhere('user.id', $staff->id);

        $this->assertSame(1, $row['total_leads']);
        $this->assertSame(0, $row['imported_leads']);
        $this->assertSame(1, $row['manual_leads']);

        Carbon::setTestNow();
    }

    public function test_supervisor_activity_dashboard_returns_zero_counts_when_no_leads_exist(): void
    {
        $org = $this->createOrganization('Org Empty');
        $supervisor = $this->createUserWithRole('supervisor', $org->id);
        $staff = $this->createUserWithRole('staff', $org->id);

        $this->actingAs($supervisor);

        $response = app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET', [
            'period' => 'weekly',
        ]));

        $row = collect($response->getData()['staffLeadStats'])->firstWhere('user.id', $staff->id);

        $this->assertSame(0, $row['total_leads']);
        $this->assertSame(0, $row['imported_leads']);
        $this->assertSame(0, $row['manual_leads']);
    }

    public function test_non_supervisor_cannot_access_import_activity_dashboard(): void
    {
        $org = $this->createOrganization('Org Auth');
        $staff = $this->createUserWithRole('staff', $org->id);

        $this->actingAs($staff);

        try {
            app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET'));
            $this->fail('Expected non-supervisor user to be denied.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_invalid_date_range_is_rejected(): void
    {
        $org = $this->createOrganization('Org Invalid Date');
        $supervisor = $this->createUserWithRole('supervisor', $org->id);

        $this->actingAs($supervisor);

        try {
            app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET', [
                'period' => 'custom',
                'date_from' => '2026-04-20',
                'date_to' => '2026-04-10',
            ]));
            $this->fail('Expected validation exception for invalid date range.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('date_to', $exception->errors());
        }
    }

    public function test_large_dataset_aggregates_correctly_per_staff_user(): void
    {
        $org = $this->createOrganization('Org Large');
        $supervisor = $this->createUserWithRole('supervisor', $org->id);
        $staffA = $this->createUserWithRole('staff', $org->id);
        $staffB = $this->createUserWithRole('staff', $org->id);

        for ($i = 0; $i < 120; $i++) {
            $this->createLead($org->id, $staffA->id, now()->subDays(1), $i % 2 === 0 ? 'file_import' : null);
        }

        for ($i = 0; $i < 80; $i++) {
            $this->createLead($org->id, $staffB->id, now()->subDays(2), 'file_import');
        }

        $this->actingAs($supervisor);

        $response = app(LeadController::class)->importActivity(Request::create('/admin/crm/import-activity', 'GET', [
            'period' => 'monthly',
        ]));

        $stats = collect($response->getData()['staffLeadStats'])->keyBy(fn ($row) => $row['user']->id);

        $this->assertSame(120, $stats[$staffA->id]['total_leads']);
        $this->assertSame(60, $stats[$staffA->id]['imported_leads']);
        $this->assertSame(60, $stats[$staffA->id]['manual_leads']);

        $this->assertSame(80, $stats[$staffB->id]['total_leads']);
        $this->assertSame(80, $stats[$staffB->id]['imported_leads']);
        $this->assertSame(0, $stats[$staffB->id]['manual_leads']);
    }

    private function createOrganization(string $name): Organization
    {
        return Organization::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
        ]);
    }

    private function createUserWithRole(string $role, ?int $organizationId): User
    {
        $user = User::factory()->create([
            'organization_id' => $organizationId,
        ]);

        $user->assignRole($role);

        return $user;
    }

    private function createLead(?int $organizationId, ?int $createdBy, Carbon $createdAt, ?string $sourceType): void
    {
        $lead = Lead::create([
            'organization_id' => $organizationId,
            'created_by' => $createdBy,
            'first_name' => 'Lead ' . Str::random(6),
            'email' => Str::lower((string) Str::uuid()) . '@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
            'source_type' => $sourceType,
        ]);

        // created_at/updated_at are not mass assignable on Lead, so set explicitly.
        $lead->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->saveQuietly();
    }
}
