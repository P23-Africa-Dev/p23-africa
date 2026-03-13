<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('CRM Settings') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Configure display preferences for your CRM</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ orgRoute('admin.crm.statuses.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-tags me-2"></i>Manage Statuses
                </a>
                <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Leads
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Info --}}
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-info me-3 fs-5"></i>
                        <span class="text-muted dark:text-gray-400">
                            The selected currency will be used as the display currency across all CRM pages:
                            pipeline values, lead cards, stats, and lead details. It does not convert existing
                            monetary values; it only changes the symbol displayed.
                        </span>
                    </div>
                </div>
            </div>

            {{-- Currency Settings Card --}}
            <div class="card border-0 shadow-sm dark:bg-gray-800">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0 text-dark dark:text-gray-100">
                        <i class="fa fa-money-bill-wave me-2 text-primary"></i>Display Currency
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ orgRoute('admin.crm.settings.update') }}">
                        @csrf

                        <p class="text-muted dark:text-gray-400 mb-4">
                            Choose the currency symbol that will be shown next to all monetary values in your CRM.
                        </p>

                        <div class="row g-3">
                            @foreach ($currencies as $code => $info)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <label
                                        class="currency-option w-100 h-100 {{ $currentCode === $code ? 'selected' : '' }}">
                                        <input type="radio" name="crm_currency" value="{{ $code }}"
                                            class="d-none currency-radio" {{ $currentCode === $code ? 'checked' : '' }}>
                                        <div
                                            class="currency-card p-3 rounded border text-center h-100 d-flex flex-column align-items-center justify-content-center gap-1
                                            {{ $currentCode === $code ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary bg-transparent' }}">
                                            <span
                                                class="fs-4 fw-bold text-dark dark:text-gray-100">{{ $info['symbol'] }}</span>
                                            <span
                                                class="fw-semibold text-dark dark:text-gray-100 small">{{ $code }}</span>
                                            <span class="text-muted dark:text-gray-400"
                                                style="font-size:0.72rem;">{{ $info['name'] }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('crm_currency')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save me-2"></i>Save Settings
                            </button>
                            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <style>
            .currency-option {
                cursor: pointer;
                display: block;
            }

            .currency-option .currency-card {
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .currency-option:hover .currency-card {
                border-color: var(--bs-primary) !important;
                background-color: rgba(13, 110, 253, 0.05) !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .currency-option input:checked+.currency-card {
                border-color: var(--bs-primary) !important;
                background-color: rgba(13, 110, 253, 0.1) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Highlight selected card on click
            document.querySelectorAll('.currency-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.currency-card').forEach(card => {
                        card.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
                        card.classList.add('border-secondary', 'bg-transparent');
                    });
                    const card = this.nextElementSibling;
                    card.classList.remove('border-secondary', 'bg-transparent');
                    card.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
                });
            });
        </script>
    @endpush
</x-app-layout>
