<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Attendance & Leave Calendar</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    View attendance records and leave schedules at a glance
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ orgRoute('admin.attendance.index') }}"
                    class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-list-ul"></i> Attendance
                </a>
                <a href="{{ orgRoute('admin.leave-requests.index') }}"
                    class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-envelope-paper"></i> Leave Requests
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Legend --}}
    <div class="card anim-in mb-4">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap gap-3 align-items-center" style="font-size:.8125rem;">
                <span class="fw-semibold text-muted">Legend:</span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#10b981;display:inline-block;"></span>
                    Attendance (Sign-in)
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#3b82f6;display:inline-block;"></span>
                    Annual Leave
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#ef4444;display:inline-block;"></span>
                    Sick Leave
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#8b5cf6;display:inline-block;"></span>
                    Personal Leave
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#f59e0b;display:inline-block;"></span>
                    Pending Leave
                </span>
                <span class="d-flex align-items-center gap-1">
                    <span
                        style="width:12px;height:12px;border-radius:3px;background:#6b7280;display:inline-block;"></span>
                    Other Leave
                </span>
            </div>
        </div>
    </div>

    {{-- Calendar Container --}}
    <div class="card anim-in">
        <div class="card-body">
            <div id="attendance-calendar" style="min-height:600px;"></div>
        </div>
    </div>

    {{-- FullCalendar CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <style>
        /* Calendar Theming */
        #attendance-calendar .fc {
            --fc-border-color: var(--border);
            --fc-button-bg-color: var(--p-primary);
            --fc-button-border-color: var(--p-primary);
            --fc-button-hover-bg-color: var(--p-accent);
            --fc-button-hover-border-color: var(--p-accent);
            --fc-button-active-bg-color: var(--p-accent);
            --fc-button-active-border-color: var(--p-accent);
            --fc-today-bg-color: rgba(99, 102, 241, .06);
            font-size: .8125rem;
        }

        #attendance-calendar .fc-toolbar-title {
            font-size: 1.1rem !important;
            font-weight: 700;
        }

        #attendance-calendar .fc-event {
            border: none;
            border-radius: 4px;
            padding: 1px 4px;
            font-size: .7rem;
            font-weight: 600;
            cursor: default;
        }

        #attendance-calendar .fc-daygrid-event-dot {
            display: none;
        }

        #attendance-calendar .fc-button {
            font-size: .8125rem;
            padding: .3rem .65rem;
            border-radius: var(--radius-sm) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('attendance-calendar');
            const attendanceUrl = '{{ orgRoute('admin.attendance.calendar-data') }}';
            const leaveUrl = '{{ orgRoute('admin.leave-requests.calendar-data') }}';

            const leaveTypeColors = {
                annual: '#3b82f6',
                sick: '#ef4444',
                personal: '#8b5cf6',
                maternity: '#ec4899',
                paternity: '#06b6d4',
                bereavement: '#6b7280',
                other: '#6b7280'
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                height: 'auto',
                eventSources: [{
                        url: attendanceUrl,
                        method: 'GET',
                        failure: function() {
                            console.error('Failed to load attendance data');
                        },
                        eventDataTransform: function(event) {
                            return {
                                ...event,
                                color: '#10b981',
                                display: 'block',
                                allDay: true
                            };
                        }
                    },
                    {
                        url: leaveUrl,
                        method: 'GET',
                        failure: function() {
                            console.error('Failed to load leave data');
                        },
                        eventDataTransform: function(event) {
                            const isPending = event.extendedProps && event.extendedProps.status ===
                                'pending';
                            const leaveType = event.extendedProps ? event.extendedProps.type :
                                'other';
                            return {
                                ...event,
                                color: isPending ? '#f59e0b' : (leaveTypeColors[leaveType] ||
                                    '#6b7280'),
                                display: 'block',
                                allDay: true
                            };
                        }
                    }
                ],
                eventDidMount: function(info) {
                    // Add tooltip
                    if (info.event.extendedProps && info.event.extendedProps.tooltip) {
                        info.el.title = info.event.extendedProps.tooltip;
                    }
                },
                loading: function(isLoading) {
                    calendarEl.style.opacity = isLoading ? '0.6' : '1';
                }
            });

            calendar.render();
        });
    </script>

</x-app-layout>
