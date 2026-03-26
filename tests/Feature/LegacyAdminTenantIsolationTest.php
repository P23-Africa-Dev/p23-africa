<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\TaskController;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LegacyAdminTenantIsolationTest extends TestCase
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

    public function test_legacy_admin_crm_index_only_returns_legacy_context_leads(): void
    {
        $orgA = $this->createOrganization('Org A');

        $legacyAdmin = $this->createAdminUser(null);

        $legacyLead = $this->createLead(null, 'legacy-lead@example.com');
        $this->createLead($orgA->id, 'org-a-lead@example.com');

        $this->actingAs($legacyAdmin);

        $response = app(LeadController::class)->index(Request::create('/admin/crm', 'GET'));

        $leads = $response->getData()['leads']->getCollection();

        $this->assertCount(1, $leads);
        $this->assertTrue($leads->contains(fn ($lead) => $lead->id === $legacyLead->id));
    }

    public function test_org_admin_crm_index_only_returns_same_organization_leads(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $orgAdmin = $this->createAdminUser($orgA->id);

        $ownLead = $this->createLead($orgA->id, 'own-org-lead@example.com');
        $this->createLead($orgB->id, 'other-org-lead@example.com');
        $this->createLead(null, 'legacy-context-lead@example.com');

        $this->actingAs($orgAdmin);

        $response = app(LeadController::class)->index(Request::create('/admin/crm', 'GET'));

        $leads = $response->getData()['leads']->getCollection();

        $this->assertCount(1, $leads);
        $this->assertTrue($leads->contains(fn ($lead) => $lead->id === $ownLead->id));
    }

    public function test_project_policy_denies_legacy_admin_access_to_foreign_organization_project(): void
    {
        $orgA = $this->createOrganization('Org A');

        $legacyAdmin = $this->createAdminUser(null);
        $orgAdmin = $this->createAdminUser($orgA->id);

        $legacyProject = $this->createProject($legacyAdmin->id, null, 'Legacy Project');
        $foreignProject = $this->createProject($orgAdmin->id, $orgA->id, 'Org Project');

        $policy = new ProjectPolicy();

        $this->assertTrue($policy->view($legacyAdmin, $legacyProject));
        $this->assertFalse($policy->view($legacyAdmin, $foreignProject));
    }

    public function test_legacy_admin_cannot_reorder_tasks_from_other_organizations(): void
    {
        $orgA = $this->createOrganization('Org A');

        $legacyAdmin = $this->createAdminUser(null);
        $orgAdmin = $this->createAdminUser($orgA->id);

        $legacyProject = $this->createProject($legacyAdmin->id, null, 'Legacy Project');
        $orgProject = $this->createProject($orgAdmin->id, $orgA->id, 'Org Project');

        $legacyTask = $this->createTask($legacyProject->id, $legacyAdmin->id, null, 1);
        $foreignTask = $this->createTask($orgProject->id, $orgAdmin->id, $orgA->id, 1);

        $this->actingAs($legacyAdmin);

        app(TaskController::class)->updateOrder(Request::create('/admin/tasks/order', 'POST', [
            'tasks' => [
                ['id' => $legacyTask->id, 'order' => 2, 'status' => 'todo'],
                ['id' => $foreignTask->id, 'order' => 99, 'status' => 'todo'],
            ],
        ]));

        $this->assertSame(2, $legacyTask->fresh()->order);
        $this->assertSame(1, $foreignTask->fresh()->order);
    }

    private function createOrganization(string $name): Organization
    {
        return Organization::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
        ]);
    }

    private function createAdminUser(?int $organizationId): User
    {
        $user = User::factory()->create([
            'organization_id' => $organizationId,
        ]);

        $user->assignRole('admin');

        return $user;
    }

    private function createLead(?int $organizationId, string $email): Lead
    {
        return Lead::create([
            'organization_id' => $organizationId,
            'first_name' => 'Lead',
            'email' => $email,
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ]);
    }

    private function createProject(int $creatorId, ?int $organizationId, string $name): Project
    {
        return Project::create([
            'name' => $name,
            'status' => 'planning',
            'priority' => 'medium',
            'created_by' => $creatorId,
            'organization_id' => $organizationId,
        ]);
    }

    private function createTask(int $projectId, int $creatorId, ?int $organizationId, int $order): Task
    {
        return Task::create([
            'title' => 'Task ' . Str::random(6),
            'project_id' => $projectId,
            'status' => 'todo',
            'priority' => 'medium',
            'created_by' => $creatorId,
            'organization_id' => $organizationId,
            'order' => $order,
        ]);
    }
}
