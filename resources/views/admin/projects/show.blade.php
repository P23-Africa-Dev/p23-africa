<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Project Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h3 class="mb-0 fw-bold">{{ $project->name }}</h3>
                                <span
                                    class="badge {{ $project->status_badge_class }}">{{ \App\Models\Project::getStatuses()[$project->status] }}</span>
                                <span
                                    class="badge {{ $project->priority_badge_class }}">{{ ucfirst($project->priority) }}
                                    Priority</span>
                            </div>
                            <p class="text-muted mb-0">
                                {{ strip_tags($project->description ?? 'No description available') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ orgRoute('admin.projects.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <a href="{{ orgRoute('admin.tasks.kanban', ['project_id' => $project->id]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-columns me-1"></i> Kanban Board
                                </a>
                                <a href="{{ orgRoute('admin.projects.edit', $project) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>Started: {{ $project->start_date?->format('M d, Y') ?? 'Not set' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div
                                class="d-flex align-items-center {{ $project->due_date?->isPast() && $project->status !== 'completed' ? 'text-danger' : 'text-muted' }}">
                                <i class="fas fa-clock me-2"></i>
                                <span>Due: {{ $project->due_date?->format('M d, Y') ?? 'Not set' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-user me-2"></i>
                                <span>Created by: {{ $project->creator->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-tasks me-2"></i>
                                <span>{{ $project->tasks->count() }} Tasks</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-semibold">Overall Progress</small>
                            <small class="fw-bold">{{ $project->progress_percentage }}%</small>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $project->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Quick Add (for Admin/Supervisor) -->
            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6">
                        <h5 class="fw-bold mb-3"><i class="fas fa-plus-circle me-2"></i>Quick Add Task</h5>
                        <form action="{{ orgRoute('admin.tasks.store') }}" method="POST" class="row g-3">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <input type="hidden" name="status" value="todo">

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="title" placeholder="Task title"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="priority">
                                    @foreach ($priorities as $key => $label)
                                        <option value="{{ $key }}" {{ $key === 'medium' ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="due_date" placeholder="Due date">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="assignees[]" multiple>
                                    @foreach ($assignableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tasks List -->
            <div class="bg-white shadow-sm sm:rounded-lg" style="overflow: visible;">
                <div class="p-6">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-list-check me-2"></i>Tasks</h5>
                        @if (auth()->user()->hasRole(['admin', 'supervisor']))
                            <a href="{{ orgRoute('admin.tasks.create', ['project_id' => $project->id]) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Add Task
                            </a>
                        @endif
                    </div>

                    @if ($project->tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Task</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assignees</th>
                                        <th>Due Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->tasks as $task)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $task->title }}</div>
                                                @if ($task->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit(strip_tags($task->description), 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Status Dropdown -->
                                                <div x-data="{
                                                    open: false,
                                                    status: '{{ $task->status }}',
                                                    statusLabel: '{{ \App\Models\Task::getStatuses()[$task->status] }}',
                                                    badgeClass: '{{ $task->status_badge_class }}'
                                                }">
                                                    <button @click="open = !open" class="badge border-0"
                                                        :class="badgeClass" style="cursor: pointer;">
                                                        <span x-text="statusLabel"></span>
                                                        <i class="fas fa-chevron-down ms-1"
                                                            style="font-size: 0.6rem;"></i>
                                                    </button>
                                                    <div x-show="open" @click.away="open = false"
                                                        class="position-absolute bg-white rounded shadow-lg border mt-1 py-1"
                                                        style="z-index: 1000; min-width: 130px;">
                                                        @foreach (\App\Models\Task::getStatuses() as $key => $label)
                                                            <button
                                                                @click="
                                                                fetch('{{ orgRoute('admin.tasks.update-status', $task) }}', {
                                                                    method: 'PATCH',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                    },
                                                                    body: JSON.stringify({ status: '{{ $key }}' })
                                                                })
                                                                .then(res => res.json())
                                                                .then(data => {
                                                                    if(data.success) {
                                                                        status = data.status;
                                                                        statusLabel = data.status_label;
                                                                        badgeClass = data.badge_class;
                                                                    }
                                                                });
                                                                open = false;
                                                            "
                                                                class="dropdown-item small py-2"
                                                                style="cursor: pointer;">{{ $label }}</button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $task->priority_badge_class }}">{{ ucfirst($task->priority) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @foreach ($task->assignees->take(3) as $assignee)
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                            style="width: 28px; height: 28px; margin-left: -8px; border: 2px solid white; font-size: 0.7rem;"
                                                            title="{{ $assignee->name }}">
                                                            {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                                        </div>
                                                    @endforeach
                                                    @if ($task->assignees->count() > 3)
                                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                                            style="width: 28px; height: 28px; margin-left: -8px; border: 2px solid white; font-size: 0.7rem;">
                                                            +{{ $task->assignees->count() - 3 }}
                                                        </div>
                                                    @endif
                                                    @if ($task->assignees->isEmpty())
                                                        <span class="text-muted small">Unassigned</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($task->due_date)
                                                    <span
                                                        class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                                        {{ $task->due_date->format('M d, Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ orgRoute('admin.tasks.edit', $task) }}"
                                                            class="btn btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ orgRoute('admin.tasks.destroy', $task) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Delete this task?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tasks yet</h5>
                            <p class="text-muted">Add tasks to track progress on this project.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
