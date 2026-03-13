<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Seed default lead statuses for all existing organizations
     * and one global set (null organization_id) for super-admin.
     */
    public function run(): void
    {
        // Global / super-admin statuses
        LeadStatus::seedDefaults(null);

        // Per-organization statuses
        Organization::all()->each(function (Organization $org) {
            LeadStatus::seedDefaults($org->id);
        });
    }
}
