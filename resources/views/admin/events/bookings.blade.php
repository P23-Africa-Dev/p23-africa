<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.events.index') }}"
                    class="mr-4 text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-users mr-3 text-blue-600"></i>Event Bookings
                </h2>
            </div>
            <div class="text-sm text-gray-600">
                Total Bookings: <span class="font-semibold text-blue-600">{{ count($bookings) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Event Info Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-lg mb-8">
                        <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                        <div class="flex items-center space-x-6 text-blue-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $event->venue }}
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-500 rounded-lg">
                                    <i class="fas fa-user-check text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-green-600 text-sm font-medium">Total Bookings</p>
                                    <p class="text-2xl font-bold text-green-900">{{ count($bookings) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 rounded-lg">
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-blue-600 text-sm font-medium">Unique Emails</p>
                                    <p class="text-2xl font-bold text-blue-900">
                                        {{ $bookings->unique('email')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-500 rounded-lg">
                                    <i class="fas fa-percentage text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-purple-600 text-sm font-medium">Booking Rate</p>
                                    <p class="text-2xl font-bold text-purple-900">100%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Export -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-download mr-2 text-green-600"></i>Export Attendee Data
                        </h4>
                        <div class="flex items-center space-x-4">
                            <button id="copyEmailsBtn"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-copy mr-2"></i>Copy All Emails
                            </button>
                            <button onclick="downloadCSV()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-file-csv mr-2"></i>Download CSV
                            </button>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email List (for mailing
                                tools)</label>
                            <textarea id="emailList" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="3" readonly>
@foreach ($bookings as $booking)
{{ $booking->email }},
@endforeach
</textarea>
                        </div>
                    </div>

                    <script>
                        document.getElementById('copyEmailsBtn').addEventListener('click', function() {
                            const textarea = document.getElementById('emailList');
                            textarea.select();
                            textarea.setSelectionRange(0, 99999);
                            navigator.clipboard.writeText(textarea.value.trim()).then(() => {
                                // Show success message
                                const btn = this;
                                const originalText = btn.innerHTML;
                                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                                btn.classList.add('bg-green-800');

                                setTimeout(() => {
                                    btn.innerHTML = originalText;
                                    btn.classList.remove('bg-green-800');
                                    btn.classList.add('bg-green-600', 'hover:bg-green-700');
                                }, 2000);
                            });
                        });

                        function downloadCSV() {
                            const bookings = @json($bookings);
                            const csvContent =
                                "data:text/csv;charset=utf-8,Name,Email,Phone,Job Title,Organization,Location,Industry,Seat Code,Registration Date\n" +
                                bookings.map(booking =>
                                    `"${booking.name}","${booking.email}","${booking.phone || ''}","${booking.job_title || ''}","${booking.organization || ''}","${booking.location || ''}","${booking.industry || ''}","${booking.seat_code}","${new Date(booking.created_at).toLocaleDateString()}"`
                                ).join("\n");

                            const encodedUri = encodeURI(csvContent);
                            const link = document.createElement("a");
                            link.setAttribute("href", encodedUri);
                            link.setAttribute("download", "{{ Str::slug($event->title) }}-bookings.csv");
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    </script>

</x-app-layout>
