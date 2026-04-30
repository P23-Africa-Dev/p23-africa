<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\LeadController;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadDrive;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CrmLeadFlowRefactorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('staff', 'web');
    }

    public function test_user_cannot_update_activity_for_lead_outside_organization(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $actor = $this->createStaffUser($orgA);
        $leadInOtherOrg = $this->createLeadForOrganization($orgB);

        $activity = LeadActivity::create([
            'lead_id' => $leadInOtherOrg->id,
            'type' => 'note',
            'title' => 'Original title',
        ]);

        $controller = app(LeadController::class);
        $request = Request::create('/admin/crm/activity/' . $activity->id, 'PATCH', [
            'title' => 'Updated title',
        ]);

        $this->actingAs($actor);

        try {
            $controller->updateActivity($request, $activity);
            $this->fail('Expected a 403 exception when updating a foreign activity.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }

        $this->assertDatabaseHas('lead_activities', [
            'id' => $activity->id,
            'title' => 'Original title',
        ]);
    }

    public function test_user_cannot_complete_activity_for_lead_outside_organization(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $actor = $this->createStaffUser($orgA);
        $leadInOtherOrg = $this->createLeadForOrganization($orgB);

        $activity = LeadActivity::create([
            'lead_id' => $leadInOtherOrg->id,
            'type' => 'task',
            'title' => 'Follow-up task',
            'is_completed' => false,
        ]);

        $controller = app(LeadController::class);

        $this->actingAs($actor);

        try {
            $controller->completeActivity($activity);
            $this->fail('Expected a 403 exception when completing a foreign activity.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }

        $this->assertDatabaseHas('lead_activities', [
            'id' => $activity->id,
            'is_completed' => false,
        ]);
    }

    public function test_bulk_assign_rejects_assignee_outside_user_organization(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $actor = $this->createStaffUser($orgA);
        $assigneeOutsideOrg = $this->createStaffUser($orgB);
        $lead = $this->createLeadForOrganization($orgA);

        $controller = app(LeadController::class);
        $request = Request::create('/admin/crm/bulk-update', 'POST', [
            'ids' => [$lead->id],
            'action' => 'assign',
            'value' => $assigneeOutsideOrg->id,
        ]);

        $this->actingAs($actor);

        $response = $controller->bulkUpdate($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame([
            'success' => false,
            'message' => 'Cannot assign leads to a user outside your organization.',
        ], $response->getData(true));

        $this->assertNull($lead->fresh()->assigned_to);
    }

    public function test_bulk_priority_rejects_invalid_value(): void
    {
        $org = $this->createOrganization('Org Priority');
        $actor = $this->createStaffUser($org);
        $lead = $this->createLeadForOrganization($org, ['priority' => 'medium']);

        $controller = app(LeadController::class);
        $request = Request::create('/admin/crm/bulk-update', 'POST', [
            'ids' => [$lead->id],
            'action' => 'priority',
            'value' => 'critical',
        ]);

        $this->actingAs($actor);

        $response = $controller->bulkUpdate($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'success' => false,
            'message' => 'Invalid priority value.',
        ], $response->getData(true));

        $this->assertSame('medium', $lead->fresh()->priority);
    }

    public function test_bulk_crm_flow_assign_status_and_delete_succeeds_for_same_org(): void
    {
        $org = $this->createOrganization('Org Flow');
        $actor = $this->createStaffUser($org);
        $assignee = $this->createStaffUser($org);

        $leadA = $this->createLeadForOrganization($org);
        $leadB = $this->createLeadForOrganization($org);

        $ids = [$leadA->id, $leadB->id];

        $controller = app(LeadController::class);

        $this->actingAs($actor);

        $assignResponse = $controller->bulkUpdate(Request::create('/admin/crm/bulk-update', 'POST', [
            'ids' => $ids,
            'action' => 'assign',
            'value' => $assignee->id,
        ]));

        $this->assertInstanceOf(JsonResponse::class, $assignResponse);
        $this->assertSame(200, $assignResponse->getStatusCode());
        $this->assertTrue($assignResponse->getData(true)['success']);

        $this->assertDatabaseHas('leads', ['id' => $leadA->id, 'assigned_to' => $assignee->id]);
        $this->assertDatabaseHas('leads', ['id' => $leadB->id, 'assigned_to' => $assignee->id]);

        $statusResponse = $controller->bulkUpdate(Request::create('/admin/crm/bulk-update', 'POST', [
            'ids' => $ids,
            'action' => 'status',
            'value' => 'contacted',
        ]));

        $this->assertSame(200, $statusResponse->getStatusCode());
        $this->assertTrue($statusResponse->getData(true)['success']);

        $this->assertDatabaseHas('leads', ['id' => $leadA->id, 'status' => 'contacted']);
        $this->assertDatabaseHas('leads', ['id' => $leadB->id, 'status' => 'contacted']);

        $deleteResponse = $controller->bulkUpdate(Request::create('/admin/crm/bulk-update', 'POST', [
            'ids' => $ids,
            'action' => 'delete',
        ]));

        $this->assertSame(200, $deleteResponse->getStatusCode());
        $this->assertTrue($deleteResponse->getData(true)['success']);

        $this->assertSoftDeleted('leads', ['id' => $leadA->id]);
        $this->assertSoftDeleted('leads', ['id' => $leadB->id]);
    }

    public function test_user_can_update_lead_pipeline_within_same_organization(): void
    {
        $org = $this->createOrganization('Org Pipeline');
        $actor = $this->createStaffUser($org);

        $oldDrive = LeadDrive::create([
            'organization_id' => $org->id,
            'name' => 'Old Pipeline',
            'color' => '#0d6efd',
            'is_default' => false,
        ]);

        $newDrive = LeadDrive::create([
            'organization_id' => $org->id,
            'name' => 'New Pipeline',
            'color' => '#198754',
            'is_default' => false,
        ]);

        $lead = $this->createLeadForOrganization($org, [
            'drive_id' => $oldDrive->id,
            'priority' => 'medium',
            'source' => 'website',
            'status' => 'new',
        ]);

        $controller = app(LeadController::class);
        $request = Request::create('/admin/crm/' . $lead->id, 'PUT', [
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company' => $lead->company,
            'status' => $lead->status,
            'source' => $lead->source,
            'priority' => $lead->priority,
            'assigned_to' => $lead->assigned_to,
            'drive_id' => $newDrive->id,
        ]);

        $this->actingAs($actor);
        $controller->update($request, $lead);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'drive_id' => $newDrive->id,
        ]);
    }

    private function createOrganization(string $name): Organization
    {
        return Organization::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
        ]);
    }

    private function createStaffUser(?Organization $organization): User
    {
        $user = User::factory()->create([
            'organization_id' => $organization?->id,
        ]);

        $user->assignRole('staff');

        return $user;
    }

    private function createLeadForOrganization(Organization $organization, array $overrides = []): Lead
    {
        $uuid = Str::lower((string) Str::uuid());

        return Lead::create(array_merge([
            'organization_id' => $organization->id,
            'first_name' => 'Lead ' . Str::substr($uuid, 0, 8),
            'email' => 'lead-' . $uuid . '@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ], $overrides));
    }
}
