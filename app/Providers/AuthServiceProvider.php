<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
    ];

    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //
    // }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::define('access-dashboard', function (User $user) {
        //     return !$user->isSuspended();
        // });

        // Define Gates (Permissions)
        Gate::define('manage_events', function ($user) {
            return $user->hasPermission('manage_events');
        });

        Gate::define('resource_hub', function ($user) {
            return $user->hasPermission('resource_hub');
        });

        Gate::define('view_students', function ($user) {
            return $user->hasPermission('view_students');
        });
    }
}
