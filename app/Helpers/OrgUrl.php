<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class OrgUrl
{
    /**
     * Get the org-prefixed admin base path for the current authenticated user.
     * Returns e.g. "/acme-corp/admin" for org users, or "/admin" for non-org / admin users.
     */
    public static function adminBase(): string
    {
        $user = Auth::user();

        if ($user && $user->organization_id && !$user->hasRole('admin')) {
            $org = $user->organization;
            if ($org && $org->slug) {
                return '/' . $org->slug . '/admin';
            }
        }

        return '/admin';
    }

    /**
     * Generate an org-aware admin URL.
     * Usage: OrgUrl::to('/dashboard') => "/acme-corp/admin/dashboard" or "/admin/dashboard"
     */
    public static function to(string $path = ''): string
    {
        return url(static::adminBase() . ($path ? '/' . ltrim($path, '/') : ''));
    }

    /**
     * Get the org slug for the current user (or null).
     */
    public static function slug(): ?string
    {
        $user = Auth::user();

        if ($user && $user->organization_id && !$user->hasRole('admin')) {
            $org = $user->organization;
            return $org ? $org->slug : null;
        }

        return null;
    }

    /**
     * Get the login URL for a given org slug (or default).
     */
    public static function loginUrl(?string $orgSlug = null): string
    {
        if ($orgSlug) {
            return url('/' . $orgSlug . '/admin/login');
        }

        return route('login');
    }
}
