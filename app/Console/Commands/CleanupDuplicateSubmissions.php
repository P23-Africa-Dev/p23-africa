<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:cleanup-duplicates {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate contact submissions with same session_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Scanning for duplicate contact submissions...');

        // Find sessions with both draft and submitted records
        $duplicateSessions = DB::table('contact_submissions')
            ->select('session_id')
            ->whereNotNull('session_id')
            ->groupBy('session_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('session_id');

        if ($duplicateSessions->isEmpty()) {
            $this->info('No duplicate sessions found.');
            return;
        }

        $this->info("Found {$duplicateSessions->count()} sessions with duplicates:");

        $totalDeleted = 0;

        foreach ($duplicateSessions as $sessionId) {
            $records = \App\Models\ContactSubmission::where('session_id', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get();

            $this->line("\nSession: {$sessionId}");

            $submitted = $records->where('status', 'submitted')->first();
            $drafts = $records->where('status', 'draft');

            foreach ($records as $record) {
                $this->line("  ID {$record->id}: {$record->status} - {$record->created_at} - {$record->name}");
            }

            $submitted = $records->where('status', 'submitted');

            if ($submitted->count() > 0 && $drafts->count() > 0) {
                // Keep the submitted record, delete drafts
                $toDelete = $drafts->pluck('id')->toArray();

                if ($dryRun) {
                    $this->warn("  Would delete draft records: " . implode(', ', $toDelete));
                } else {
                    \App\Models\ContactSubmission::whereIn('id', $toDelete)->delete();
                    $this->info("  Deleted {$drafts->count()} draft records");
                    $totalDeleted += $drafts->count();
                }
            }

            if ($submitted->count() > 1) {
                // Multiple submitted records, keep the latest one
                $toKeep = $submitted->sortByDesc('created_at')->first();
                $toDelete = $submitted->where('id', '!=', $toKeep->id)->pluck('id')->toArray();

                if (!empty($toDelete)) {
                    if ($dryRun) {
                        $this->warn("  Would delete older submitted records: " . implode(', ', $toDelete));
                    } else {
                        \App\Models\ContactSubmission::whereIn('id', $toDelete)->delete();
                        $this->info("  Deleted " . count($toDelete) . " older submitted records, kept ID {$toKeep->id}");
                        $totalDeleted += count($toDelete);
                    }
                }
            } elseif ($drafts->count() > 1) {
                // Multiple drafts, keep the latest one
                $toKeep = $drafts->sortByDesc('created_at')->first();
                $toDelete = $drafts->where('id', '!=', $toKeep->id)->pluck('id')->toArray();

                if (!empty($toDelete)) {
                    if ($dryRun) {
                        $this->warn("  Would delete older draft records: " . implode(', ', $toDelete));
                    } else {
                        \App\Models\ContactSubmission::whereIn('id', $toDelete)->delete();
                        $this->info("  Deleted " . count($toDelete) . " older draft records, kept ID {$toKeep->id}");
                        $totalDeleted += count($toDelete);
                    }
                }
            }
        }

        if ($dryRun) {
            $this->info("\nDry run completed. Use without --dry-run to actually delete records.");
        } else {
            $this->info("\nCleanup completed. Deleted {$totalDeleted} duplicate records.");
        }
    }
}
