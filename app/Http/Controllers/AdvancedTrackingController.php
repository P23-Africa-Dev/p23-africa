<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class AdvancedTrackingController extends Controller
{
    public function track(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'page_view':
                return $this->handlePageView($request);
            case 'time_update':
                return $this->handleTimeUpdate($request);
            case 'custom_event':
                return $this->handleCustomEvent($request);
            case 'conversion':
                return $this->handleConversion($request);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }

    private function handlePageView(Request $request)
    {
        $agent = new Agent();

        // Determine route name from URL
        $url = parse_url($request->input('url'), PHP_URL_PATH);
        $routeName = $this->getRouteNameFromUrl($url);

        // Get location data (implement GeoIP integration here)
        $ipAddress = $request->ip();
        $rawCountry = $this->getCountryFromIP($ipAddress);
        $country = $this->normalizeCountry($rawCountry);
        $city = $this->getCityFromIP($ipAddress);

        // Check if this is a unique visitor
        $isUniqueVisitor = !PageVisit::where('ip_address', $ipAddress)
            ->where('route_name', $routeName)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        try {
            // Create visit record
            $visit = PageVisit::create([
                'route_name' => $routeName,
                'page_title' => $this->getPageTitle($routeName),
                'page_url' => $request->input('url'),
                'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'referrer' => $request->input('referrer'),
                'session_id' => session()->getId(),
                'country' => $country,
                'city' => $city,
                'timezone' => $request->input('timezone'),
                'is_unique_visitor' => $isUniqueVisitor,
                'screen_width' => $request->input('screen_width'),
                'screen_height' => $request->input('screen_height'),
            ]);

            return response()->json(['visit_id' => $visit->id]);
        } catch (\Throwable $e) {
            Log::warning('Advanced page visit tracking failed', [
                'route_name' => $routeName,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Tracking failed'], 500);
        }
    }

    private function handleTimeUpdate(Request $request)
    {
        $visitId = $request->input('visit_id');
        $timeOnPage = $request->input('time_on_page');

        if ($visitId && $timeOnPage) {
            PageVisit::where('id', $visitId)->update(['time_on_page' => $timeOnPage]);
        }

        return response()->json(['status' => 'success']);
    }

    private function handleCustomEvent(Request $request)
    {
        // Log custom events (you might want to create a separate events table)
        Log::info('Custom Event Tracked', [
            'event_name' => $request->input('event_name'),
            'event_data' => $request->input('event_data'),
            'visit_id' => $request->input('visit_id'),
            'ip_address' => $request->ip(),
            'timestamp' => now()
        ]);

        return response()->json(['status' => 'success']);
    }

    private function handleConversion(Request $request)
    {
        // Log conversions (you might want to create a separate conversions table)
        Log::info('Conversion Tracked', [
            'conversion_type' => $request->input('conversion_type'),
            'conversion_value' => $request->input('conversion_value'),
            'visit_id' => $request->input('visit_id'),
            'ip_address' => $request->ip(),
            'timestamp' => now()
        ]);

        return response()->json(['status' => 'success']);
    }

    private function getRouteNameFromUrl($url)
    {
        $routes = [
            '/' => 'homepage',
            '/contact' => 'Contact_Page',
            '/about' => 'About_Page',
            '/services' => 'Service_Page',
            '/sales' => 'Sales_Page',
            '/quiz' => 'Quiz_Page',
            '/quiz/start' => 'Quiz_Start_Page',
            '/noel54' => 'BRN_Page',
            '/noel54/interest-form' => 'BRN_Form_Page',
            '/student' => 'student',
            '/resource-hub' => 'resource-hub',
            '/events' => 'event',
            '/founders-playbook' => 'playbook_landing',
        ];

        return $routes[$url] ?? 'unknown_page';
    }

    private function getPageTitle($routeName)
    {
        $titles = [
            'homepage' => 'Home Page',
            'Contact_Page' => 'Contact Us',
            'About_Page' => 'About Us',
            'Service_Page' => 'Our Services',
            'Sales_Page' => 'Sales',
            'Quiz_Page' => 'Sales Quiz',
            'BRN_Page' => 'BRN Program',
            'BRN_Form_Page' => 'BRN Interest Form',
            'Quiz_Start_Page' => 'Quiz Start',
            'student' => 'Student Portal',
            'resource-hub' => 'Resource Hub',
            'event' => 'Events',
            'playbook_landing' => 'Founders Playbook',
        ];

        return $titles[$routeName] ?? ucwords(str_replace(['_', '-'], ' ', $routeName));
    }

    private function getCountryFromIP($ipAddress)
    {
        // Handle local IPs
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || strpos($ipAddress, '192.168.') === 0 || strpos($ipAddress, '10.') === 0) {
            return 'Local';
        }

        // Check if IP is valid for API lookup
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'Unknown';
        }

        try {
            // Use ip-api.com (free tier: 45 requests per minute)
            // Cache the result for 24 hours to reduce API calls
            $cacheKey = 'geoip_country_' . md5($ipAddress);

            return cache()->remember($cacheKey, 86400, function () use ($ipAddress) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ipAddress}?fields=status,country");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 3 second timeout
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);
                    if (isset($data['status']) && $data['status'] === 'success' && isset($data['country'])) {
                        return $data['country'];
                    }
                }

                return 'Unknown';
            });
        } catch (\Exception $e) {
            // Log error but don't break the tracking
            Log::warning('GeoIP lookup failed for IP: ' . $ipAddress . ' - ' . $e->getMessage());
            return 'Unknown';
        }
    }

    private function getCityFromIP($ipAddress)
    {
        // Handle local IPs
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || strpos($ipAddress, '192.168.') === 0 || strpos($ipAddress, '10.') === 0) {
            return 'Local';
        }

        // Check if IP is valid for API lookup
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 'Unknown';
        }

        try {
            // Cache the result for 24 hours
            $cacheKey = 'geoip_city_' . md5($ipAddress);

            return cache()->remember($cacheKey, 86400, function () use ($ipAddress) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ipAddress}?fields=status,city");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);
                    if (isset($data['status']) && $data['status'] === 'success' && isset($data['city'])) {
                        return $data['city'];
                    }
                }

                return 'Unknown';
            });
        } catch (\Exception $e) {
            Log::warning('GeoIP city lookup failed for IP: ' . $ipAddress . ' - ' . $e->getMessage());
            return 'Unknown';
        }
    }

    /**
     * Normalize country name to handle common variations
     * Now stores all actual country names instead of forcing "Others"
     */
    private function normalizeCountry($country)
    {
        if (empty($country)) {
            return 'Unknown';
        }

        // Normalize common variations to standard names
        $countryMapping = [
            // United States variations
            'USA' => 'United States',
            'US' => 'United States',
            'America' => 'United States',
            'United States of America' => 'United States',

            // United Kingdom variations
            'UK' => 'United Kingdom',
            'Britain' => 'United Kingdom',
            'Great Britain' => 'United Kingdom',
            'England' => 'United Kingdom',
            'Scotland' => 'United Kingdom',
            'Wales' => 'United Kingdom',
            'Northern Ireland' => 'United Kingdom',

            // Other common variations
            'UAE' => 'United Arab Emirates',
            'South Korea' => 'Korea, Republic of',
            'North Korea' => 'Korea, Democratic People\'s Republic of',
            'Congo' => 'Congo, Republic of the',
            'DRC' => 'Congo, Democratic Republic of the',
            'Ivory Coast' => 'Côte d\'Ivoire',

            // Keep these as is
            'Local' => 'Local',
            'Unknown' => 'Unknown',
        ];

        // Apply mapping if exists, otherwise return the original country name
        return $countryMapping[$country] ?? $country;
    }
}
