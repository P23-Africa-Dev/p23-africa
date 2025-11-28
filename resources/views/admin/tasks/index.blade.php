<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0 fw-bold">All Tasks</h4>

                            <!-- Project Filter -->
                            <select id="projectFilter" class="form-select form-select-sm" style="width: 200px;"
                                onchange="filterByProject(this.value)">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $projectId == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tasks.kanban', request()->query()) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-columns me-1"></i> Kanban Board
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                <a href="{{ route('admin.tasks.create', request()->query()) }}" class="btn btn-dark">
                                    <i class="fas fa-plus me-1"></i> Add Task
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assignees</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $index => $task)
                                    <tr>
                                        <td>{{ $tasks->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $task->title }}</div>
                                            @if ($task->description)
                                                <small class="text-muted">{!! Str::limit(strip_tags($task->description), 50) !!}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $task->project) }}"
                                                class="text-decoration-none">
                                                {{ $task->project->name }}
                                            </a>
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
                                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false"
                                                    class="position-absolute bg-white rounded shadow-lg border mt-1 py-1"
                                                    style="z-index: 1000; min-width: 130px;">
                                                    @foreach (\App\Models\Task::getStatuses() as $key => $label)
                                                        <button
                                                            @click="
                                                            fetch('{{ route('admin.tasks.update-status', $task) }}', {
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
                                                <span class="{{ $task->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                                    {{ $task->due_date->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.tasks.edit', $task) }}"
                                                        class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.tasks.destroy', $task) }}"
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
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No tasks found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function filterByProject(projectId) {
            const url = new URL(window.location.href);
            if (projectId) {
                url.searchParams.set('project_id', projectId);
            } else {
                url.searchParams.delete('project_id');
            }
            window.location.href = url.toString();
        }
    </script>

</x-app-layout>
