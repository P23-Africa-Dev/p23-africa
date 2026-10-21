<?php

$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    Msurguy\Honeypot\HoneypotServiceProvider::class,
];

// Telescope is a dev dependency. Skip it when Composer was installed with --no-dev.
if (class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
