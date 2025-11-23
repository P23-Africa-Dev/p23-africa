<?php

namespace App\Console\Commands;

use App\Models\PageVisit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackfillCountryDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:backfill-countries {--limit=100 : Number of records to process per run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill country data for existing page visits with "Others" or null countries using GeoIP lookup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');

        $this->info("Starting country data backfill (processing {$limit} records)...");

        // Get records that need updating
        $visits = PageVisit::where(function ($query) {
            $query->where('country', 'Others')
                ->orWhere('country', 'Unknown')
                ->orWhereNull('country');
        })
            ->whereNotNull('ip_address')
            ->where('ip_address', '!=', '127.0.0.1')
            ->where('ip_address', 'not like', '192.168.%')
            ->where('ip_address', 'not like', '10.%')
            ->limit($limit)
            ->get();

        if ($visits->isEmpty()) {
            $this->info('No records found that need updating!');
            return Command::SUCCESS;
        }

        $this->info("Found {$visits->count()} records to update");

        $progressBar = $this->output->createProgressBar($visits->count());
        $progressBar->start();

        $updated = 0;
        $failed = 0;
        $rateLimit = 0;

        foreach ($visits as $visit) {
            try {
                // Rate limiting: 45 requests per minute for ip-api.com free tier
                if ($rateLimit >= 45) {
                    $this->newLine();
                    $this->info('Rate limit reached, waiting 60 seconds...');
                    sleep(60);
                    $rateLimit = 0;
                }

                $country = $this->getCountryFromIP($visit->ip_address);

                if ($country && $country !== 'Unknown' && $country !== 'Others') {
                    $city = $this->getCityFromIP($visit->ip_address);

                    $visit->update([
                        'country' => $this->normalizeCountry($country),
                        'city' => $city ?? $visit->city
                    ]);

                    $updated++;
                    $rateLimit += 2; // We made 2 API calls (country + city)
                } else {
                    $failed++;
                }

                // Small delay to respect rate limits
                usleep(50000); // 50ms delay between requests

            } catch (\Exception $e) {
                Log::error('Failed to update country for visit ID ' . $visit->id . ': ' . $e->getMessage());
                $failed++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display summary
        $this->info("Backfill completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Updated Successfully', $updated],
                ['Failed/Unchanged', $failed],
                ['Total Processed', $visits->count()]
            ]
        );

        // Check if there are more records to process
        $remaining = PageVisit::where(function ($query) {
            $query->where('country', 'Others')
                ->orWhere('country', 'Unknown')
                ->orWhereNull('country');
        })
            ->whereNotNull('ip_address')
            ->where('ip_address', '!=', '127.0.0.1')
            ->where('ip_address', 'not like', '192.168.%')
            ->where('ip_address', 'not like', '10.%')
            ->count();

        if ($remaining > 0) {
            $this->newLine();
            $this->info("There are still {$remaining} records that need updating.");
            $this->info("Run this command again to continue: php artisan analytics:backfill-countries");
        } else {
            $this->info("All records have been processed! 🎉");
        }

        return Command::SUCCESS;
    }

    private function getCountryFromIP($ipAddress)
    {
        // Check cache first
        $cacheKey = 'geoip_country_' . md5($ipAddress);

        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ipAddress}?fields=status,country");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (isset($data['status']) && $data['status'] === 'success' && isset($data['country'])) {
                    $country = $data['country'];
                    cache()->put($cacheKey, $country, 86400); // Cache for 24 hours
                    return $country;
                }
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getCityFromIP($ipAddress)
    {
        $cacheKey = 'geoip_city_' . md5($ipAddress);

        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ipAddress}?fields=status,city");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (isset($data['status']) && $data['status'] === 'success' && isset($data['city'])) {
                    $city = $data['city'];
                    cache()->put($cacheKey, $city, 86400);
                    return $city;
                }
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function normalizeCountry($country)
    {
        if (empty($country)) {
            return 'Unknown';
        }

        $countryMapping = [
            'USA' => 'United States',
            'US' => 'United States',
            'America' => 'United States',
            'United States of America' => 'United States',
            'UK' => 'United Kingdom',
            'Britain' => 'United Kingdom',
            'Great Britain' => 'United Kingdom',
            'England' => 'United Kingdom',
            'Scotland' => 'United Kingdom',
            'Wales' => 'United Kingdom',
            'Northern Ireland' => 'United Kingdom',
            'UAE' => 'United Arab Emirates',
            'South Korea' => 'Korea, Republic of',
            'North Korea' => 'Korea, Democratic People\'s Republic of',
            'Congo' => 'Congo, Republic of the',
            'DRC' => 'Congo, Democratic Republic of the',
            'Ivory Coast' => 'Côte d\'Ivoire',
        ];

        return $countryMapping[$country] ?? $country;
    }
}
