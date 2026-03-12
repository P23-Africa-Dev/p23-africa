<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Check if user can access the project based on organization.
     */
    protected function isInSameOrg(User $user, Project $project): bool
    {
        // Admin bypasses org checks
        if ($user->hasRole('admin')) {
            return true;
        }

        // Org user can only access their org's projects
        if ($user->organization_id) {
            return $project->organization_id === $user->organization_id;
        }

        // Non-org user can only access global (null org) projects
        return is_null($project->organization_id);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'supervisor', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        if (!$this->isInSameOrg($user, $project)) {
            return false;
        }

        // Admin and Supervisor can view all projects in their org
        if ($user->hasRole(['admin', 'supervisor'])) {
            return true;
        }

        // Staff can view projects where they have assigned tasks
        return $project->tasks()->whereHas('assignees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if (!$this->isInSameOrg($user, $project)) {
            return false;
        }

        return $user->hasRole(['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if (!$this->isInSameOrg($user, $project)) {
            return false;
        }

        return $user->hasRole(['admin', 'supervisor']);
    }
}
