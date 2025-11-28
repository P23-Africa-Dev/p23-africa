<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">All Projects</h4>
                        @if (auth()->user()->hasRole(['admin', 'supervisor']))
                            <a href="{{ route('admin.projects.create') }}" class="btn btn-dark">
                                <i class="fas fa-plus me-2"></i>New Project
                            </a>
                        @endif
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

                    <div class="row g-4">
                        @forelse($projects as $project)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 project-card"
                                    style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0 fw-bold">{{ $project->name }}</h5>
                                            <div class="dropdown" x-data="{ open: false }">
                                                <button @click="open = !open"
                                                    class="btn btn-sm btn-light rounded-circle"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" :class="{ 'show': open }"
                                                    @click.away="open = false">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('admin.projects.show', $project) }}"><i
                                                                class="fas fa-eye me-2"></i>View</a></li>
                                                    @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('admin.projects.edit', $project) }}"><i
                                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                                    @endif
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.projects.destroy', $project) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this project?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger"><i
                                                                        class="fas fa-trash me-2"></i>Delete</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <p class="text-muted small mb-3"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ strip_tags($project->description ?? 'No description available') }}
                                        </p>

                                        <div class="d-flex gap-2 mb-3 flex-wrap">
                                            <!-- Status Dropdown -->
                                            <div x-data="{
                                                open: false,
                                                status: '{{ $project->status }}',
                                                statusLabel: '{{ \App\Models\Project::getStatuses()[$project->status] }}',
                                                badgeClass: '{{ $project->status_badge_class }}'
                                            }">
                                                <button @click="open = !open" class="badge border-0 cursor-pointer"
                                                    :class="badgeClass" style="cursor: pointer; font-size: 0.75rem;">
                                                    <span x-text="statusLabel"></span>
                                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false"
                                                    class="position-absolute bg-white rounded shadow-lg border mt-1 py-1"
                                                    style="z-index: 1000; min-width: 140px;">
                                                    @foreach (\App\Models\Project::getStatuses() as $key => $label)
                                                        <button
                                                            @click="
                                                            fetch('{{ route('admin.projects.update-status', $project) }}', {
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
                                            <span
                                                class="badge {{ $project->priority_badge_class }}">{{ ucfirst($project->priority) }}</span>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Progress</small>
                                                <small class="fw-bold">{{ $project->progress_percentage }}%</small>
                                            </div>
                                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $project->progress_percentage }}%"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center text-muted small">
                                            <span><i class="fas fa-tasks me-1"></i>{{ $project->tasks->count() }}
                                                Tasks</span>
                                            @if ($project->due_date)
                                                <span
                                                    class="{{ $project->due_date->isPast() && $project->status !== 'completed' ? 'text-danger' : '' }}">
                                                    <i
                                                        class="fas fa-calendar me-1"></i>{{ $project->due_date->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                            class="btn btn-outline-primary btn-sm w-100">
                                            View Project <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No projects found</h5>
                                    <p class="text-muted">Get started by creating your first project.</p>
                                    @if (auth()->user()->hasRole(['admin', 'supervisor']))
                                        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Project
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>

</x-app-layout>
