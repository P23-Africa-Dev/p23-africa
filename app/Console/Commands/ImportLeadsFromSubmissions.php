<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\ContactSubmission;
use App\Models\ConsultationApplication;

class ImportLeadsFromSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:import-leads 
                            {--source=all : Source to import from (all, contact, consultation)}
                            {--status=submitted : Only import submissions with this status}
                            {--dry-run : Show what would be imported without actually importing}
                            {--force : Import even if lead with same email already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing contact submissions and consultation applications as CRM leads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $status = $this->option('status');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('Starting lead import...');
        $this->newLine();

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        // Import from Contact Submissions
        if ($source === 'all' || $source === 'contact') {
            $this->info('📧 Importing from Contact Submissions...');

            $query = ContactSubmission::where('status', $status);
            $submissions = $query->get();

            $this->info("Found {$submissions->count()} contact submissions with status '{$status}'");

            $bar = $this->output->createProgressBar($submissions->count());
            $bar->start();

            foreach ($submissions as $submission) {
                try {
                    // Check if lead already exists with same email
                    $existingLead = Lead::where('email', $submission->email)->first();

                    if ($existingLead && !$force) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }

                    if (!$dryRun) {
                        Lead::convertFromContactSubmission($submission);
                    }

                    $imported++;
                } catch (\Exception $e) {
                    $errors++;
                    $this->newLine();
                    $this->error("Error importing contact submission #{$submission->id}: {$e->getMessage()}");
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
        }

        // Import from Consultation Applications
        if ($source === 'all' || $source === 'consultation') {
            $this->info('📅 Importing from Consultation Applications...');

            // Map common status names
            $consultationStatus = $status;
            if ($status === 'submitted') {
                $consultationStatus = 'pending'; // Consultations use 'pending' instead of 'submitted'
            }

            $query = ConsultationApplication::where('status', '!=', 'draft');
            if ($consultationStatus !== 'all') {
                $query->where('status', $consultationStatus);
            }

            $applications = $query->get();

            $this->info("Found {$applications->count()} consultation applications (excluding drafts)");

            $bar = $this->output->createProgressBar($applications->count());
            $bar->start();

            foreach ($applications as $application) {
                try {
                    // Check if lead already exists with same email
                    $existingLead = Lead::where('email', $application->email)->first();

                    if ($existingLead && !$force) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }

                    if (!$dryRun) {
                        Lead::convertFromConsultation($application);
                    }

                    $imported++;
                } catch (\Exception $e) {
                    $errors++;
                    $this->newLine();
                    $this->error("Error importing consultation #{$application->id}: {$e->getMessage()}");
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
        }

        // Summary
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('           IMPORT SUMMARY              ');
        $this->info('═══════════════════════════════════════');

        if ($dryRun) {
            $this->warn("Would import: {$imported} leads");
        } else {
            $this->info("✅ Imported: {$imported} leads");
        }

        $this->info("⏭️  Skipped (duplicates): {$skipped}");

        if ($errors > 0) {
            $this->error("❌ Errors: {$errors}");
        }

        $this->newLine();

        if ($dryRun && $imported > 0) {
            $this->info('Run without --dry-run to actually import the leads.');
        }

        return Command::SUCCESS;
    }
}
