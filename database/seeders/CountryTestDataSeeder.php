<?php

namespace Database\Seeders;

use App\Models\PageVisit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CountryTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Adding sample data with new country structure...');

        $routes = ['homepage', 'Contact_Page', 'About_Page', 'Sales_Page'];
        $deviceTypes = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];

        // Sample data for the specific countries and "Others"
        $countryData = [
            'Nigeria' => ['Lagos', 'Abuja', 'Port Harcourt'],
            'United States' => ['New York', 'Los Angeles', 'Chicago'],
            'United Kingdom' => ['London', 'Manchester', 'Birmingham'],
            'Ghana' => ['Accra', 'Kumasi', 'Tamale'],
            'Kenya' => ['Nairobi', 'Mombasa', 'Kisumu'],
            'South Africa' => ['Cape Town', 'Johannesburg', 'Durban'],
            'Egypt' => ['Cairo', 'Alexandria', 'Giza'],
            'Morocco' => ['Casablanca', 'Rabat', 'Marrakech'],
            'Others' => ['Unknown', 'Various', 'International'],
        ];

        // Create 50 sample visits distributed across countries
        for ($i = 0; $i < 50; $i++) {
            $country = array_rand($countryData);
            $cities = $countryData[$country];
            $city = $cities[array_rand($cities)];

            PageVisit::create([
                'route_name' => $routes[array_rand($routes)],
                'page_title' => ucwords(str_replace('_', ' ', $routes[array_rand($routes)])),
                'page_url' => 'https://p23africa.com/test',
                'device_type' => $deviceTypes[array_rand($deviceTypes)],
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => $this->generateUserAgent(),
                'browser' => $browsers[array_rand($browsers)],
                'browser_version' => '119.0',
                'platform' => 'Windows',
                'platform_version' => '10',
                'referrer' => rand(0, 3) === 0 ? 'https://google.com' : null,
                'session_id' => 'sess_' . uniqid(),
                'country' => $country,
                'city' => $city,
                'timezone' => $this->getTimezone($country),
                'is_unique_visitor' => rand(0, 1) === 1,
                'screen_width' => rand(1366, 1920),
                'screen_height' => rand(768, 1080),
                'time_on_page' => rand(10, 300),
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now()->subDays(rand(0, 7)),
            ]);
        }

        $this->command->info('✅ Added 50 sample visits with new country structure');

        // Show distribution
        $distribution = PageVisit::selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        $this->command->info('📊 Current country distribution:');
        foreach ($distribution as $item) {
            $this->command->line("   {$item->country}: {$item->count} visits");
        }
    }

    private function generateRandomIP()
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function generateUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
        ];

        return $userAgents[array_rand($userAgents)];
    }

    private function getTimezone($country)
    {
        $timezones = [
            'Nigeria' => 'Africa/Lagos',
            'United States' => 'America/New_York',
            'United Kingdom' => 'Europe/London',
            'Ghana' => 'Africa/Accra',
            'Kenya' => 'Africa/Nairobi',
            'South Africa' => 'Africa/Johannesburg',
            'Egypt' => 'Africa/Cairo',
            'Morocco' => 'Africa/Casablanca',
            'Others' => 'UTC',
        ];

        return $timezones[$country] ?? 'UTC';
    }
}
