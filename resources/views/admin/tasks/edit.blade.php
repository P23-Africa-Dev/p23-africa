<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Edit: {{ $task->title }}</h4>
                        <a href="{{ route('admin.tasks.kanban', ['project_id' => $task->project_id]) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Board
                        </a>
                    </div>

                    <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Task Title <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title', $task->title) }}" required
                                placeholder="Enter task title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="Describe the task in detail">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="project_id" class="form-label fw-semibold">Project <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('project_id') is-invalid @enderror" id="project_id"
                                    name="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $task->status) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-semibold">Priority <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                    name="priority" required>
                                    @foreach ($priorities as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('priority', $task->priority) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="due_date" class="form-label fw-semibold">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                    id="due_date" name="due_date"
                                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="assignees" class="form-label fw-semibold">Assign To</label>
                            @php
                                $currentAssignees = $task->assignees->pluck('id')->toArray();
                            @endphp
                            <select class="form-select @error('assignees') is-invalid @enderror" id="assignees"
                                name="assignees[]" multiple style="height: 150px;">
                                @foreach ($assignableUsers as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, old('assignees', $currentAssignees)) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->getRoleNames()->first() }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                            @error('assignees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Task
                            </button>
                            <a href="{{ route('admin.tasks.kanban', ['project_id' => $task->project_id]) }}"
                                class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
