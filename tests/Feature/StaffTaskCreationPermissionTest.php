<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\TaskController;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StaffTaskCreationPermissionTest extends TestCase
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

    public function test_staff_can_create_task_within_own_organization_and_assign_staff(): void
    {
        $org = $this->createOrganization('Org A');

        $staffCreator = $this->createUserWithRole('staff', $org->id);
        $staffAssignee = $this->createUserWithRole('staff', $org->id);

        $project = $this->createProject($staffCreator->id, $org->id, 'Org A Project');

        $this->actingAs($staffCreator);

        app(TaskController::class)->store(Request::create('/admin/tasks', 'POST', [
            'title' => 'Staff Created Task',
            'description' => 'Created by staff user',
            'project_id' => $project->id,
            'status' => 'todo',
            'priority' => 'medium',
            'assignees' => [$staffAssignee->id],
        ]));

        $task = Task::where('title', 'Staff Created Task')->first();

        $this->assertNotNull($task);
        $this->assertSame($org->id, $task->organization_id);
        $this->assertSame($staffCreator->id, $task->created_by);
        $this->assertTrue($task->assignees()->where('users.id', $staffAssignee->id)->exists());
    }

    public function test_staff_cannot_create_task_in_foreign_organization_project(): void
    {
        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $staffCreator = $this->createUserWithRole('staff', $orgA->id);
        $projectInOrgB = $this->createProject($staffCreator->id, $orgB->id, 'Org B Project');

        $this->actingAs($staffCreator);

        try {
            app(TaskController::class)->store(Request::create('/admin/tasks', 'POST', [
                'title' => 'Forbidden Task',
                'project_id' => $projectInOrgB->id,
                'status' => 'todo',
                'priority' => 'medium',
            ]));

            $this->fail('Expected staff task creation to be forbidden for foreign organization projects.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }

        $this->assertSame(0, Task::count());
    }

    public function test_staff_cannot_assign_supervisor_when_creating_task(): void
    {
        $org = $this->createOrganization('Org A');

        $staffCreator = $this->createUserWithRole('staff', $org->id);
        $supervisor = $this->createUserWithRole('supervisor', $org->id);

        $project = $this->createProject($staffCreator->id, $org->id, 'Org A Project');

        $this->actingAs($staffCreator);

        try {
            app(TaskController::class)->store(Request::create('/admin/tasks', 'POST', [
                'title' => 'Task With Invalid Assignee',
                'project_id' => $project->id,
                'status' => 'todo',
                'priority' => 'medium',
                'assignees' => [$supervisor->id],
            ]));

            $this->fail('Expected staff assignment to supervisor to be forbidden.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }

        $this->assertSame(0, Task::count());
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
}
