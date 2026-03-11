<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Request Leave</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">
                    Submit a new leave request to your supervisor
                </p>
            </div>
            <a href="{{ url($orgSlug ?? '' ? '/' . ($orgSlug ?? '') . '/admin/leave-requests' : '/admin/leave-requests') }}"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card anim-in">
                <div class="card-body" style="padding:1.5rem;">

                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size:.875rem;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ url($orgSlug ?? '' ? '/' . ($orgSlug ?? '') . '/admin/leave-requests' : '/admin/leave-requests') }}">
                        @csrf

                        {{-- Leave Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label" style="font-weight:600;font-size:.875rem;">Leave
                                Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">Select leave type...</option>
                                @foreach (\App\Models\LeaveRequest::TYPES as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label"
                                    style="font-weight:600;font-size:.875rem;">Start Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label" style="font-weight:600;font-size:.875rem;">End
                                    Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        {{-- Duration Preview --}}
                        <div class="mb-3" x-data="durationCalc()" x-init="calc()">
                            <div class="alert alert-info" style="font-size:.8125rem;padding:.625rem 1rem;"
                                x-show="days > 0" x-cloak>
                                <i class="bi bi-calendar-range me-1"></i>
                                <strong x-text="days"></strong> working day<span x-show="days > 1">s</span>
                                (<span x-text="startFormatted"></span> — <span x-text="endFormatted"></span>)
                            </div>
                        </div>

                        {{-- Supervisor --}}
                        <div class="mb-3">
                            <label for="supervisor_id" class="form-label"
                                style="font-weight:600;font-size:.875rem;">Supervisor <span
                                    class="text-danger">*</span></label>
                            <select name="supervisor_id" id="supervisor_id" class="form-select" required>
                                <option value="">Select supervisor to send request to...</option>
                                @foreach ($supervisors as $sup)
                                    <option value="{{ $sup->id }}"
                                        {{ old('supervisor_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                        @if ($sup->hasRole('admin'))
                                            (Admin)
                                        @elseif($sup->hasRole('supervisor'))
                                            (Supervisor)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if ($supervisors->isEmpty())
                                <div class="text-muted mt-1" style="font-size:.8125rem;">
                                    <i class="bi bi-info-circle me-1"></i>No supervisors found in your organization.
                                </div>
                            @endif
                        </div>

                        {{-- Reason --}}
                        <div class="mb-4">
                            <label for="reason" class="form-label" style="font-weight:600;font-size:.875rem;">Reason
                                <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" rows="4" class="form-control"
                                placeholder="Explain the reason for your leave request..." required>{{ old('reason') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end gap-2" x-data="{ submitting: false }">
                            <a href="{{ url($orgSlug ?? '' ? '/' . ($orgSlug ?? '') . '/admin/leave-requests' : '/admin/leave-requests') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1"
                                :disabled="submitting"
                                @click="if(!$el.form.checkValidity()) return; submitting = true; $nextTick(() => $el.form.submit())">
                                <template x-if="!submitting">
                                    <span><i class="bi bi-send me-1"></i>Submit Leave Request</span>
                                </template>
                                <template x-if="submitting">
                                    <span><span class="spinner-border spinner-border-sm me-1"
                                            role="status"></span>Sending Request...</span>
                                </template>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function durationCalc() {
                return {
                    days: 0,
                    startFormatted: '',
                    endFormatted: '',
                    init() {
                        this.$watch('days', () => {});
                        const s = document.getElementById('start_date');
                        const e = document.getElementById('end_date');
                        s.addEventListener('change', () => this.calc());
                        e.addEventListener('change', () => this.calc());
                    },
                    calc() {
                        const s = document.getElementById('start_date').value;
                        const e = document.getElementById('end_date').value;
                        if (!s || !e) {
                            this.days = 0;
                            return;
                        }
                        const start = new Date(s);
                        const end = new Date(e);
                        if (end < start) {
                            this.days = 0;
                            return;
                        }
                        let count = 0;
                        let d = new Date(start);
                        while (d <= end) {
                            const day = d.getDay();
                            if (day !== 0 && day !== 6) count++;
                            d.setDate(d.getDate() + 1);
                        }
                        this.days = count;
                        this.startFormatted = start.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        });
                        this.endFormatted = end.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
