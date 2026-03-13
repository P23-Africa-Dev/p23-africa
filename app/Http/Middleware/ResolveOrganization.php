<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ResolveOrganization
{
    /**
     * Resolve the organization from the {orgSlug} route parameter.
     * Shares the organization with all views and stores it on the request.
     */
    public function handle(Request $request, Closure $next)
    {
        $orgSlug = $request->route('orgSlug');

        if ($orgSlug) {
            $organization = Organization::where('slug', $orgSlug)->first();

            if (!$organization) {
                abort(404, 'Organization not found.');
            }

            // Store on the request so controllers/views can access it
            $request->attributes->set('organization', $organization);
            $request->attributes->set('orgSlug', $orgSlug);

            // Share with all views
            View::share('currentOrg', $organization);
            View::share('orgSlug', $orgSlug);
            View::share('orgPrefix', '/' . $orgSlug);

            // Remove orgSlug from route parameters so it won't be passed
            // as a positional argument to controller methods.
            // Without this, controller methods like show(Task $task)
            // would receive show('org-slug', Task) causing a TypeError.
            $request->route()->forgetParameter('orgSlug');
        } else {
            View::share('currentOrg', null);
            View::share('orgSlug', null);
            View::share('orgPrefix', '');
        }

        return $next($request);
    }
}
