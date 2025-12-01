<x-app-layout>
    <x-admin-dark-mode />

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight dark-transition">
                <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>{{ __('Event Management') }}
            </h2>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Total Events: <span class="font-semibold text-blue-600">{{ count($events) }}</span>
                </span>
                <a href="{{ route('admin.events.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Create Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        @php
                            $now = \Carbon\Carbon::now();
                            $upcomingEvents = $events
                                ->filter(function ($event) use ($now) {
                                    return \Carbon\Carbon::parse($event->event_date . ' ' . $event->event_time) > $now;
                                })
                                ->count();
                            $pastEvents = count($events) - $upcomingEvents;
                            $publicEvents = $events->where('visibility', 'public')->count();
                            $privateEvents = $events->where('visibility', 'private')->count();
                        @endphp

                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl text-white shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-green-100 text-sm">Upcoming Events</p>
                                    <p class="text-2xl font-bold">{{ $upcomingEvents }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl text-white shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-history fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-blue-100 text-sm">Past Events</p>
                                    <p class="text-2xl font-bold">{{ $pastEvents }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl text-white shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-globe fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-purple-100 text-sm">Public Events</p>
                                    <p class="text-2xl font-bold">{{ $publicEvents }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-xl text-white shadow-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-lock fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-orange-100 text-sm">Private Events</p>
                                    <p class="text-2xl font-bold">{{ $privateEvents }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" class="text-green-400 hover:text-green-600"
                                        onclick="this.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" class="text-red-400 hover:text-red-600"
                                        onclick="this.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Events Table -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Events</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Event Details</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Schedule</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Settings</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions</th>  
                                        @if (auth()->user()->hasRole('admin|supervisor'))  
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Notifications</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($events as $event)
                                        @php
                                            $eventDateTime = \Carbon\Carbon::parse(
                                                $event->event_date . ' ' . $event->event_time,
                                            );
                                            $now = \Carbon\Carbon::now();
                                            $isUpcoming = $eventDateTime > $now;
                                            $hoursUntilEvent = $now->diffInHours($eventDateTime, false);
                                            $hoursUntilEvent2 = $now->diffInHours($eventDateTime, true);
                                        @endphp

                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <!-- Event Details -->
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        @if ($event->image)
                                                            <img class="h-12 w-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                                                                src="{{ asset('storage/' . $event->image) }}"
                                                                alt="{{ $event->title }}"
                                                                style="min-height: 48px; min-width: 48px; max-height: 48px; max-width: 48px;">
                                                        @else
                                                            <div
                                                                class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ Str::limit($event->title, 40) }}
                                                        </div>
                                                        @if ($event->subtitle)
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ Str::limit($event->subtitle, 50) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center mt-1">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isUpcoming ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                                {{ $isUpcoming ? 'Upcoming' : 'Completed' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Schedule -->
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    <div class="flex items-center mb-1">
                                                        <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                                    </div>
                                                    <div class="flex items-center mb-1">
                                                        <i class="fas fa-clock mr-2 text-green-500"></i>
                                                        {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                                        <span
                                                            class="text-xs">{{ Str::limit($event->venue, 20) }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Settings -->
                                            <td class="px-6 py-4">
                                                <div class="space-y-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->visibility === 'public' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                        <i
                                                            class="fas {{ $event->visibility === 'public' ? 'fa-globe' : 'fa-lock' }} mr-1"></i>
                                                        {{ ucfirst($event->visibility) }}
                                                    </span>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ str_replace('_', ' ', $event->display) }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Bookings -->
                                                    <a href="{{ route('admin.events.bookings', $event->id) }}"
                                                        target="_blank"
                                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200"
                                                        title="View Bookings ({{ $event->seats->count() }})">
                                                        <i class="fas fa-users"></i>
                                                        <span class="sr-only">{{ $event->seats->count() }}</span>
                                                    </a>

                                                    <!-- View Event -->
                                                    <a href="{{ route('events.show', $event->slug) }}"
                                                        target="_blank"
                                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200"
                                                        title="View Event">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Edit -->
                                                    <a href="{{ route('admin.events.edit', $event) }}"
                                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-200"
                                                        title="Edit Event">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Feedback -->
                                                    <a href="{{ route('admin.events.feedbacks', $event->id) }}"
                                                        target="_blank"
                                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200"
                                                        title="View Feedback">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </a>

                                                    <!-- Analytics -->
                                                    <a href="{{ route('admin.events.clicks', $event->id) }}"
                                                        target="_blank"
                                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-200"
                                                        title="View Analytics">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>

                                                    <!-- Delete -->
                                                    @if (auth()->user()->hasRole('admin|supervisor'))
                                                    <form action="{{ route('admin.events.destroy', $event) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                                            title="Delete Event">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Notifications -->
                                            @if (auth()->user()->hasRole('admin|supervisor'))
                                            <td class="px-6 py-4">
                                                <div class="space-y-2">
                                                    @if ($hoursUntilEvent <= 24 && $hoursUntilEvent > 0)
                                                        <form
                                                            action="{{ route('admin.events.sendReminder', $event->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Send reminder emails to all participants?')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg transition-colors duration-200">
                                                                <i class="fas fa-bell mr-1"></i>Send Reminder
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($hoursUntilEvent2 <= 24 && $hoursUntilEvent2 > 0)
                                                        <form
                                                            action="{{ route('admin.events.sendFeedback', $event->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg transition-colors duration-200">
                                                                <i class="fas fa-comment mr-1"></i>Send Feedback
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                                        No events found</h3>
                                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by
                                                        creating your first event.</p>
                                                    <a href="{{ route('admin.events.create') }}"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                                        Create Event
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center my-4">
                            {{ $events->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
