<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Site Errors</h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Problems visitors hit, kept here for reference</p>
            </div>
            <a href="{{ orgRoute('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-4">
                <div class="input-group">
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search path, message, or exception">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>Page</th>
                                <th>Exception</th>
                                <th>Message</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($errors as $error)
                                <tr>
                                    <td class="text-nowrap">{{ $error->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.site-errors.show', $error) }}">
                                            {{ $error->method }} {{ $error->path }}
                                        </a>
                                    </td>
                                    <td>{{ $error->exceptionName() }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($error->message, 120) }}</td>
                                    <td class="text-end text-nowrap">
                                        <textarea id="site-error-log-{{ $error->id }}" class="visually-hidden" readonly aria-hidden="true">{{ $error->fullErrorLog() }}</textarea>
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm copy-site-error-log"
                                            data-source="site-error-log-{{ $error->id }}"
                                            title="Copy full error"
                                            aria-label="Copy full error">
                                            <i class="fa fa-copy" aria-hidden="true"></i>
                                            <span class="visually-hidden" data-label>Copy full error</span>
                                        </button>
                                        <a href="{{ route('admin.site-errors.show', $error) }}"
                                            class="btn btn-outline-secondary btn-sm"
                                            title="View error details"
                                            aria-label="View error details">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted p-4">No site errors have been recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">{{ $errors->links() }}</div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.copy-site-error-log').forEach(function(button) {
            button.addEventListener('click', async function() {
                const source = document.getElementById(this.dataset.source);
                const label = this.querySelector('[data-label]');
                const text = source?.value || '';
                const original = label?.textContent || 'Copy full error';

                try {
                    if (navigator.clipboard?.writeText) {
                        await navigator.clipboard.writeText(text);
                    } else if (source) {
                        source.classList.remove('visually-hidden');
                        source.select();
                        document.execCommand('copy');
                        source.classList.add('visually-hidden');
                    }
                    if (label) label.textContent = 'Copied';
                } catch (error) {
                    if (label) label.textContent = 'Copy failed';
                }

                setTimeout(function() {
                    if (label) label.textContent = original;
                }, 2000);
            });
        });
    </script>
</x-app-layout>
