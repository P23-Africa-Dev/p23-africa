<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function viewLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect($this->getDashboardUrl(Auth::user(), $request));
        }

        // If accessing via org-prefixed route, resolve the org for branding
        $orgSlug = $request->route('orgSlug');
        $organization = null;
        if ($orgSlug) {
            $organization = Organization::where('slug', $orgSlug)->first();
        }

        return view('auth.login', [
            'organization' => $organization,
            'orgSlug' => $orgSlug,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
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
        $orgSlug = $request->route('orgSlug');

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
