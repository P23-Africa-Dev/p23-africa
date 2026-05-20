<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">WhatsApp Templates</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Store and manage reusable message
                templates for future Meta-approved workflows.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header fw-semibold">Create Template</div>
                    <div class="card-body">
                        <form method="POST" action="{{ orgRoute('admin.whatsapp.templates.store') }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control" placeholder="utility"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Language</label>
                                <input type="text" name="language" class="form-control" placeholder="en" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Template JSON</label>
                                <textarea name="template_json" class="form-control" rows="8" required>{
  "components": [
    {
      "type": "BODY",
      "text": "Hello {{ 1 }}, this is a placeholder template message."
    }
  ]
}</textarea>
                            </div>
                            <button class="btn btn-success w-100" type="submit">Save Template</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header fw-semibold">Existing Templates</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Template</th>
                                        <th>Status</th>
                                        <th>JSON</th>
                                        <th class="pe-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($templates as $template)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-semibold">{{ $template->name }}</div>
                                                <small class="text-muted">{{ $template->category }} |
                                                    {{ $template->language }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $template->status === 'approved' ? 'bg-success' : ($template->status === 'rejected' ? 'bg-danger' : ($template->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')) }}">{{ ucfirst($template->status) }}</span>
                                            </td>
                                            <td style="min-width:260px;">
                                                <details>
                                                    <summary class="small">Preview JSON</summary>
                                                    <pre class="small mt-2 mb-0">{{ json_encode($template->template_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </details>
                                            </td>
                                            <td class="pe-3 text-end" style="min-width: 260px;">
                                                <form method="POST"
                                                    action="{{ orgRoute('admin.whatsapp.templates.update', ['template' => $template->id]) }}"
                                                    class="d-inline-flex gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="name" value="{{ $template->name }}">
                                                    <input type="hidden" name="category"
                                                        value="{{ $template->category }}">
                                                    <input type="hidden" name="language"
                                                        value="{{ $template->language }}">
                                                    <input type="hidden" name="template_json"
                                                        value='@json($template->template_json)'>
                                                    <select name="status" class="form-select form-select-sm"
                                                        style="width:130px;">
                                                        @foreach ($statuses as $status)
                                                            <option value="{{ $status }}"
                                                                {{ $template->status === $status ? 'selected' : '' }}>
                                                                {{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-sm btn-outline-success"
                                                        type="submit">Update</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No templates created
                                                yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">{{ $templates->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
