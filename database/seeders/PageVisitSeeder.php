<?php

namespace Database\Seeders;

use App\Models\PageVisit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PageVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            'homepage',
            'Contact_Page',
            'About_Page',
            'Service_Page',
            'Sales_Page',
            'Quiz_Page',
            'NOEL_Form_Page',
            'Quiz_Start_Page',
            'student',
            'resource-hub',
            'event'
        ];

        $deviceTypes = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];

        // Updated countries with United States, United Kingdom, and Others
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

        // Generate data for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);

            // Generate 10-50 visits per day (more on weekdays)
            $visitsCount = $date->isWeekend() ? rand(10, 25) : rand(20, 50);

            for ($j = 0; $j < $visitsCount; $j++) {
                $route = $routes[array_rand($routes)];
                $deviceType = $deviceTypes[array_rand($deviceTypes)];
                $browser = $browsers[array_rand($browsers)];

                // Select a random country and corresponding city
                $country = array_rand($countryData);
                $cities = $countryData[$country];
                $city = $cities[array_rand($cities)];

                PageVisit::create([
                    'route_name' => $route,
                    'page_title' => $this->getPageTitle($route),
                    'page_url' => 'https://p23africa.com/' . str_replace('_', '/', strtolower($route)),
                    'device_type' => $deviceType,
                    'ip_address' => $this->generateRandomIP(),
                    'user_agent' => $this->generateUserAgent($browser, $deviceType),
                    'browser' => $browser,
                    'browser_version' => $this->generateBrowserVersion($browser),
                    'platform' => $deviceType === 'mobile' ? 'Mobile' : 'Windows',
                    'platform_version' => $deviceType === 'mobile' ? 'iOS 15' : 'Windows 10',
                    'referrer' => rand(0, 3) === 0 ? 'https://google.com' : null,
                    'session_id' => 'sess_' . uniqid(),
                    'country' => $country,
                    'city' => $city,
                    'timezone' => $this->getTimezone($country),
                    'is_unique_visitor' => rand(0, 1) === 1,
                    'screen_width' => $this->getScreenWidth($deviceType),
                    'screen_height' => $this->getScreenHeight($deviceType),
                    'time_on_page' => rand(10, 300), // 10 seconds to 5 minutes
                    'created_at' => $date->setTime(rand(8, 22), rand(0, 59), rand(0, 59)),
                    'updated_at' => $date->setTime(rand(8, 22), rand(0, 59), rand(0, 59)),
                ]);
            }
        }
    }

    private function getPageTitle($route)
    {
        $titles = [
            'homepage' => 'Home Page',
            'Contact_Page' => 'Contact Us',
            'About_Page' => 'About Us',
            'Service_Page' => 'Our Services',
            'Sales_Page' => 'Sales',
            'Quiz_Page' => 'Sales Quiz',
            'NOEL_Form_Page' => 'NOEL Interest Form',
            'Quiz_Start_Page' => 'Quiz Start',
            'student' => 'Student Portal',
            'resource-hub' => 'Resource Hub',
            'event' => 'Events',
        ];

        return $titles[$route] ?? ucwords(str_replace(['_', '-'], ' ', $route));
    }

    private function generateRandomIP()
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function generateUserAgent($browser, $deviceType)
    {
        $userAgents = [
            'Chrome' => [
                'desktop' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
                'mobile' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/119.0.0.0 Mobile/15E148 Safari/604.1',
                'tablet' => 'Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/119.0.0.0 Mobile/15E148 Safari/604.1'
            ],
            'Firefox' => [
                'desktop' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/119.0',
                'mobile' => 'Mozilla/5.0 (Mobile; rv:109.0) Gecko/119.0 Firefox/119.0',
                'tablet' => 'Mozilla/5.0 (Mobile; rv:109.0) Gecko/119.0 Firefox/119.0'
            ],
            'Safari' => [
                'desktop' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Version/17.0 Safari/537.36',
                'mobile' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
                'tablet' => 'Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1'
            ],
            'Edge' => [
                'desktop' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0',
                'mobile' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 EdgiOS/119.0.0.0 Mobile/15E148 Safari/605.1.15',
                'tablet' => 'Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 EdgiOS/119.0.0.0 Mobile/15E148 Safari/605.1.15'
            ]
        ];

        return $userAgents[$browser][$deviceType] ?? 'Mozilla/5.0 (Unknown)';
    }

    private function generateBrowserVersion($browser)
    {
        $versions = [
            'Chrome' => '119.0.0.0',
            'Firefox' => '119.0',
            'Safari' => '17.0',
            'Edge' => '119.0.0.0'
        ];

        return $versions[$browser] ?? '1.0';
    }

    private function getScreenWidth($deviceType)
    {
        return match ($deviceType) {
            'mobile' => rand(360, 414),
            'tablet' => rand(768, 1024),
            'desktop' => rand(1366, 1920),
            default => 1366
        };
    }

    private function getScreenHeight($deviceType)
    {
        return match ($deviceType) {
            'mobile' => rand(640, 896),
            'tablet' => rand(1024, 1366),
            'desktop' => rand(768, 1080),
            default => 768
        };
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
