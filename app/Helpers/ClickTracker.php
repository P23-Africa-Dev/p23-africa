<?php
// app/Helpers/ClickTracker.php

namespace App\Helpers;

use App\Events\AdminClicksEvent;
use App\Models\Click;
use App\Models\PageVisit;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ClickTracker
{
    public static function track($routeName, $pageTitle = null, $additionalData = [])
    {
        try {
            $agent = new Agent();
            $request = Request::instance();

            // Get device information
            $deviceType = $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop');

            // Get location data (you may want to integrate with a GeoIP service)
            $ipAddress = $request->ip();
            $rawCountry = self::getCountryFromIP($ipAddress);
            $country = self::normalizeCountry($rawCountry);
            $city = self::getCityFromIP($ipAddress);

            // Check if this is a unique visitor
            $sessionId = Session::getId();
            $isUniqueVisitor = !PageVisit::where('ip_address', $ipAddress)
                ->where('route_name', $routeName)
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            // Create detailed visit record
            $visitData = [
                'route_name' => $routeName,
                'page_title' => $pageTitle ?? self::getPageTitle($routeName),
                'page_url' => $request->fullUrl(),
                'device_type' => $deviceType,
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'referrer' => $request->header('referer'),
                'session_id' => $sessionId,
                'country' => $country,
                'city' => $city,
                'is_unique_visitor' => $isUniqueVisitor,
                'screen_width' => $additionalData['screen_width'] ?? null,
                'screen_height' => $additionalData['screen_height'] ?? null,
            ];

            $pageVisit = PageVisit::create($visitData);

            // Update the aggregate clicks table for backward compatibility
            $clicks = Click::where('route_name', $routeName)
                ->where('device_type', $deviceType)
                ->first();

            if ($clicks) {
                $clicks->increment('click_count');
                $clicks->touch();
            } else {
                $clicks = Click::create([
                    'route_name' => $routeName,
                    'device_type' => $deviceType,
                    'click_count' => 1,
                ]);
            }

            event(new AdminClicksEvent($clicks));

            return $pageVisit;
        } catch (\Throwable $e) {
            // Never let analytics take down a public page (e.g. long social URLs)
            Log::warning('Page visit tracking failed', [
                'route_name' => $routeName,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private static function getPageTitle($routeName)
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

        return $titles[$routeName] ?? ucwords(str_replace(['_', '-'], ' ', $routeName));
    }

    private static function getCountryFromIP($ipAddress)
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

    private static function getCityFromIP($ipAddress)
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
    private static function normalizeCountry($country)
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

    public static function trackWithScreenInfo($routeName, $pageTitle = null, $screenWidth = null, $screenHeight = null)
    {
        return self::track($routeName, $pageTitle, [
            'screen_width' => $screenWidth,
            'screen_height' => $screenHeight
        ]);
    }

    public static function updateTimeOnPage($visitId, $timeInSeconds)
    {
        PageVisit::where('id', $visitId)->update(['time_on_page' => $timeInSeconds]);
    }
}
