<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestBookingFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-booking-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test booking fields functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Booking Fields System...');

        // Create a test event
        $event = \App\Models\Event::create([
            'title' => 'Test Event for Booking Fields',
            'subtitle' => 'Testing dynamic field selection',
            'description' => 'This is a test event to verify booking fields functionality',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'event_time' => '14:00:00',
            'venue' => 'Test Venue',
            'display' => 'event_only',
            'visibility' => 'public',
            'booking_fields' => ['name', 'email', 'job_title', 'organization', 'expectations']
        ]);

        $this->info("Created test event with ID: {$event->id}");

        // Test the getBookingFields method
        $bookingFields = $event->getBookingFields();
        $this->info('Booking fields for this event:');
        foreach ($bookingFields as $field) {
            $this->line("- {$field}");
        }

        // Test the available fields
        $availableFields = \App\Models\Event::getAvailableBookingFields();
        $this->info('Available booking fields:');
        foreach ($availableFields as $key => $label) {
            $this->line("- {$key}: {$label}");
        }

        $this->info('Test completed successfully!');

        return 0;
    }
}
