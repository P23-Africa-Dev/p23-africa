<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function viewLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect($this->getDashboardUrl(Auth::user(), $request));
        }

        // If accessing via org-prefixed route, resolve the org for branding
        // orgSlug is stored on request attributes by ResolveOrganization middleware
        $orgSlug = $request->attributes->get('orgSlug') ?? $request->route('orgSlug');
        $organization = $request->attributes->get('organization');
        if ($orgSlug && !$organization) {
            $organization = Organization::where('slug', $orgSlug)->first();
        }

        return view('auth.login', [
            'organization' => $organization,
            'orgSlug' => $orgSlug,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $orgSlug = $request->attributes->get('orgSlug') ?? $request->route('orgSlug');
        $usersQuery = User::where('email', $request->email);

        if ($orgSlug) {
            $organization = $request->attributes->get('organization')
                ?? Organization::where('slug', $orgSlug)->first();

            if (!$organization) {
                return back()->withErrors([
                    'email' => 'Invalid organization login URL.',
                ]);
            }

            $usersQuery->where('organization_id', $organization->id);
        }

        $users = $usersQuery->limit(2)->get();

        // If the same email exists in multiple organizations, user must log in via org URL.
        if (!$orgSlug && $users->count() > 1) {
            return back()->withErrors([
                'email' => 'This email is used in multiple organizations. Please use your organization login URL.',
            ]);
        }

        $user = $users->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            $intendedUrl = $this->getDashboardUrl($user, $request);
            return redirect()->intended($intendedUrl);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Determine the correct dashboard URL based on user's organization.
     */
    protected function getDashboardUrl($user, Request $request): string
    {
        // If the login was via an org-prefixed URL, use that slug
        // orgSlug is stored on request attributes by ResolveOrganization middleware
        $orgSlug = $request->attributes->get('orgSlug') ?? $request->route('orgSlug');

        // If not from an org URL, check if the user belongs to an org
        if (!$orgSlug && $user->organization_id && !$user->hasRole('admin')) {
            $org = $user->organization;
            if ($org && $org->slug) {
                $orgSlug = $org->slug;
            }
        }

        if ($orgSlug) {
            return '/' . $orgSlug . '/admin/dashboard';
        }

        return '/admin/dashboard';
    }
}
