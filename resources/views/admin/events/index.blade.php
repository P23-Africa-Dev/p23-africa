<x-app-layout>
    <x-admin-dark-mode />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight dark-transition">
            {{ __('All Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-container">
                <div class="admin-content table-responsive">

                    <a href="{{ route('admin.events.create') }}" class="admin-btn-primary mb-4 inline-block">Create
                        Event</a>

                    <div class="my-5">
                        @if (session('success'))
                            <div class="admin-alert-success" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close dark:text-green-200" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="admin-alert-danger" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close dark:text-red-200" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="admin-table">
                            <table class="table table-hover w-full">
                                <thead class="admin-table-header">
                                    <tr>
                                        <th class="admin-table-cell">Title</th>
                                        <th class="admin-table-cell">Date</th>
                                        <th class="admin-table-cell">Time</th>
                                        <th class="admin-table-cell">Type</th>
                                        <th class="admin-table-cell">Display</th>
                                        <th class="admin-table-cell">Action</th>
                                        <th class="admin-table-cell">Notifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr class="admin-table-row">
                                            <td class="admin-table-cell">{{ Str::limit($event->title, 23) }}</td>
                                            <td class="admin-table-cell">{{ $event->event_date }}</td>
                                            <td class="admin-table-cell">{{ $event->event_time }}</td>
                                            <td class="admin-table-cell">{{ $event->visibility }}</td>
                                            <td class="admin-table-cell text-capitalize">
                                                {{ str_replace('_', ' ', $event->display) }}</td>
                                            <td class="admin-table-cell">
                                                <div class="flex justify-center space-x-1">
                                                    <a href="{{ route('admin.events.bookings', $event->id) }}"
                                                        target="_blank"
                                                        class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                        title="View Bookings">
                                                        <i class="fa fa-users text-gray-600 dark:text-gray-400"></i>
                                                    </a>

                                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                                                        class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                        title="View Event">
                                                        <i class="fa fa-eye text-gray-600 dark:text-gray-400"></i>
                                                    </a>

                                                    <a href="{{ route('admin.events.edit', $event) }}"
                                                        class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                        title="Edit Event">
                                                        <i class="fa fa-edit text-blue-600 dark:text-blue-400"></i>
                                                    </a>

                                                    <a href="{{ route('admin.events.feedbacks', $event->id) }}"
                                                        target="_blank"
                                                        class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                        title="View Feedback">
                                                        <i
                                                            class="fa-regular fa-comment-dots text-gray-600 dark:text-gray-400"></i>
                                                    </a>

                                                    <a href="{{ route('admin.events.clicks', $event->id) }}"
                                                        target="_blank"
                                                        class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                        title="View Analytics">
                                                        <i
                                                            class="fa fa-chart-line text-gray-600 dark:text-gray-400"></i>
                                                    </a>

                                                    <form action="{{ route('admin.events.destroy', $event) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                                            title="Delete Event">
                                                            <i class="fa fa-trash text-red-600 dark:text-red-400"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="admin-table-cell">
                                                <div class="space-y-2">
                                                    @php
                                                        $eventDateTime = \Carbon\Carbon::parse(
                                                            $event->event_date . ' ' . $event->event_time,
                                                        );
                                                        $now = \Carbon\Carbon::now();
                                                        $hoursUntilEvent = $now->diffInHours($eventDateTime, false);
                                                        $hoursUntilEvent2 = $now->diffInHours($eventDateTime, true);
                                                    @endphp

                                                    @if ($hoursUntilEvent <= 24 && $hoursUntilEvent > 0)
                                                        <form
                                                            action="{{ route('admin.events.sendReminder', $event->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Send reminder emails to all participants?')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="admin-btn-primary text-xs py-1 px-2">
                                                                Send Reminder
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($hoursUntilEvent2 <= 24 && $hoursUntilEvent2 > 0)
                                                        <form
                                                            action="{{ route('admin.events.sendFeedback', $event->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="admin-btn-secondary text-xs py-1 px-2">
                                                                Send Feedback
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
