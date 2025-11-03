<?php

namespace App\Console\Commands;

use App\Models\PageVisit;
use Illuminate\Console\Command;

class NormalizeCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:normalize-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize existing country data to use specific countries or "Others"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Normalizing country data...');

        // Define allowed countries
        $allowedCountries = [
            'Nigeria',
            'United States',
            'United Kingdom',
            'Ghana',
            'Kenya',
            'South Africa',
            'Egypt',
            'Morocco',
            'Local'
        ];

        // Normalize common variations
        $countryMapping = [
            'USA' => 'United States',
            'US' => 'United States',
            'America' => 'United States',
            'UK' => 'United Kingdom',
            'Britain' => 'United Kingdom',
            'Great Britain' => 'United Kingdom',
            'England' => 'United Kingdom',
        ];

        $totalUpdated = 0;
        $batchSize = 100;

        // Process all page visits in batches
        PageVisit::chunk($batchSize, function ($visits) use ($allowedCountries, $countryMapping, &$totalUpdated) {
            foreach ($visits as $visit) {
                $originalCountry = $visit->country;

                if (empty($originalCountry)) {
                    $normalizedCountry = 'Others';
                } else {
                    // Apply mapping if exists
                    $normalizedCountry = $countryMapping[$originalCountry] ?? $originalCountry;

                    // Check if the normalized country is in allowed list
                    if (!in_array($normalizedCountry, $allowedCountries)) {
                        $normalizedCountry = 'Others';
                    }
                }

                // Update if changed
                if ($visit->country !== $normalizedCountry) {
                    $visit->update(['country' => $normalizedCountry]);
                    $totalUpdated++;
                }
            }
        });

        $this->info("✅ Country normalization completed!");
        $this->info("📊 Updated {$totalUpdated} records");

        // Show country distribution
        $this->newLine();
        $this->info("📈 Current country distribution:");

        $distribution = PageVisit::selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        foreach ($distribution as $item) {
            $this->line("   {$item->country}: {$item->count} visits");
        }

        return 0;
    }
}
