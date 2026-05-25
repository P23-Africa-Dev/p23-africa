<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureLegacyWhatsAppAccess;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class WhatsAppLegacyAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('admin');
        Role::findOrCreate('supervisor');
    }

    public function test_legacy_admin_can_access_whatsapp_routes(): void
    {
        $user = User::factory()->create([
            'organization_id' => null,
        ]);
        $user->assignRole('admin');

        $request = Request::create('/admin/whatsapp/conversations', 'GET');
        $request->setUserResolver(fn() => $user);

        $response = app(EnsureLegacyWhatsAppAccess::class)->handle($request, fn() => new Response('OK', 200));

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_legacy_supervisor_can_access_whatsapp_routes(): void
    {
        $user = User::factory()->create([
            'organization_id' => null,
        ]);
        $user->assignRole('supervisor');

        $request = Request::create('/admin/whatsapp/conversations', 'GET');
        $request->setUserResolver(fn() => $user);

        $response = app(EnsureLegacyWhatsAppAccess::class)->handle($request, fn() => new Response('OK', 200));

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_org_admin_is_denied_whatsapp_access(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Acme Org',
            'slug' => 'acme-org',
        ]);

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $user->assignRole('admin');

        $request = Request::create('/admin/whatsapp/conversations', 'GET');
        $request->setUserResolver(fn() => $user);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Forbidden');

        app(EnsureLegacyWhatsAppAccess::class)->handle($request, fn() => new Response('OK', 200));
    }

    public function test_org_supervisor_is_denied_whatsapp_access(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Beta Org',
            'slug' => 'beta-org',
        ]);

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $user->assignRole('supervisor');

        $request = Request::create('/admin/whatsapp/conversations', 'GET');
        $request->setUserResolver(fn() => $user);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Forbidden');

        app(EnsureLegacyWhatsAppAccess::class)->handle($request, fn() => new Response('OK', 200));
    }
}
