<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $siteError->exceptionName() }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    {{ $siteError->created_at->format('d M Y H:i:s') }}
                </p>
            </div>
            <a href="{{ route('admin.site-errors.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> All site errors
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Page</dt>
                        <dd class="col-sm-9">{{ $siteError->method }} {{ $siteError->path }}@if ($siteError->query)?{{ $siteError->query }}@endif</dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{ $siteError->status }}</dd>
                        <dt class="col-sm-3">Exception</dt>
                        <dd class="col-sm-9">{{ $siteError->exception_class }}</dd>
                        <dt class="col-sm-3">Location</dt>
                        <dd class="col-sm-9">{{ $siteError->file }}:{{ $siteError->line }}</dd>
                        <dt class="col-sm-3">Signed-in user</dt>
                        <dd class="col-sm-9">
                            @if ($siteError->user)
                                {{ $siteError->user->name }} ({{ $siteError->user->email }})
                            @else
                                Guest
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">Message</div>
                <div class="card-body">
                    <pre class="mb-0" style="white-space:pre-wrap;">{{ $siteError->message }}</pre>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header">Stack</div>
                <div class="card-body">
                    <pre class="mb-0" style="white-space:pre-wrap;background:#0f172a;color:#e2e8f0;padding:1rem;border-radius:8px;">{{ $siteError->trace }}</pre>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
