<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\User;
use App\Services\LegacyContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LegacyContextServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('admin');
        Role::findOrCreate('supervisor');
        Role::findOrCreate('staff');
    }

    public function test_is_legacy_user_when_organization_id_is_null(): void
    {
        $user = User::factory()->create(['organization_id' => null]);

        $service = app(LegacyContextService::class);

        $this->assertTrue($service->isLegacyUser($user));
    }

    public function test_has_legacy_whatsapp_access_for_legacy_admin(): void
    {
        $user = User::factory()->create(['organization_id' => null]);
        $user->assignRole('admin');

        $service = app(LegacyContextService::class);

        $this->assertTrue($service->isLegacyAdmin($user));
        $this->assertTrue($service->hasLegacyWhatsAppAccess($user));
    }

    public function test_has_legacy_whatsapp_access_for_legacy_supervisor(): void
    {
        $user = User::factory()->create(['organization_id' => null]);
        $user->assignRole('supervisor');

        $service = app(LegacyContextService::class);

        $this->assertTrue($service->isLegacySupervisor($user));
        $this->assertTrue($service->hasLegacyWhatsAppAccess($user));
    }

    public function test_denies_non_legacy_admin(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Tenant One',
            'slug' => 'tenant-one',
        ]);

        $user = User::factory()->create(['organization_id' => $organization->id]);
        $user->assignRole('admin');

        $service = app(LegacyContextService::class);

        $this->assertFalse($service->isLegacyUser($user));
        $this->assertFalse($service->hasLegacyWhatsAppAccess($user));
    }

    public function test_denies_non_legacy_supervisor(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Tenant Two',
            'slug' => 'tenant-two',
        ]);

        $user = User::factory()->create(['organization_id' => $organization->id]);
        $user->assignRole('supervisor');

        $service = app(LegacyContextService::class);

        $this->assertFalse($service->isLegacyUser($user));
        $this->assertFalse($service->hasLegacyWhatsAppAccess($user));
    }

    public function test_denies_legacy_user_without_allowed_role(): void
    {
        $user = User::factory()->create(['organization_id' => null]);
        $user->assignRole('staff');

        $service = app(LegacyContextService::class);

        $this->assertTrue($service->isLegacyUser($user));
        $this->assertFalse($service->hasLegacyWhatsAppAccess($user));
    }
}
