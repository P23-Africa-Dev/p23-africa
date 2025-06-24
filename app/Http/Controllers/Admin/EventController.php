<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventFeedbackMail;
use App\Mail\EventReminderMail;
use App\Models\Event;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            ->get();
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
            'event_date' => 'required|date',
            'event_time' => 'required',
            'venue' => 'required|string|max:255',
            'image' => 'nullable',
            'visibility' => 'required|in:public,private',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['visibility'] === 'public') {
            $validated['link'] = $request->link;
        } else {
            $validated['link'] = null; // clear for private
        }

        // $validated['slug'] = Str::slug($validated['title']);
        // $validated['link'] = url('/events/' . $validated['slug']); // Auto-generate link
        $validated['description'] = Purifier::clean($request->description); // Sanitize HTML

        // Event::create($request->all());
        Event::create($validated);

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
            'image' => 'nullable|image|max:2048', // optional image validation
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

        // if ($validated['visibility'] === 'public') {
        //     $validated['link'] = $request->link;
        // }

        // $validated['slug'] = Str::slug($validated['title']);
        // $validated['link'] = url('/events/' . $validated['slug']); // Auto-generate updated link
        

        // Sanitize and slugify
        $validated['description'] = Purifier::clean($request->description);
        $validated['slug'] = Str::slug($validated['title']);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted');
    }

    public function sendReminder(Request $request, $eventId)
    {
        $event = Event::with('seats')->findOrFail($eventId);

        foreach ($event->seats as $seat) {
            $firstName = explode(' ', $seat->name)[0] ?? 'Guest';

            $link = $event->visibility === 'public'
                ? $event->link
                : route('private.access', ['slug' => $event->slug, 'code' => $seat->seat_code]);

            Mail::to($seat->email)->send(new EventReminderMail($seat, $event, $link, $firstName));
        }

        return back()->with('success', 'Reminder emails sent successfully.');
    }

    public function sendFeedback(Event $event)
    {
        $bookings = Seat::where('event_id', $event->id)->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->email)->queue(new EventFeedbackMail($event, $booking));
        }

        return redirect()->back()->with('success', 'Feedback emails sent to attendees.');
    }
}
