<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\StaffController;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class StaffEmailOrganizationScopeTest extends TestCase
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

    public function test_staff_creation_rejects_duplicate_email_in_same_organization_with_clear_message(): void
    {
        Mail::fake();

        $orgA = $this->createOrganization('Org A');
        $admin = $this->createAdmin($orgA->id);

        User::factory()->create([
            'organization_id' => $orgA->id,
            'email' => 'shared@example.com',
        ])->assignRole('staff');

        $request = Request::create('/admin/staff', 'POST', [
            'name' => 'Duplicate Same Org',
            'email' => 'shared@example.com',
            'role' => 'staff',
            'organization_id' => $orgA->id,
        ]);

        $this->actingAs($admin);

        try {
            app(StaffController::class)->store($request);
            $this->fail('Expected validation to fail for same-organization duplicate email.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey('email', $errors);
            $this->assertContains('This staff member already exists in your organization.', $errors['email']);
        }

        $this->assertSame(1, User::where('organization_id', $orgA->id)->where('email', 'shared@example.com')->count());
    }

    public function test_staff_creation_allows_same_email_in_different_organization(): void
    {
        Mail::fake();

        $orgA = $this->createOrganization('Org A');
        $orgB = $this->createOrganization('Org B');

        $admin = $this->createAdmin($orgA->id);

        User::factory()->create([
            'organization_id' => $orgB->id,
            'email' => 'shared@example.com',
        ])->assignRole('staff');

        $request = Request::create('/admin/staff', 'POST', [
            'name' => 'New Org A Staff',
            'email' => 'shared@example.com',
            'role' => 'staff',
            'organization_id' => $orgA->id,
        ]);

        $this->actingAs($admin);

        app(StaffController::class)->store($request);

        $this->assertSame(2, User::where('email', 'shared@example.com')->count());
        $this->assertSame(1, User::where('organization_id', $orgA->id)->where('email', 'shared@example.com')->count());
        $this->assertSame(1, User::where('organization_id', $orgB->id)->where('email', 'shared@example.com')->count());
    }

    private function createOrganization(string $name): Organization
    {
        return Organization::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
        ]);
    }

    private function createAdmin(?int $organizationId): User
    {
        $admin = User::factory()->create([
            'organization_id' => $organizationId,
        ]);

        $admin->assignRole('admin');

        return $admin;
    }
}
