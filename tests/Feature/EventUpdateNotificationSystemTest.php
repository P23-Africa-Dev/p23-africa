<?php

namespace Tests\Feature;

use App\Mail\EventUpdatedMail;
use App\Models\Event;
use App\Models\EventUpdateHistory;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventUpdateNotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
    }

    public function test_update_event_without_notification_updates_data_and_does_not_queue_emails(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $event = $this->createEvent();

        $response = $this->actingAs($admin)
            ->put(route('admin.events.update', $event), [
                'title' => 'Updated Growth Summit',
                'subtitle' => 'Updated subtitle',
                'description' => '<p>Updated event description</p>',
                'link' => 'https://example.test/updated-link',
                'event_date' => now()->addDays(8)->toDateString(),
                'event_time' => '14:00',
                'venue' => 'Abuja Hub',
                'display' => 'open_to_all',
                'booking_fields' => ['name', 'email', 'phone'],
                'send_update_email' => 0,
            ]);

        $response->assertRedirect(route('admin.events.index'));

        $event->refresh();

        $this->assertSame('Updated Growth Summit', $event->title);
        $this->assertSame('Abuja Hub', $event->venue);

        Mail::assertNothingSent();

        $history = EventUpdateHistory::query()->where('event_id', $event->id)->latest()->first();
        $this->assertNotNull($history);
        $this->assertFalse((bool) $history->notifications_requested);
        $this->assertSame(0, (int) $history->notified_attendees_count);
        $this->assertContains('title', $history->changed_fields);
    }

    public function test_update_event_with_notification_sends_one_email_per_unique_attendee_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $event = $this->createEvent();

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Amina Yusuf',
            'email' => 'attendee@example.test',
            'phone' => '+2348012345678',
            'seat_code' => 'P23-NTF-001',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Amina Duplicate',
            'email' => 'ATTENDEE@example.test',
            'phone' => '+2348012345679',
            'seat_code' => 'P23-NTF-002',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Second Attendee',
            'email' => 'second@example.test',
            'phone' => '+2348012345680',
            'seat_code' => 'P23-NTF-003',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.events.update', $event), [
                'title' => 'Growth Summit 2.0',
                'subtitle' => 'Updated subtitle',
                'description' => '<p>Updated event description with new details</p>',
                'link' => 'https://example.test/new-link',
                'event_date' => now()->addDays(10)->toDateString(),
                'event_time' => '15:30',
                'venue' => 'Virtual Event Room',
                'display' => 'open_to_all',
                'booking_fields' => ['name', 'email', 'phone'],
                'send_update_email' => 1,
            ]);

        $response->assertRedirect(route('admin.events.index'));

        Mail::assertSent(EventUpdatedMail::class, 2);

        Mail::assertSent(EventUpdatedMail::class, function (EventUpdatedMail $mail) use ($event) {
            return $mail->event->id === $event->id
                && in_array('event_date', $mail->changes['changed_fields'] ?? [], true)
                && ($mail->changes['old_values']['event_date'] ?? null) !== ($mail->changes['new_values']['event_date'] ?? null);
        });

        $history = EventUpdateHistory::query()->where('event_id', $event->id)->latest()->first();
        $this->assertNotNull($history);
        $this->assertTrue((bool) $history->notifications_requested);
        $this->assertSame(2, (int) $history->notified_attendees_count);
        $this->assertContains('event_time', $history->changed_fields);
        $this->assertContains('venue', $history->changed_fields);
    }

    private function createEvent(): Event
    {
        return Event::create([
            'title' => 'Growth Summit',
            'subtitle' => 'Original subtitle',
            'description' => '<p>Original event description</p>',
            'link' => 'https://example.test/original-link',
            'event_date' => now()->addDays(5)->toDateString(),
            'event_time' => '10:00:00',
            'venue' => 'Lagos Hall',
            'display' => 'open_to_all',
            'visibility' => 'public',
            'booking_fields' => ['name', 'email'],
        ]);
    }
}
