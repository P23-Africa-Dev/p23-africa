<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventFeedbackMail;
use App\Mail\EventReminderMail;
use App\Models\Event;
use App\Models\EventClick;
use App\Models\EventClickLog;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();

        $events = Event::select('*')
            ->orderByRaw("CASE WHEN CONCAT(event_date, ' ', event_time) >= ? THEN 0 ELSE 1 END", [$now])
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->paginate(10);
        // $events = Event::latest()->get();


        return view('admin.events.index', compact('events'));
    }

    public function showBookings(Event $event)
    {
        $bookings = $event->seats()->with('event')->get();
        return view('admin.events.bookings', compact('event', 'bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visibility' => 'required|in:public,private',
            'display' => 'required|in:open_to_all,event_only,brn_only',
            'booking_fields' => 'nullable|array',
            'booking_fields.*' => 'string',
        ], [
            'title.required' => 'Event title is required.',
            'title.max' => 'Event title cannot exceed 255 characters.',
            'description.required' => 'Event description is required.',
            'link.url' => 'Please enter a valid URL (e.g., https://example.com).',
            'event_date.required' => 'Event date is required.',
            'event_date.after_or_equal' => 'Event date cannot be in the past.',
            'event_time.required' => 'Event time is required.',
            'venue.required' => 'Event venue is required.',
            'venue.max' => 'Venue name cannot exceed 255 characters.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'visibility.required' => 'Please select event visibility.',
            'display.required' => 'Please select where to display this event.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['visibility'] === 'public') {
            // Auto-prepend https:// if not present
            $link = $request->link;
            if ($link && !preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $validated['link'] = $link;
        } else {
            $validated['link'] = null; // clear for private
        }

        // Handle booking fields
        $bookingFields = $request->input('booking_fields', ['name', 'email']);
        $validated['booking_fields'] = is_array($bookingFields) ? $bookingFields : ['name', 'email'];

        // Sanitize description and generate slug
        $validated['description'] = Purifier::clean($request->description); // Sanitize HTML

        // Event::create($request->all());
        $event = Event::create($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($event)
            ->withProperties(['title' => $event->title, 'event_date' => $event->event_date])
            ->log('Created event');

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display' => 'required|in:open_to_all,event_only,brn_only',
            'booking_fields' => 'nullable|array',
            'booking_fields.*' => 'string',
        ], [
            'title.required' => 'Event title is required.',
            'title.max' => 'Event title cannot exceed 255 characters.',
            'description.required' => 'Event description is required.',
            'link.url' => 'Please enter a valid URL (e.g., https://example.com).',
            'event_date.required' => 'Event date is required.',
            'event_time.required' => 'Event time is required.',
            'venue.required' => 'Event venue is required.',
            'venue.max' => 'Venue name cannot exceed 255 characters.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'display.required' => 'Please select where to display this event.',
        ]);

        // Remove current image if requested
        if ($request->has('remove_image') && $event->image) {
            Storage::disk('public')->delete($event->image);
            $event->image = null;
        }

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $validated['image'] = $request->file('image')->store('events', 'public');
        } else {
            // Keep the old image if no new one and not removed
            unset($validated['image']);
        }

        // Handle link formatting for public events
        if ($event->visibility === 'public' && $request->link) {
            // Auto-prepend https:// if not present
            $link = $request->link;
            if ($link && !preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $validated['link'] = $link;
        }

        // Handle booking fields
        $bookingFields = $request->input('booking_fields', ['name', 'email']);
        $validated['booking_fields'] = is_array($bookingFields) ? $bookingFields : ['name', 'email'];

        // Sanitize and slugify
        $validated['description'] = Purifier::clean($request->description);
        $validated['slug'] = Str::slug($validated['title']);

        $event->update($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($event)
            ->withProperties(['title' => $event->title])
            ->log('Updated event');

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $eventTitle = $event->title;
        $eventId = $event->id;

        $event->delete();

        activity()
            ->causedBy(Auth::user())
            ->withProperties(['title' => $eventTitle, 'event_id' => $eventId])
            ->log('Deleted event');

        return redirect()->route('admin.events.index')->with('success', 'Event deleted');
    }

    public function sendReminder(Request $request, $eventId)
    {
        $event = Event::with('seats')->findOrFail($eventId);
        $type = $request->input('type', '24hr'); // Default to 24hr if not specified

        if ($event->seats->isEmpty()) {
            return back()->with('error', 'No registered attendees for this event.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($event->seats as $seat) {
            if (empty($seat->email)) {
                continue;
            }

            try {
                $firstName = explode(' ', $seat->name)[0] ?? 'Guest';

                $link = $event->visibility === 'public'
                    ? $event->link
                    : route('private.access', ['slug' => $event->slug, 'code' => $seat->seat_code]);

                Mail::to($seat->email)->send(new EventReminderMail($seat, $event, $link, $firstName));
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error("Failed to send {$type} reminder to {$seat->email} for event '{$event->title}': " . $e->getMessage());
            }
        }

        // Mark reminder as sent
        if ($type === '24hr') {
            $event->reminder_24hr_sent_at = now();
        } else {
            $event->reminder_15min_sent_at = now();
        }
        $event->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($event)
            ->withProperties(['type' => $type, 'sent' => $sentCount, 'failed' => $failedCount])
            ->log("Sent {$type} reminder emails manually");

        if ($failedCount > 0) {
            return back()->with('warning', "Sent {$sentCount} reminder(s), {$failedCount} failed.");
        }

        return back()->with('success', "Reminder emails sent successfully to {$sentCount} attendee(s).");
    }

    public function sendFeedback(Event $event)
    {
        $bookings = Seat::where('event_id', $event->id)->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->email)->send(new EventFeedbackMail($event, $booking));
        }

        return redirect()->back()->with('success', 'Feedback emails sent to attendees.');
    }

    public function viewFeedbacks(Event $event)
    {
        $feedbacks = $event->feedbacks()->latest()->get();
        return view('admin.events.feedbacks', compact('event', 'feedbacks'));
    }

    public function viewClicks(Event $event, Request $request)
    {
        $filter = $request->get('filter', 'all');

        $logsQuery = EventClickLog::where('event_id', $event->id);
        $query = EventClick::where('event_id', $event->id);

        // Apply filters
        if ($filter === 'today') {
            $logsQuery->whereDate('created_at', Carbon::today());
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === '7days') {
            $logsQuery->where('created_at', '>=', Carbon::now()->subDays(7));
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($filter === '30days') {
            $logsQuery->where('created_at', '>=', Carbon::now()->subDays(30));
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        // Unique device_identifier for unique counts
        $uniqueClicks = $query->get()->unique('device_identifier');

        $mobileClicks = $uniqueClicks->where('device_type', 'Mobile')->count();
        $desktopClicks = $uniqueClicks->where('device_type', 'Desktop')->count();
        $allUniqueClicks = $uniqueClicks->count();

        // All clicks including revisits
        $allClicksWithRevisits = $logsQuery->count();

        return view('admin.events.clicks_report', compact(
            'event',
            'mobileClicks',
            'desktopClicks',
            'allUniqueClicks',
            'allClicksWithRevisits',
            'filter'
        ));
    }

    /**
     * Get available booking fields for dynamic form generation
     */
    public function getBookingFields()
    {
        $fields = [
            [
                'field' => 'name',
                'label' => 'Full Name',
                'description' => 'Attendee\'s full name (required)',
                'type' => 'text',
                'required' => true
            ],
            [
                'field' => 'email',
                'label' => 'Email Address',
                'description' => 'Attendee\'s email address (required)',
                'type' => 'email',
                'required' => true
            ],
            [
                'field' => 'phone',
                'label' => 'Phone Number',
                'description' => 'Contact phone number with country code',
                'type' => 'tel',
                'required' => false
            ],
            [
                'field' => 'job_title',
                'label' => 'Job Title',
                'description' => 'Attendee\'s current job title or position',
                'type' => 'text',
                'required' => false
            ],
            [
                'field' => 'organization',
                'label' => 'Organization',
                'description' => 'Company or organization name',
                'type' => 'text',
                'required' => false
            ],
            [
                'field' => 'location',
                'label' => 'Location',
                'description' => 'City, country, or region',
                'type' => 'text',
                'required' => false
            ],
            [
                'field' => 'industry',
                'label' => 'Industry',
                'description' => 'Industry sector or field of work',
                'type' => 'text',
                'required' => false
            ],
            [
                'field' => 'expectations',
                'label' => 'Expectations',
                'description' => 'What do you hope to gain from this event?',
                'type' => 'textarea',
                'required' => false
            ],
            [
                'field' => 'referral_source',
                'label' => 'How did you hear about us?',
                'description' => 'Referral source or discovery method',
                'type' => 'text',
                'required' => false
            ]
        ];

        return response()->json($fields);
    }
}
