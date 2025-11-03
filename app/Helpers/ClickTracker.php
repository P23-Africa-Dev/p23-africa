<?php
// app/Helpers/ClickTracker.php

namespace App\Helpers;

use App\Events\AdminClicksEvent;
use App\Models\Click;
use App\Models\PageVisit;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ClickTracker
{
    public static function track($routeName, $pageTitle = null, $additionalData = [])
    {
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
            'BRN_Page' => 'BRN Program',
            'BRN_Form_Page' => 'BRN Interest Form',
            'Quiz_Start_Page' => 'Quiz Start',
            'student' => 'Student Portal',
            'resource-hub' => 'Resource Hub',
            'event' => 'Events',
        ];

        return $titles[$routeName] ?? ucwords(str_replace(['_', '-'], ' ', $routeName));
    }

    private static function getCountryFromIP($ipAddress)
    {
        // This is a placeholder. You should integrate with a proper GeoIP service
        // like MaxMind GeoIP2, IP-API, or similar
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'Local';
        }

        // For now, return null. You can integrate with services like:
        // - MaxMind GeoIP2
        // - ip-api.com (free tier available)
        // - ipinfo.io
        return null;
    }

    private static function getCityFromIP($ipAddress)
    {
        // Same as above - integrate with GeoIP service
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'Local';
        }

        return null;
    }

    /**
     * Normalize country name to specific allowed countries or "Others"
     */
    public static function normalizeCountry($country)
    {
        if (empty($country)) {
            return 'Others';
        }

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

        // Apply mapping if exists
        $normalizedCountry = $countryMapping[$country] ?? $country;

        // Check if the normalized country is in allowed list
        return in_array($normalizedCountry, $allowedCountries) ? $normalizedCountry : 'Others';
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
