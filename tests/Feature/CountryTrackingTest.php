<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers\ClickTracker;
use App\Models\PageVisit;

class CountryTrackingTest extends TestCase
{
    /**
     * Test that country tracking works for new visits
     */
    public function test_country_detection_works()
    {
        // Simulate a visit from a non-local IP
        // Note: This will make a real API call in testing

        $this->markTestSkipped('Skipping live API test - uncomment to test');

        // Track a visit
        $visit = ClickTracker::track('homepage', 'Home Page');

        // Assert the visit was created
        $this->assertNotNull($visit);
        $this->assertNotNull($visit->country);

        // Country should not be null or empty
        $this->assertNotEquals('', $visit->country);

        echo "\n✅ Country detected: " . $visit->country;
        echo "\n✅ City detected: " . ($visit->city ?? 'N/A');
    }

    /**
     * Test that local IPs are detected correctly
     */
    public function test_local_ip_detection()
    {
        // This test can run without API calls
        $reflection = new \ReflectionClass(ClickTracker::class);
        $method = $reflection->getMethod('getCountryFromIP');
        $method->setAccessible(true);

        // Test local IPs
        $this->assertEquals('Local', $method->invoke(null, '127.0.0.1'));
        $this->assertEquals('Local', $method->invoke(null, '192.168.1.1'));
        $this->assertEquals('Local', $method->invoke(null, '10.0.0.1'));

        echo "\n✅ Local IP detection works correctly";
    }

    /**
     * Test country normalization
     */
    public function test_country_normalization()
    {
        $reflection = new \ReflectionClass(ClickTracker::class);
        $method = $reflection->getMethod('normalizeCountry');
        $method->setAccessible(true);

        // Test variations
        $this->assertEquals('United States', $method->invoke(null, 'USA'));
        $this->assertEquals('United States', $method->invoke(null, 'US'));
        $this->assertEquals('United Kingdom', $method->invoke(null, 'UK'));
        $this->assertEquals('United Kingdom', $method->invoke(null, 'Britain'));

        // Test passthrough for other countries
        $this->assertEquals('Nigeria', $method->invoke(null, 'Nigeria'));
        $this->assertEquals('Ghana', $method->invoke(null, 'Ghana'));

        echo "\n✅ Country normalization works correctly";
    }
}
