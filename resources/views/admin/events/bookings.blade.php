<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ orgRoute('admin.events.index') }}"
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

                    <!-- Bookings Table -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-table mr-2 text-blue-600"></i>All Attendees
                                </h4>
                                <div class="flex items-center space-x-3">
                                    <input type="text" id="searchInput" placeholder="Search attendees..."
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <select id="filterIndustry"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Industries</option>
                                        @foreach ($bookings->pluck('industry')->filter()->unique()->sort() as $industry)
                                            <option value="{{ $industry }}">{{ $industry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            @if ($bookings->isEmpty())
                                <div class="text-center py-12">
                                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Bookings Yet</h3>
                                    <p class="text-gray-500">No one has registered for this event yet.</p>
                                </div>
                            @else
                                <table class="min-w-full divide-y divide-gray-200" id="bookingsTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                #
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Phone
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Job Title
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Organization
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Location
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Industry
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Seat Code
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Registered
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($bookings as $index => $booking)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150 booking-row"
                                                data-name="{{ strtolower($booking->name) }}"
                                                data-email="{{ strtolower($booking->email) }}"
                                                data-organization="{{ strtolower($booking->organization ?? '') }}"
                                                data-industry="{{ $booking->industry ?? '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <span
                                                                class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($booking->name, 0, 1)) }}</span>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $booking->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $booking->email }}</div>
                                                    <button
                                                        onclick="copyToClipboard('{{ $booking->email }}', this)"
                                                        class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                                        <i class="fas fa-copy mr-1"></i>Copy
                                                    </button>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->phone ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->job_title ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->organization ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->location ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($booking->industry)
                                                        <span
                                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                            {{ $booking->industry }}
                                                        </span>
                                                    @else
                                                        <span class="text-sm text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <code
                                                        class="px-2 py-1 bg-gray-100 rounded text-xs font-mono text-gray-800">{{ $booking->seat_code }}</code>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div>{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::parse($booking->created_at)->format('g:i A') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button onclick="viewDetails({{ $booking->id }})"
                                                        class="text-blue-600 hover:text-blue-900 mr-3" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="mailto:{{ $booking->email }}"
                                                        class="text-green-600 hover:text-green-900" title="Send Email">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        @if ($bookings->isNotEmpty())
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Showing <span id="showingCount">{{ count($bookings) }}</span> of
                                        <span>{{ count($bookings) }}</span> attendees
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Last updated: {{ now()->format('M d, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-user-circle mr-2 text-blue-600"></i>Attendee Details
                </h3>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        const bookingsData = @json($bookings);

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            filterTable();
        });

        // Industry filter
        document.getElementById('filterIndustry').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const industryFilter = document.getElementById('filterIndustry').value;
            const rows = document.querySelectorAll('.booking-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                const organization = row.getAttribute('data-organization');
                const industry = row.getAttribute('data-industry');

                const matchesSearch = name.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    organization.includes(searchTerm);

                const matchesIndustry = !industryFilter || industry === industryFilter;

                if (matchesSearch && matchesIndustry) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('showingCount').textContent = visibleCount;
        }

        // Copy to clipboard
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                button.classList.add('text-green-600');

                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.classList.remove('text-green-600');
                }, 2000);
            });
        }

        // View details modal
        function viewDetails(bookingId) {
            const booking = bookingsData.find(b => b.id === bookingId);
            if (!booking) return;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Full Name</p>
                        <p class="text-base text-gray-900">${booking.name}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Email Address</p>
                        <p class="text-base text-gray-900">${booking.email}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Phone Number</p>
                        <p class="text-base text-gray-900">${booking.phone || 'Not provided'}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Job Title</p>
                        <p class="text-base text-gray-900">${booking.job_title || 'Not provided'}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Organization</p>
                        <p class="text-base text-gray-900">${booking.organization || 'Not provided'}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Location</p>
                        <p class="text-base text-gray-900">${booking.location || 'Not provided'}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Industry</p>
                        <p class="text-base text-gray-900">${booking.industry || 'Not provided'}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 mb-1">Seat Code</p>
                        <p class="text-base text-gray-900"><code class="px-2 py-1 bg-gray-200 rounded text-sm font-mono">${booking.seat_code}</code></p>
                    </div>
                    ${booking.referral_source ? `
                    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 mb-1">How They Heard About Us</p>
                        <p class="text-base text-gray-900">${booking.referral_source}</p>
                    </div>
                    ` : ''}
                    ${booking.expectations ? `
                    <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 mb-1">Expectations</p>
                        <p class="text-base text-gray-900">${booking.expectations}</p>
                    </div>
                    ` : ''}
                    <div class="bg-blue-50 p-4 rounded-lg md:col-span-2">
                        <p class="text-sm font-medium text-blue-700 mb-1">Registration Date</p>
                        <p class="text-base text-blue-900">${new Date(booking.created_at).toLocaleString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric', 
                            hour: 'numeric', 
                            minute: 'numeric',
                            hour12: true
                        })}</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="mailto:${booking.email}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    <button onclick="closeModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Close
                    </button>
                </div>
            `;

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Email list copy functionality
        document.getElementById('copyEmailsBtn').addEventListener('click', function() {
            const textarea = document.getElementById('emailList');
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(textarea.value.trim()).then(() => {
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

        // CSV Download
        function downloadCSV() {
            const bookings = @json($bookings);
            const csvContent =
                "data:text/csv;charset=utf-8,Name,Email,Phone,Job Title,Organization,Location,Industry,Expectations,Referral Source,Seat Code,Registration Date\n" +
                bookings.map(booking =>
                    `"${booking.name}","${booking.email}","${booking.phone || ''}","${booking.job_title || ''}","${booking.organization || ''}","${booking.location || ''}","${booking.industry || ''}","${(booking.expectations || '').replace(/"/g, '""')}","${booking.referral_source || ''}","${booking.seat_code}","${new Date(booking.created_at).toLocaleString()}"`
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
