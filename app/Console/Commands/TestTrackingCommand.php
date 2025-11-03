<?php

namespace App\Console\Commands;

use App\Helpers\ClickTracker;
use Illuminate\Console\Command;

class TestTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the analytics tracking system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Analytics Tracking System...');

        $routes = [
            'homepage' => 'Home Page',
            'Contact_Page' => 'Contact Us',
            'About_Page' => 'About Us',
            'Sales_Page' => 'Sales Page'
        ];

        foreach ($routes as $route => $title) {
            $this->info("Tracking visit to: {$title}");
            ClickTracker::track($route, $title);
        }

        $this->info('✅ Analytics tracking test completed!');
        $this->info('Visit /admin/clicks/report to see the results.');
    }
}
