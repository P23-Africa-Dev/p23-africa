<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gantt Chart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header Controls -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-chart-gantt me-2"></i>Project Timeline
                            </h4>

                            <!-- Status Filter -->
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;"
                                onchange="filterByStatus(this.value)">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button onclick="zoomOut()" class="btn btn-outline-secondary btn-sm" title="Zoom Out">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <button onclick="zoomIn()" class="btn btn-outline-secondary btn-sm" title="Zoom In">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button onclick="scrollToToday()" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-calendar-day me-1"></i>Today
                            </button>
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-list me-1"></i>List View
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <a href="{{ route('admin.projects.create') }}" class="btn btn-dark btn-sm">
                                    <i class="fas fa-plus me-1"></i>New Project
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="d-flex flex-wrap gap-3 mb-4 small">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary">&nbsp;&nbsp;&nbsp;</span>
                            <span>Planning</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">&nbsp;&nbsp;&nbsp;</span>
                            <span>In Progress</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning">&nbsp;&nbsp;&nbsp;</span>
                            <span>On Hold / Review</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success">&nbsp;&nbsp;&nbsp;</span>
                            <span>Completed</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger">&nbsp;&nbsp;&nbsp;</span>
                            <span>Cancelled / Overdue</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-3">
                            <div
                                style="width: 16px; height: 16px; background: repeating-linear-gradient(45deg, #6c757d, #6c757d 2px, transparent 2px, transparent 6px); border-radius: 3px;">
                            </div>
                            <span>Task</span>
                        </div>
                    </div>

                    @if ($projects->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-project-diagram fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No projects found</h5>
                            <p class="text-muted">Create your first project to see it on the Gantt chart.</p>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i>Create Project
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- Gantt Chart Container -->
                        <div class="gantt-container" id="ganttContainer">
                            <div class="gantt-wrapper">
                                <!-- Left Panel: Project/Task Names -->
                                <div class="gantt-sidebar">
                                    <div class="gantt-sidebar-header">
                                        <strong>Projects & Tasks</strong>
                                    </div>
                                    <div class="gantt-sidebar-content">
                                        @foreach ($projects as $project)
                                            <div class="gantt-row gantt-project-row"
                                                data-project-id="{{ $project->id }}">
                                                <div class="gantt-row-content">
                                                    <button class="btn btn-sm btn-link p-0 me-2 toggle-project"
                                                        onclick="toggleProject({{ $project->id }})">
                                                        <i class="fas fa-chevron-down"
                                                            id="chevron-{{ $project->id }}"></i>
                                                    </button>
                                                    <a href="{{ route('admin.projects.show', $project) }}"
                                                        class="text-decoration-none fw-semibold text-truncate"
                                                        title="{{ $project->name }}">
                                                        {{ $project->name }}
                                                    </a>
                                                    <span class="badge {{ $project->status_badge_class }} ms-2"
                                                        style="font-size: 0.65rem;">
                                                        {{ $project->progress_percentage }}%
                                                    </span>
                                                </div>
                                            </div>
                                            @foreach ($project->tasks as $task)
                                                <div class="gantt-row gantt-task-row task-of-project-{{ $project->id }}"
                                                    data-task-id="{{ $task->id }}">
                                                    <div class="gantt-row-content ps-4">
                                                        <i class="fas fa-tasks me-2 text-muted small"></i>
                                                        <span class="text-truncate" title="{{ $task->title }}">
                                                            {{ Str::limit($task->title, 25) }}
                                                        </span>
                                                        @if ($task->isOverdue())
                                                            <i class="fas fa-exclamation-circle text-danger ms-1"
                                                                title="Overdue"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Right Panel: Timeline -->
                                <div class="gantt-timeline" id="ganttTimeline">
                                    <!-- Timeline Header -->
                                    <div class="gantt-timeline-header" id="timelineHeader">
                                        <!-- Months row -->
                                        <div class="gantt-months-row" id="monthsRow"></div>
                                        <!-- Days row -->
                                        <div class="gantt-days-row" id="daysRow"></div>
                                    </div>

                                    <!-- Timeline Content -->
                                    <div class="gantt-timeline-content" id="timelineContent">
                                        <!-- Today marker -->
                                        <div class="gantt-today-marker" id="todayMarker"></div>

                                        @foreach ($projects as $project)
                                            <!-- Project Bar -->
                                            <div class="gantt-row gantt-bar-row" data-project-id="{{ $project->id }}">
                                                <div class="gantt-bar project-bar {{ $project->status }}"
                                                    data-start="{{ $project->start_date?->format('Y-m-d') ?? now()->format('Y-m-d') }}"
                                                    data-end="{{ $project->due_date?->format('Y-m-d') ?? now()->addWeek()->format('Y-m-d') }}"
                                                    data-status="{{ $project->status }}"
                                                    title="{{ $project->name }}&#10;Start: {{ $project->start_date?->format('M d, Y') ?? 'Not set' }}&#10;Due: {{ $project->due_date?->format('M d, Y') ?? 'Not set' }}&#10;Progress: {{ $project->progress_percentage }}%">
                                                    <div class="gantt-bar-progress"
                                                        style="width: {{ $project->progress_percentage }}%;"></div>
                                                    <span class="gantt-bar-label">{{ $project->name }}</span>
                                                </div>
                                            </div>

                                            <!-- Task Bars -->
                                            @foreach ($project->tasks as $task)
                                                <div class="gantt-row gantt-bar-row task-of-project-{{ $project->id }}"
                                                    data-task-id="{{ $task->id }}">
                                                    @php
                                                        $taskStart = $task->created_at->format('Y-m-d');
                                                        $taskEnd =
                                                            $task->due_date?->format('Y-m-d') ??
                                                            $task->created_at->addDays(3)->format('Y-m-d');
                                                    @endphp
                                                    <div class="gantt-bar task-bar {{ $task->status }} {{ $task->isOverdue() ? 'overdue' : '' }}"
                                                        data-start="{{ $taskStart }}"
                                                        data-end="{{ $taskEnd }}"
                                                        data-status="{{ $task->status }}"
                                                        title="{{ $task->title }}&#10;Created: {{ $task->created_at->format('M d, Y') }}&#10;Due: {{ $task->due_date?->format('M d, Y') ?? 'Not set' }}&#10;Status: {{ \App\Models\Task::getStatuses()[$task->status] }}&#10;Assignees: {{ $task->assignees->pluck('name')->join(', ') ?: 'None' }}">
                                                        <span
                                                            class="gantt-bar-label small">{{ Str::limit($task->title, 20) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <style>
        .gantt-container {
            overflow: hidden;
            border: 1px solid var(--border-color, #dee2e6);
            border-radius: 8px;
            background: var(--bg-primary, #fff);
        }

        .gantt-wrapper {
            display: flex;
            overflow: hidden;
        }

        /* Sidebar Styles */
        .gantt-sidebar {
            min-width: 280px;
            max-width: 280px;
            border-right: 2px solid var(--border-color, #dee2e6);
            flex-shrink: 0;
            background: var(--bg-secondary, #f8f9fa);
        }

        .gantt-sidebar-header {
            height: 60px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid var(--border-color, #dee2e6);
            background: var(--bg-tertiary, #e9ecef);
            font-weight: 600;
        }

        .gantt-sidebar-content {
            overflow-y: auto;
            max-height: calc(70vh - 60px);
        }

        .gantt-row {
            height: 40px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color, #dee2e6);
        }

        .gantt-row-content {
            padding: 0 16px;
            display: flex;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }

        .gantt-project-row {
            background: var(--bg-primary, #fff);
        }

        .gantt-task-row {
            background: var(--bg-secondary, #f8f9fa);
            font-size: 0.875rem;
        }

        .toggle-project {
            color: var(--text-secondary, #6c757d);
        }

        /* Timeline Styles */
        .gantt-timeline {
            flex: 1;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .gantt-timeline-header {
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 10;
            background: var(--bg-tertiary, #e9ecef);
            border-bottom: 2px solid var(--border-color, #dee2e6);
        }

        .gantt-months-row {
            height: 30px;
            display: flex;
            border-bottom: 1px solid var(--border-color, #dee2e6);
        }

        .gantt-month {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            border-right: 1px solid var(--border-color, #dee2e6);
            background: var(--bg-secondary, #f8f9fa);
        }

        .gantt-days-row {
            height: 30px;
            display: flex;
        }

        .gantt-day {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            border-right: 1px solid var(--border-color, #dee2e6);
            color: var(--text-secondary, #6c757d);
        }

        .gantt-day.weekend {
            background: rgba(0, 0, 0, 0.03);
        }

        .gantt-day.today {
            background: rgba(59, 130, 246, 0.15);
            font-weight: 700;
            color: #3b82f6;
        }

        .gantt-timeline-content {
            position: relative;
            overflow-y: auto;
            max-height: calc(70vh - 60px);
        }

        .gantt-bar-row {
            position: relative;
            background: linear-gradient(90deg,
                    transparent 0px,
                    transparent calc(var(--day-width) - 1px),
                    var(--border-color, #dee2e6) calc(var(--day-width) - 1px),
                    var(--border-color, #dee2e6) var(--day-width));
            background-size: var(--day-width) 100%;
        }

        /* Gantt Bars */
        .gantt-bar {
            position: absolute;
            height: 28px;
            top: 6px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            min-width: 20px;
        }

        .gantt-bar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 5;
        }

        .project-bar {
            height: 24px;
            top: 8px;
        }

        .project-bar.planning {
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }

        .project-bar.in_progress {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .project-bar.on_hold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .project-bar.completed {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .project-bar.cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .task-bar {
            height: 22px;
            top: 9px;
            opacity: 0.85;
        }

        .task-bar.todo {
            background: repeating-linear-gradient(45deg, #6c757d, #6c757d 3px, #868e96 3px, #868e96 6px);
        }

        .task-bar.in_progress {
            background: repeating-linear-gradient(45deg, #3b82f6, #3b82f6 3px, #60a5fa 3px, #60a5fa 6px);
        }

        .task-bar.review {
            background: repeating-linear-gradient(45deg, #f59e0b, #f59e0b 3px, #fbbf24 3px, #fbbf24 6px);
        }

        .task-bar.completed {
            background: repeating-linear-gradient(45deg, #10b981, #10b981 3px, #34d399 3px, #34d399 6px);
        }

        .task-bar.overdue {
            background: repeating-linear-gradient(45deg, #ef4444, #ef4444 3px, #f87171 3px, #f87171 6px) !important;
        }

        .gantt-bar-progress {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px 0 0 4px;
        }

        .gantt-bar-label {
            padding: 0 8px;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Today Marker */
        .gantt-today-marker {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ef4444;
            z-index: 10;
            pointer-events: none;
        }

        .gantt-today-marker::before {
            content: 'Today';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 3px;
            white-space: nowrap;
        }

        /* Dark Mode */
        .dark .gantt-container {
            border-color: var(--border-color);
        }

        .dark .gantt-sidebar {
            background: var(--bg-secondary);
            border-color: var(--border-color);
        }

        .dark .gantt-sidebar-header {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
        }

        .dark .gantt-row {
            border-color: var(--border-color);
        }

        .dark .gantt-project-row {
            background: var(--bg-primary);
        }

        .dark .gantt-task-row {
            background: var(--bg-secondary);
        }

        .dark .gantt-timeline-header {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
        }

        .dark .gantt-month {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .dark .gantt-day {
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .dark .gantt-day.weekend {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Scrollbar styling */
        .gantt-timeline::-webkit-scrollbar,
        .gantt-sidebar-content::-webkit-scrollbar,
        .gantt-timeline-content::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .gantt-timeline::-webkit-scrollbar-track,
        .gantt-sidebar-content::-webkit-scrollbar-track,
        .gantt-timeline-content::-webkit-scrollbar-track {
            background: var(--bg-secondary, #f1f1f1);
        }

        .gantt-timeline::-webkit-scrollbar-thumb,
        .gantt-sidebar-content::-webkit-scrollbar-thumb,
        .gantt-timeline-content::-webkit-scrollbar-thumb {
            background: var(--text-secondary, #888);
            border-radius: 4px;
        }

        /* Sync scrolling */
        .gantt-sidebar-content,
        .gantt-timeline-content {
            scrollbar-width: thin;
        }

        /* Drag to scroll styles */
        .gantt-timeline {
            cursor: grab;
        }

        .gantt-timeline.dragging {
            cursor: grabbing;
            user-select: none;
        }

        .gantt-timeline.dragging * {
            pointer-events: none;
        }

        .gantt-timeline-content {
            cursor: grab;
        }

        .gantt-timeline-content.dragging {
            cursor: grabbing;
            user-select: none;
        }
    </style>

    <script>
        // Configuration
        let dayWidth = 30; // pixels per day
        const minDayWidth = 15;
        const maxDayWidth = 60;

        // Date range from PHP
        const minDate = new Date('{{ $minDate->format('Y-m-d') }}');
        const maxDate = new Date('{{ $maxDate->format('Y-m-d') }}');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            renderTimeline();
            positionBars();
            syncScroll();
            initDragToScroll();
            scrollToToday();
        });

        // Drag to scroll functionality
        function initDragToScroll() {
            const timeline = document.getElementById('ganttTimeline');
            const timelineContent = document.getElementById('timelineContent');

            if (!timeline) return;

            let isDragging = false;
            let startX, startY;
            let scrollLeftStart, scrollTopStart;

            // Function to handle drag start
            function handleDragStart(e) {
                // Don't start drag if clicking on interactive elements
                if (e.target.closest('.gantt-bar') || e.target.closest('a') || e.target.closest('button')) {
                    return;
                }

                isDragging = true;
                timeline.classList.add('dragging');
                if (timelineContent) timelineContent.classList.add('dragging');

                // Get starting position
                startX = e.type === 'mousedown' ? e.pageX : e.touches[0].pageX;
                startY = e.type === 'mousedown' ? e.pageY : e.touches[0].pageY;
                scrollLeftStart = timeline.scrollLeft;
                scrollTopStart = timelineContent ? timelineContent.scrollTop : 0;

                // Prevent text selection
                e.preventDefault();
            }

            // Function to handle drag move
            function handleDragMove(e) {
                if (!isDragging) return;

                const x = e.type === 'mousemove' ? e.pageX : e.touches[0].pageX;
                const y = e.type === 'mousemove' ? e.pageY : e.touches[0].pageY;

                // Calculate distance moved
                const walkX = (x - startX) * 1.5; // Multiplier for faster scrolling
                const walkY = (y - startY) * 1.5;

                // Apply scroll
                timeline.scrollLeft = scrollLeftStart - walkX;

                if (timelineContent) {
                    timelineContent.scrollTop = scrollTopStart - walkY;
                    // Sync sidebar scroll
                    const sidebar = document.querySelector('.gantt-sidebar-content');
                    if (sidebar) {
                        sidebar.scrollTop = timelineContent.scrollTop;
                    }
                }

                e.preventDefault();
            }

            // Function to handle drag end
            function handleDragEnd() {
                if (!isDragging) return;

                isDragging = false;
                timeline.classList.remove('dragging');
                if (timelineContent) timelineContent.classList.remove('dragging');
            }

            // Mouse events
            timeline.addEventListener('mousedown', handleDragStart);
            document.addEventListener('mousemove', handleDragMove);
            document.addEventListener('mouseup', handleDragEnd);
            document.addEventListener('mouseleave', handleDragEnd);

            // Touch events for mobile support
            timeline.addEventListener('touchstart', handleDragStart, {
                passive: false
            });
            document.addEventListener('touchmove', handleDragMove, {
                passive: false
            });
            document.addEventListener('touchend', handleDragEnd);

            // Prevent default drag behavior on the timeline
            timeline.addEventListener('dragstart', (e) => e.preventDefault());
        }

        function renderTimeline() {
            const monthsRow = document.getElementById('monthsRow');
            const daysRow = document.getElementById('daysRow');

            monthsRow.innerHTML = '';
            daysRow.innerHTML = '';

            let currentDate = new Date(minDate);
            let currentMonth = null;
            let monthDays = 0;
            let monthElements = [];

            while (currentDate <= maxDate) {
                const month = currentDate.toLocaleString('default', {
                    month: 'short',
                    year: 'numeric'
                });

                if (month !== currentMonth) {
                    if (currentMonth !== null) {
                        monthElements.push({
                            month: currentMonth,
                            days: monthDays
                        });
                    }
                    currentMonth = month;
                    monthDays = 0;
                }

                monthDays++;

                // Create day cell
                const dayDiv = document.createElement('div');
                dayDiv.className = 'gantt-day';
                dayDiv.style.width = dayWidth + 'px';
                dayDiv.style.minWidth = dayWidth + 'px';

                const dayOfWeek = currentDate.getDay();
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    dayDiv.classList.add('weekend');
                }

                if (currentDate.toDateString() === today.toDateString()) {
                    dayDiv.classList.add('today');
                }

                dayDiv.textContent = currentDate.getDate();
                daysRow.appendChild(dayDiv);

                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Add last month
            if (currentMonth !== null) {
                monthElements.push({
                    month: currentMonth,
                    days: monthDays
                });
            }

            // Render months
            monthElements.forEach(m => {
                const monthDiv = document.createElement('div');
                monthDiv.className = 'gantt-month';
                monthDiv.style.width = (m.days * dayWidth) + 'px';
                monthDiv.style.minWidth = (m.days * dayWidth) + 'px';
                monthDiv.textContent = m.month;
                monthsRow.appendChild(monthDiv);
            });

            // Set CSS variable for grid
            document.documentElement.style.setProperty('--day-width', dayWidth + 'px');
        }

        function positionBars() {
            const bars = document.querySelectorAll('.gantt-bar');

            bars.forEach(bar => {
                const startStr = bar.dataset.start;
                const endStr = bar.dataset.end;

                if (!startStr || !endStr) return;

                const start = new Date(startStr);
                const end = new Date(endStr);

                // Calculate position from minDate
                const startOffset = Math.floor((start - minDate) / (1000 * 60 * 60 * 24));
                const duration = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1);

                bar.style.left = (startOffset * dayWidth) + 'px';
                bar.style.width = (duration * dayWidth - 4) + 'px';
            });

            // Position today marker
            const todayMarker = document.getElementById('todayMarker');
            if (todayMarker) {
                const todayOffset = Math.floor((today - minDate) / (1000 * 60 * 60 * 24));
                if (todayOffset >= 0) {
                    todayMarker.style.left = (todayOffset * dayWidth + dayWidth / 2) + 'px';
                    todayMarker.style.display = 'block';
                } else {
                    todayMarker.style.display = 'none';
                }
            }
        }

        function syncScroll() {
            const sidebar = document.querySelector('.gantt-sidebar-content');
            const timeline = document.querySelector('.gantt-timeline-content');
            const timelineContainer = document.getElementById('ganttTimeline');

            if (sidebar && timeline) {
                sidebar.addEventListener('scroll', function() {
                    timeline.scrollTop = sidebar.scrollTop;
                });

                timeline.addEventListener('scroll', function() {
                    sidebar.scrollTop = timeline.scrollTop;
                });
            }

            // Sync horizontal scroll between header and content
            if (timelineContainer && timeline) {
                timelineContainer.addEventListener('scroll', function() {
                    // Header scrolls with container
                });
            }
        }

        function scrollToToday() {
            const timeline = document.getElementById('ganttTimeline');
            if (!timeline) return;

            const todayOffset = Math.floor((today - minDate) / (1000 * 60 * 60 * 24));
            const scrollPosition = Math.max(0, (todayOffset * dayWidth) - (timeline.clientWidth / 2));

            timeline.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }

        function zoomIn() {
            if (dayWidth < maxDayWidth) {
                dayWidth = Math.min(dayWidth + 5, maxDayWidth);
                renderTimeline();
                positionBars();
            }
        }

        function zoomOut() {
            if (dayWidth > minDayWidth) {
                dayWidth = Math.max(dayWidth - 5, minDayWidth);
                renderTimeline();
                positionBars();
            }
        }

        function toggleProject(projectId) {
            const tasks = document.querySelectorAll('.task-of-project-' + projectId);
            const chevron = document.getElementById('chevron-' + projectId);

            tasks.forEach(task => {
                if (task.style.display === 'none') {
                    task.style.display = 'flex';
                } else {
                    task.style.display = 'none';
                }
            });

            if (chevron) {
                chevron.classList.toggle('fa-chevron-down');
                chevron.classList.toggle('fa-chevron-right');
            }
        }

        function filterByStatus(status) {
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        }
    </script>

</x-app-layout>
