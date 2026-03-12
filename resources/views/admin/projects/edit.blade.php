<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Edit: {{ $project->name }}</h4>
                        <a href="{{ orgRoute('admin.projects.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Projects
                        </a>
                    </div>

                    <form action="{{ orgRoute('admin.projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Project Name <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $project->name) }}" required
                                placeholder="Enter project name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" placeholder="Describe the project objectives and scope">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $project->status) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-semibold">Priority <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority"
                                    name="priority" required>
                                    @foreach ($priorities as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('priority', $project->priority) === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date"
                                    value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="due_date" class="form-label fw-semibold">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                    id="due_date" name="due_date"
                                    value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Project
                            </button>
                            <a href="{{ orgRoute('admin.projects.index') }}"
                                class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
