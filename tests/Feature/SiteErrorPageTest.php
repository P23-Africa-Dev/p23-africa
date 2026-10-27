<?php

namespace Tests\Feature;

use App\Models\SiteError;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SiteErrorPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => true, 'app.url' => 'http://localhost']);
        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin');
    }

    public function test_a_server_error_shows_the_calm_page_and_stores_the_details(): void
    {
        Route::middleware('web')->get('/_site-error-probe', function () {
            throw new RuntimeException('database password leaked');
        });

        $response = $this->get('/_site-error-probe?token=secret-token&page=2');

        $response->assertStatus(500);
        $response->assertSee("We'll be right back", false);
        $response->assertSee('Please try again in a few minutes', false);
        $response->assertDontSee('RuntimeException');
        $response->assertDontSee('database password leaked');
        $response->assertDontSee('secret-token');
        $response->assertDontSee('vendor/laravel/framework');

        $error = SiteError::query()->first();
        $this->assertNotNull($error);
        $this->assertSame('/_site-error-probe', $error->path);
        $this->assertSame(RuntimeException::class, $error->exception_class);
        $this->assertStringContainsString('database password leaked', $error->message);
        $this->assertStringNotContainsString('secret-token', (string) $error->query);
        $this->assertStringContainsString('page=2', (string) $error->query);
    }

    public function test_a_json_error_does_not_include_the_exception(): void
    {
        Route::get('/_site-error-json', function () {
            throw new RuntimeException('hidden from the client');
        });

        $this->getJson('/_site-error-json')
            ->assertStatus(500)
            ->assertExactJson([
                'message' => 'Something went wrong. Please try again in a few minutes.',
            ]);
    }

    public function test_a_missing_page_stays_not_found_and_is_not_stored(): void
    {
        $this->get('/this-page-does-not-exist-at-all')->assertNotFound();
        $this->assertSame(0, SiteError::query()->count());
    }

    public function test_an_admin_can_open_a_stored_error(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $error = SiteError::query()->create([
            'method' => 'GET',
            'path' => '/services',
            'status' => 500,
            'exception_class' => RuntimeException::class,
            'message' => 'Route was missing',
            'file' => 'routes/web.php',
            'line' => 10,
            'trace' => '#0 routes/web.php:10',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.site-errors.show', $error))
            ->assertOk()
            ->assertSee('Route was missing')
            ->assertSee('routes/web.php');
    }
}
