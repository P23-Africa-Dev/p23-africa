<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (!function_exists('orgRoute')) {
    /**
     * Generate an org-aware admin route URL.
     *
     * For organisation users (non-admin with an org), this converts
     * route('admin.projects.index') → route('org.admin.projects.index', ['orgSlug' => $slug])
     *
     * For admin users or users without an org, it falls back to the standard admin.* route.
     *
     * @param  string  $name       The route name, e.g. 'admin.projects.index'
     * @param  mixed   $parameters Route parameters (model instances, arrays, etc.)
     * @param  bool    $absolute   Whether to generate an absolute URL
     * @return string
     */
    function orgRoute(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $user = Auth::user();

        // Only rewrite admin.* routes for org users who are not super-admins
        if (
            $user
            && $user->organization_id
            && !$user->hasRole('admin')
            && str_starts_with($name, 'admin.')
        ) {
            $orgName = 'org.' . $name;

            // Check that the org-prefixed route actually exists before using it
            if (Route::has($orgName)) {
                $slug = $user->organization?->slug;

                if ($slug) {
                    // Normalise parameters to an array so we can inject orgSlug
                    if (!is_array($parameters)) {
                        $parameters = [$parameters];
                    }

                    $parameters['orgSlug'] = $slug;

                    return route($orgName, $parameters, $absolute);
                }
            }
        }

        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('orgRedirect')) {
    /**
     * Create an org-aware redirect to a named admin route.
     *
     * Drop-in replacement for redirect()->route('admin.xxx', $params)
     * Usage: orgRedirect('admin.projects.index')
     *        orgRedirect('admin.projects.index')->with('success', 'Done!')
     *
     * @param  string  $name       The route name
     * @param  mixed   $parameters Route parameters
     * @return \Illuminate\Http\RedirectResponse
     */
    function orgRedirect(string $name, mixed $parameters = []): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to(orgRoute($name, $parameters));
    }
}
