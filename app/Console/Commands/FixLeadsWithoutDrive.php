<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\LeadDrive;
use Illuminate\Console\Command;

class FixLeadsWithoutDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:fix-drives 
                            {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign all leads without a drive to the Lead Bank drive';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        // Get Lead Bank drive
        $leadBankDrive = LeadDrive::getDefault();
        if (!$leadBankDrive) {
            // Fallback: find Lead Bank by slug
            $leadBankDrive = LeadDrive::where('slug', 'lead-bank')->first();
        }

        if (!$leadBankDrive) {
            $this->error('Lead Bank drive not found! Please create a default drive first.');
            return 1;
        }

        $this->info("Using drive: {$leadBankDrive->name} (ID: {$leadBankDrive->id})");

        // Find all leads without a drive
        $leadsWithoutDrive = Lead::whereNull('drive_id')->get();

        if ($leadsWithoutDrive->isEmpty()) {
            $this->info('✅ All leads already have a drive assigned.');
            return 0;
        }

        $count = $leadsWithoutDrive->count();
        $this->warn("Found {$count} lead(s) without a drive.");

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made.');
            $this->table(
                ['ID', 'Name', 'Email', 'Company', 'Source', 'Status'],
                $leadsWithoutDrive->map(function ($lead) {
                    return [
                        $lead->id,
                        $lead->full_name,
                        $lead->email,
                        $lead->company ?? '-',
                        $lead->source_label,
                        $lead->status_label,
                    ];
                })->toArray()
            );
            $this->info("Would assign {$count} lead(s) to {$leadBankDrive->name}.");
            return 0;
        }

        // Assign leads to Lead Bank
        if ($this->confirm("Assign {$count} lead(s) to {$leadBankDrive->name}?", true)) {
            $updated = Lead::whereNull('drive_id')->update(['drive_id' => $leadBankDrive->id]);

            $this->info("✅ Successfully assigned {$updated} lead(s) to {$leadBankDrive->name}.");
            return 0;
        }

        $this->info('Operation cancelled.');
        return 0;
    }
}
