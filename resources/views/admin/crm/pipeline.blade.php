<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Pipeline') }}
                    @if (isset($currentDrive))
                        <span class="badge ms-2"
                            style="background-color: {{ $currentDrive->color ?? '#6c757d' }}; font-size: 0.6em; vertical-align: middle;">
                            {{ $currentDrive->name }}
                        </span>
                    @endif
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Drag and drop leads to update their status</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Drive Switcher -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-folder"></i>
                        <span class="mobile-btn-text ms-2">
                            @if (request('drive') && request('drive') !== 'all')
                                @if (request('drive') === 'unassigned')
                                    No Pipeline
                                @elseif (isset($currentDrive))
                                    {{ $currentDrive->name }}
                                @else
                                    Select Pipeline
                                @endif
                            @else
                                All Pipelines
                            @endif
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ !request('drive') || request('drive') === 'all' ? 'active' : '' }}"
                                href="{{ orgRoute('admin.crm.pipeline', ['drive' => 'all']) }}">
                                <i class="fa fa-globe me-2 text-muted"></i>All Pipelines
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @forelse ($drives ?? [] as $drive)
                            <li>
                                <a class="dropdown-item {{ request('drive') == $drive->id ? 'active' : '' }}"
                                    href="{{ orgRoute('admin.crm.pipeline', ['drive' => $drive->id]) }}">
                                    <span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background-color: {{ $drive->color ?? '#6c757d' }};"></span>
                                    {{ $drive->name }}
                                    <span class="badge bg-secondary ms-2">{{ $drive->leads()->count() }}</span>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">No drives created</span></li>
                        @endforelse
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('drive') === 'unassigned' ? 'active' : '' }}"
                                href="{{ orgRoute('admin.crm.pipeline', ['drive' => 'unassigned']) }}">
                                <i class="fa fa-inbox me-2 text-muted"></i>No Pipeline
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-primary" href="{{ orgRoute('admin.crm.drives.index') }}">
                                <i class="fa fa-cog me-2"></i>Manage Pipelines
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ orgRoute('admin.crm.index', request()->only('drive')) }}" class="btn btn-outline-secondary"
                    title="List View">
                    <i class="fa fa-list"></i><span class="mobile-btn-text ms-2">List View</span>
                </a>
                @hasanyrole('admin|supervisor')
                    <a href="{{ orgRoute('admin.crm.statuses.index') }}" class="btn btn-outline-dark"
                        title="Manage Statuses">
                        <i class="fa fa-tags"></i><span class="mobile-btn-text ms-2">Statuses</span>
                    </a>
                    <a href="{{ orgRoute('admin.crm.settings') }}"
                        class="btn btn-outline-secondary d-none d-md-inline-flex" title="CRM Settings">
                        <i class="fa fa-cog"></i><span class="mobile-btn-text ms-2">Settings</span>
                    </a>
                @endhasanyrole
                <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-outline-success d-none d-md-inline-flex"
                    title="Import Leads">
                    <i class="fa fa-file-import"></i><span class="mobile-btn-text ms-2">Import</span>
                </a>
                <a href="{{ orgRoute('admin.crm.create') }}" class="btn btn-primary" title="Add New Lead">
                    <i class="fa fa-plus"></i><span class="mobile-btn-text ms-2">Add Lead</span>
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        /* ═══════════════════════════════════════════
           DESKTOP / TABLET — existing kanban layout
        ═══════════════════════════════════════════ */
        .pipeline-container {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 20px;
            min-height: calc(100vh - 250px);
            /* smooth momentum scroll on iOS */
            -webkit-overflow-scrolling: touch;
            /* always show scrollbar track so users know they can scroll */
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, .25) rgba(0, 0, 0, .05);
        }

        .pipeline-container::-webkit-scrollbar {
            height: 10px;
        }

        .pipeline-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, .05);
            border-radius: 5px;
        }

        .pipeline-container::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, .22);
            border-radius: 5px;
        }

        .pipeline-container::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, .38);
        }

        .pipeline-column {
            min-width: 260px;
            max-width: 280px;
            flex-shrink: 0;
        }

        .pipeline-header {
            padding: 12px 16px;
            border-radius: 10px 10px 0 0;
            color: white;
            font-weight: 600;
        }

        /* Pipeline header colors - dynamically generated from lead statuses */
        @foreach ($leadStatuses as $ls)
            .pipeline-header.{{ $ls->slug }},
            .mobile-tab-btn[data-status="{{ $ls->slug }}"].active,
            .mobile-column-header.{{ $ls->slug }} {
                background: {{ str_starts_with($ls->color, '#') ? $ls->color : 'var(--bs-' . $ls->color . ')' }};

                @if (in_array($ls->color, ['warning', '#ffc107', 'light']))
                    color: #212529 !important;
                @endif
            }

            .mobile-tab-btn[data-status="{{ $ls->slug }}"].active {
                border-bottom: 3px solid {{ str_starts_with($ls->color, '#') ? $ls->color : 'var(--bs-' . $ls->color . ')' }};
            }
        @endforeach

        .pipeline-body {
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
            padding: 12px;
            min-height: 400px;
            max-height: calc(100vh - 350px);
            overflow-y: auto;
            transition: background-color 0.2s;
        }

        .pipeline-body::-webkit-scrollbar {
            width: 8px;
        }

        .pipeline-body::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, .05);
            border-radius: 4px;
        }

        .pipeline-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, .2);
            border-radius: 4px;
        }

        .pipeline-body::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, .3);
        }

        .dark .pipeline-body {
            background: #374151;
        }

        .pipeline-body.sortable-drag-over {
            background: #e3f2fd;
        }

        .dark .pipeline-body.sortable-drag-over {
            background: #4b5563;
        }

        /* Shared card styles (used on both desktop and mobile) */
        .lead-card {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            transition: all 0.2s;
            cursor: grab;
            text-decoration: none !important;
            display: block;
            position: relative;
        }

        .lead-card:active {
            cursor: grabbing;
        }

        .dark .lead-card {
            background: #1f2937;
        }

        .lead-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
            transform: translateY(-2px);
        }

        .lead-card.sortable-ghost {
            opacity: 0.4;
            background: #e3f2fd;
        }

        .lead-card.sortable-chosen {
            box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
            transform: rotate(3deg);
        }

        .lead-card .lead-name {
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .dark .lead-card .lead-name {
            color: #f3f4f6;
        }

        .lead-card .lead-company {
            color: #007bff;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .lead-card .lead-value {
            color: #28a745;
            font-weight: 600;
        }

        .lead-card .lead-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .pipeline-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, .2);
        }

        .lead-card-link {
            position: absolute;
            top: 8px;
            right: 8px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .lead-card:hover .lead-card-link {
            opacity: 1;
        }

        /* ═══════════════════════════════════════════
           MOBILE — override / additions (< 768 px)
        ═══════════════════════════════════════════ */
        @media (max-width: 767.98px) {

            /* ── Header: hide text labels, keep icons ── */
            .mobile-btn-text {
                display: none;
            }

            /* ── Stats: 2 × 2 compact grid ── */
            .pipeline-stats-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                margin-bottom: 12px;
            }

            .pipeline-stats-grid .card-body {
                padding: 10px 12px;
            }

            .pipeline-stats-grid .card-body h3 {
                font-size: 1.2rem;
            }

            .pipeline-stats-grid .card-body h6 {
                font-size: 0.7rem;
            }

            .pipeline-stats-grid .card-body .fa-2x {
                font-size: 1.2rem;
            }

            .pipeline-stats-grid .flex-shrink-0 {
                padding: 8px !important;
            }

            /* ── Desktop kanban: hide on mobile ── */
            .pipeline-container {
                display: none !important;
            }

            /* ── Mobile pipeline: shown only on mobile ── */
            .mobile-pipeline {
                display: block;
            }

            /* Status tab strip */
            .mobile-tab-strip {
                display: flex;
                overflow-x: auto;
                gap: 0;
                background: #fff;
                border-bottom: 1px solid #dee2e6;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .dark .mobile-tab-strip {
                background: #1f2937;
                border-color: #374151;
            }

            .mobile-tab-strip::-webkit-scrollbar {
                display: none;
            }

            .mobile-tab-btn {
                flex-shrink: 0;
                padding: 10px 14px;
                border: none;
                background: transparent;
                font-size: 0.8rem;
                font-weight: 600;
                color: #6c757d;
                cursor: pointer;
                white-space: nowrap;
                border-bottom: 3px solid transparent;
                transition: color 0.15s, border-color 0.15s;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .dark .mobile-tab-btn {
                color: #9ca3af;
            }

            .mobile-tab-btn.active {
                color: #fff;
                border-radius: 6px 6px 0 0;
            }

            .mobile-tab-btn .tab-badge {
                background: rgba(255, 255, 255, .3);
                color: inherit;
                font-size: 0.7rem;
                padding: 1px 6px;
                border-radius: 10px;
            }

            .mobile-tab-btn:not(.active) .tab-badge {
                background: #e9ecef;
                color: #495057;
            }

            .dark .mobile-tab-btn:not(.active) .tab-badge {
                background: #374151;
                color: #d1d5db;
            }

            /* Column panels */
            .mobile-column-panel {
                display: none;
            }

            .mobile-column-panel.active {
                display: block;
            }

            .mobile-column-header {
                padding: 12px 16px;
                color: white;
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-radius: 10px 10px 0 0;
                margin-top: 8px;
            }

            /* Mobile cards: full-width, no grab cursor */
            .mobile-pipeline .lead-card {
                cursor: default;
                margin-bottom: 10px;
            }

            .mobile-pipeline .lead-card:hover {
                transform: none;
            }

            /* Always-visible view link on mobile */
            .lead-card-link {
                opacity: 1 !important;
                position: static;
                display: inline-flex;
                align-items: center;
                font-size: 0.8rem;
            }

            /* Move-to button on mobile card */
            .mobile-move-btn {
                font-size: 0.75rem;
                padding: 3px 8px;
            }

            .mobile-move-btn .dropdown-menu {
                min-width: 170px;
                font-size: 0.85rem;
            }

            /* Mobile card actions row */
            .mobile-card-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 8px;
                padding-top: 8px;
                border-top: 1px solid #f0f0f0;
                gap: 6px;
            }

            .dark .mobile-card-actions {
                border-color: #374151;
            }

            /* Empty placeholder */
            .mobile-pipeline .empty-placeholder {
                padding: 40px 0;
                background: #f8f9fa;
                border-radius: 0 0 10px 10px;
                color: #6c757d;
                text-align: center;
            }

            .dark .mobile-pipeline .empty-placeholder {
                background: #374151;
            }

            /* Column value summary */
            .mobile-column-value {
                font-size: 0.8rem;
                opacity: 0.85;
            }
        }

        /* Hide mobile pipeline on desktop/tablet */
        @media (min-width: 768px) {
            .mobile-pipeline {
                display: none;
            }
        }
    </style>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="successToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fa fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="successMessage"></div>
        </div>
        <div id="errorToast" class="toast" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fa fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="errorMessage"></div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Pipeline Summary -->
            <div class="row mb-4 pipeline-stats-grid">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-users text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total in Pipeline</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ collect($pipeline)->sum('count') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-dollar-sign text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Pipeline Value</h6>
                                    @php
                                        $nonLostStatuses = $leadStatuses
                                            ->where('is_lost', false)
                                            ->pluck('slug')
                                            ->toArray();
                                        $totalPipelineValue = collect($pipeline)
                                            ->filter(fn($data, $status) => in_array($status, $nonLostStatuses))
                                            ->sum('value');
                                    @endphp
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ $crmCurrency }}{{ number_format($totalPipelineValue) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-trophy text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Won Value</h6>
                                    @php
                                        $wonSlugs = $leadStatuses->where('is_won', true)->pluck('slug')->toArray();
                                        $wonValue = collect($pipeline)
                                            ->filter(fn($data, $status) => in_array($status, $wonSlugs))
                                            ->sum('value');
                                    @endphp
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ $crmCurrency }}{{ number_format($wonValue) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-percentage text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Onboarding Rate</h6>
                                    @php
                                        $wonCount = collect($pipeline)
                                            ->filter(fn($data, $status) => in_array($status, $wonSlugs))
                                            ->sum('count');
                                        $lostSlugs = $leadStatuses->where('is_lost', true)->pluck('slug')->toArray();
                                        $lostCount = collect($pipeline)
                                            ->filter(fn($data, $status) => in_array($status, $lostSlugs))
                                            ->sum('count');
                                        $total = $wonCount + $lostCount;
                                        $winRate = $total > 0 ? round(($wonCount / $total) * 100) : 0;
                                    @endphp
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $winRate }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Board (desktop / tablet) -->
            <div class="pipeline-container">
                @foreach ($statuses as $statusKey => $statusLabel)
                    @php
                        $columnData = $pipeline[$statusKey] ?? ['leads' => collect(), 'count' => 0, 'value' => 0];
                    @endphp
                    <div class="pipeline-column">
                        <div class="pipeline-header {{ $statusKey }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $statusLabel }}</span>
                                <span class="badge bg-white text-dark"
                                    id="count-{{ $statusKey }}">{{ $columnData['count'] }}</span>
                            </div>
                            <div class="pipeline-stats">
                                <span
                                    id="value-{{ $statusKey }}">{{ $crmCurrency }}{{ number_format($columnData['value']) }}</span>
                            </div>
                        </div>
                        <div class="pipeline-body sortable-column" data-status="{{ $statusKey }}">
                            @forelse($columnData['leads'] as $lead)
                                <div class="lead-card" data-id="{{ $lead->id }}"
                                    data-value="{{ $lead->estimated_value ?? 0 }}">
                                    <a href="{{ orgRoute('admin.crm.show', $lead) }}"
                                        class="lead-card-link text-primary" title="View Details"
                                        onclick="event.stopPropagation();">
                                        <i class="fa fa-external-link-alt"></i>
                                    </a>
                                    <div class="lead-name">{{ $lead->full_name }}</div>
                                    @if ($lead->company)
                                        <div class="lead-company">{{ $lead->company }}</div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($lead->estimated_value)
                                            <span
                                                class="lead-value">{{ $crmCurrency }}{{ number_format($lead->estimated_value) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                        <span
                                            class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                    </div>
                                    <div class="lead-meta mt-2">
                                        <div class="d-flex justify-content-between">
                                            @if ($lead->assignedUser)
                                                <span><i
                                                        class="fa fa-user me-1"></i>{{ $lead->assignedUser->name }}</span>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                            <span>{{ $lead->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($lead->next_follow_up)
                                            <div class="mt-1 {{ $lead->isFollowUpOverdue() ? 'text-danger' : '' }}">
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $lead->next_follow_up->format('M d') }}
                                                @if ($lead->isFollowUpOverdue())
                                                    <span class="text-danger">(Overdue)</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4 empty-placeholder">
                                    <i class="fa fa-inbox mb-2"></i>
                                    <p class="mb-0 small">No leads</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ════════════════════════════════════════════
                 MOBILE PIPELINE — tab-per-status layout
                 Shown only on screens < 768px via CSS.
            ════════════════════════════════════════════ --}}
            <div class="mobile-pipeline">

                {{-- Sticky tab strip --}}
                <div class="mobile-tab-strip" id="mobileTabStrip">
                    @php $firstStatus = true; @endphp
                    @foreach ($statuses as $statusKey => $statusLabel)
                        @php
                            $colCount = $pipeline[$statusKey]['count'] ?? 0;
                        @endphp
                        <button type="button" class="mobile-tab-btn {{ $firstStatus ? 'active' : '' }}"
                            data-status="{{ $statusKey }}" data-panel="mobile-panel-{{ $statusKey }}">
                            {{ $statusLabel }}
                            <span class="tab-badge" id="mobile-count-{{ $statusKey }}">{{ $colCount }}</span>
                        </button>
                        @php $firstStatus = false; @endphp
                    @endforeach
                </div>

                {{-- Column panels --}}
                @php $firstStatus = true; @endphp
                @foreach ($statuses as $statusKey => $statusLabel)
                    @php
                        $columnData = $pipeline[$statusKey] ?? ['leads' => collect(), 'count' => 0, 'value' => 0];
                        $otherStatuses = collect($statuses)->reject(fn($l, $k) => $k === $statusKey);
                    @endphp
                    <div class="mobile-column-panel {{ $firstStatus ? 'active' : '' }}"
                        id="mobile-panel-{{ $statusKey }}" data-status="{{ $statusKey }}">

                        {{-- Column header --}}
                        <div class="mobile-column-header {{ $statusKey }}">
                            <span>{{ $statusLabel }}</span>
                            <span class="mobile-column-value">
                                <span
                                    id="mobile-value-{{ $statusKey }}">{{ $crmCurrency }}{{ number_format($columnData['value']) }}</span>
                            </span>
                        </div>

                        {{-- Leads list --}}
                        <div class="p-2"
                            style="background:#f8f9fa; border-radius: 0 0 10px 10px; min-height:200px;"
                            id="mobile-body-{{ $statusKey }}">
                            @forelse ($columnData['leads'] as $lead)
                                <div class="lead-card" data-id="{{ $lead->id }}"
                                    data-status="{{ $statusKey }}"
                                    data-value="{{ $lead->estimated_value ?? 0 }}">

                                    <div class="lead-name">{{ $lead->full_name }}</div>
                                    @if ($lead->company)
                                        <div class="lead-company">{{ $lead->company }}</div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($lead->estimated_value)
                                            <span
                                                class="lead-value">{{ $crmCurrency }}{{ number_format($lead->estimated_value) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                        <span
                                            class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                    </div>

                                    <div class="lead-meta mt-1">
                                        <div class="d-flex justify-content-between">
                                            @if ($lead->assignedUser)
                                                <span><i
                                                        class="fa fa-user me-1"></i>{{ $lead->assignedUser->name }}</span>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                            <span>{{ $lead->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($lead->next_follow_up)
                                            <div class="mt-1 {{ $lead->isFollowUpOverdue() ? 'text-danger' : '' }}">
                                                <i
                                                    class="fa fa-clock me-1"></i>{{ $lead->next_follow_up->format('M d') }}
                                                @if ($lead->isFollowUpOverdue())
                                                    <span>(Overdue)</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Card actions: View + Move To --}}
                                    <div class="mobile-card-actions">
                                        <a href="{{ orgRoute('admin.crm.show', $lead) }}"
                                            class="btn btn-sm btn-outline-primary lead-card-link" title="View Lead">
                                            <i class="fa fa-eye me-1"></i>View
                                        </a>

                                        @if ($otherStatuses->isNotEmpty())
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary dropdown-toggle mobile-move-btn"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-exchange-alt me-1"></i>Move to
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach ($otherStatuses as $otherKey => $otherLabel)
                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item mobile-status-change"
                                                                data-lead-id="{{ $lead->id }}"
                                                                data-new-status="{{ $otherKey }}"
                                                                data-is-lost="{{ in_array($otherKey, $leadStatuses->where('is_lost', true)->pluck('slug')->toArray()) ? 'true' : 'false' }}">
                                                                <span class="d-inline-block rounded-circle me-2"
                                                                    style="width:10px;height:10px;background:{{ collect($leadStatuses)->firstWhere('slug', $otherKey)?->color ?? '#6c757d' }};"></span>
                                                                {{ $otherLabel }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-placeholder">
                                    <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                    <p class="mb-0 small">No leads in this stage</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @php $firstStatus = false; @endphp
                @endforeach

            </div>{{-- /mobile-pipeline --}}
        </div>
    </div>

    <!-- Lost Reason Modal -->
    <div class="modal fade" id="lostReasonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title dark:text-gray-100">Reason for Lost</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted dark:text-gray-400">Please provide a reason for marking this lead as lost:</p>
                    <textarea id="lostReasonText" class="form-control dark:bg-gray-700 dark:text-gray-100" rows="3"
                        placeholder="Enter reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLostBtn">Mark as Lost</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- SortableJS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrfToken = '{{ csrf_token() }}';
                const lostSlugs = @json($leadStatuses->where('is_lost', true)->pluck('slug')->values());

                /* ══════════════════════════════════════════
                   SHARED — status update via AJAX
                   Used by both desktop drag-drop and mobile Move-to
                ══════════════════════════════════════════ */
                function updateLeadStatus(leadId, status, lostReason, onSuccess, onError) {
                    fetch(`${window.adminBasePath}/crm/${leadId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status,
                                lost_reason: lostReason
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message || 'Status updated successfully!');
                                if (onSuccess) onSuccess(data);
                            } else {
                                showToast('error', data.message || 'Failed to update status');
                                if (onError) onError();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showToast('error', 'An error occurred');
                            if (onError) onError();
                        });
                }

                function showToast(type, message) {
                    const toast = document.getElementById(type + 'Toast');
                    const msgEl = document.getElementById(type + 'Message');
                    if (toast && msgEl) {
                        msgEl.textContent = message;
                        new bootstrap.Toast(toast).show();
                    }
                }

                /* ══════════════════════════════════════════
                   DESKTOP — SortableJS kanban (unchanged)
                ══════════════════════════════════════════ */
                const columns = document.querySelectorAll('.pipeline-container .sortable-column');
                let pendingLostLead = null;
                let pendingLostSourceColumn = null;
                let pendingLostStatus = null;

                if (window.matchMedia('(min-width: 768px)').matches) {
                    columns.forEach(column => {
                        new Sortable(column, {
                            group: 'leads',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            dragClass: 'sortable-drag',
                            filter: '.empty-placeholder',
                            onStart: function(evt) {
                                document.querySelectorAll('.empty-placeholder').forEach(el => el
                                    .style.display = 'none');
                            },
                            onEnd: function(evt) {
                                const leadId = evt.item.dataset.id;
                                const newStatus = evt.to.dataset.status;
                                const oldStatus = evt.from.dataset.status;

                                updateDesktopEmptyPlaceholders();
                                if (newStatus === oldStatus) return;

                                if (lostSlugs.includes(newStatus)) {
                                    pendingLostLead = {
                                        id: leadId,
                                        element: evt.item
                                    };
                                    pendingLostSourceColumn = evt.from;
                                    pendingLostStatus = newStatus;
                                    new bootstrap.Modal(document.getElementById('lostReasonModal'))
                                        .show();
                                    return;
                                }

                                updateLeadStatus(leadId, newStatus, null,
                                    () => {
                                        updateDesktopColumnCounts();
                                        pendingLostLead = null;
                                        pendingLostSourceColumn = null;
                                    },
                                    () => {
                                        if (evt.from && evt.item) {
                                            evt.from.appendChild(evt.item);
                                            updateDesktopEmptyPlaceholders();
                                            updateDesktopColumnCounts();
                                        }
                                    }
                                );
                            }
                        });
                    });
                }

                document.getElementById('confirmLostBtn').addEventListener('click', function() {
                    const reason = document.getElementById('lostReasonText').value.trim();
                    if (!reason) {
                        alert('Please provide a reason');
                        return;
                    }

                    // Works for both desktop drag and mobile Move-to
                    const leadId = pendingLostLead?.id || pendingMobileLostLeadId;
                    const status = pendingLostStatus || 'lost';
                    const sourceColumn = pendingLostSourceColumn;
                    const element = pendingLostLead?.element;
                    const isMobile = !!pendingMobileLostLeadId;

                    updateLeadStatus(leadId, status, reason,
                        () => {
                            updateDesktopColumnCounts();
                            if (isMobile) moveMobileCard(leadId, pendingMobileOldStatus, status);
                            pendingLostLead = null;
                            pendingLostSourceColumn = null;
                            pendingMobileLostLeadId = null;
                            pendingMobileOldStatus = null;
                        },
                        () => {
                            if (!isMobile && sourceColumn && element) {
                                sourceColumn.appendChild(element);
                                updateDesktopEmptyPlaceholders();
                                updateDesktopColumnCounts();
                            }
                        }
                    );
                    bootstrap.Modal.getInstance(document.getElementById('lostReasonModal')).hide();
                    document.getElementById('lostReasonText').value = '';
                });

                document.getElementById('lostReasonModal').addEventListener('hidden.bs.modal', function() {
                    if (pendingLostLead && pendingLostSourceColumn) {
                        const targetColumn = document.querySelector(
                            `.sortable-column[data-status="${pendingLostStatus}"]`);
                        if (targetColumn && targetColumn.contains(pendingLostLead.element)) {
                            pendingLostSourceColumn.appendChild(pendingLostLead.element);
                            updateDesktopEmptyPlaceholders();
                            updateDesktopColumnCounts();
                        }
                    }
                    pendingLostLead = null;
                    pendingLostSourceColumn = null;
                    pendingLostStatus = null;
                    // Don't clear mobile pending here — modal fires hidden before our handler runs
                });

                function updateDesktopEmptyPlaceholders() {
                    columns.forEach(column => {
                        const cards = column.querySelectorAll('.lead-card');
                        let placeholder = column.querySelector('.empty-placeholder');
                        if (cards.length === 0) {
                            if (!placeholder) {
                                placeholder = document.createElement('div');
                                placeholder.className = 'text-center text-muted py-4 empty-placeholder';
                                placeholder.innerHTML =
                                    '<i class="fa fa-inbox mb-2"></i><p class="mb-0 small">No leads</p>';
                                column.appendChild(placeholder);
                            }
                            placeholder.style.display = 'block';
                        } else if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    });
                }

                function updateDesktopColumnCounts() {
                    columns.forEach(column => {
                        const status = column.dataset.status;
                        const cards = column.querySelectorAll('.lead-card');
                        const countEl = document.getElementById(`count-${status}`);
                        const valueEl = document.getElementById(`value-${status}`);
                        if (countEl) countEl.textContent = cards.length;
                        if (valueEl) {
                            let total = 0;
                            cards.forEach(card => {
                                total += parseFloat(card.dataset.value) || 0;
                            });
                            valueEl.textContent = '{{ $crmCurrency }}' + total.toLocaleString();
                        }
                    });
                }

                /* ══════════════════════════════════════════
                   MOBILE — tab switching
                ══════════════════════════════════════════ */
                const tabBtns = document.querySelectorAll('.mobile-tab-btn');
                const tabPanels = document.querySelectorAll('.mobile-column-panel');

                tabBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        tabBtns.forEach(b => b.classList.remove('active'));
                        tabPanels.forEach(p => p.classList.remove('active'));
                        btn.classList.add('active');
                        const panelId = btn.dataset.panel;
                        const panel = document.getElementById(panelId);
                        if (panel) {
                            panel.classList.add('active');
                            // Scroll the tab into view inside the strip
                            btn.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'center'
                            });
                        }
                    });
                });

                /* ══════════════════════════════════════════
                   MOBILE — Move-to dropdown
                ══════════════════════════════════════════ */
                let pendingMobileLostLeadId = null;
                let pendingMobileOldStatus = null;

                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.mobile-status-change');
                    if (!btn) return;

                    const leadId = btn.dataset.leadId;
                    const newStatus = btn.dataset.newStatus;
                    const isLost = btn.dataset.isLost === 'true';
                    const cardEl = btn.closest('.lead-card');
                    const oldStatus = cardEl ? cardEl.dataset.status : null;

                    if (isLost) {
                        // Need a reason — re-use the existing modal
                        pendingMobileLostLeadId = leadId;
                        pendingMobileOldStatus = oldStatus;
                        pendingLostStatus = newStatus;
                        pendingLostLead = null; // not a desktop drag
                        new bootstrap.Modal(document.getElementById('lostReasonModal')).show();
                        return;
                    }

                    // Optimistic UI: show spinner on button
                    const dropdown = btn.closest('.dropdown');
                    const toggleBtn = dropdown?.querySelector('.mobile-move-btn');
                    if (toggleBtn) {
                        toggleBtn.disabled = true;
                        toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    }

                    updateLeadStatus(leadId, newStatus, null,
                        () => {
                            moveMobileCard(leadId, oldStatus, newStatus);
                        },
                        () => {
                            // Restore button
                            if (toggleBtn) {
                                toggleBtn.disabled = false;
                                toggleBtn.innerHTML = '<i class="fa fa-exchange-alt me-1"></i>Move to';
                            }
                        }
                    );
                });

                /**
                 * Physically move a card from one mobile panel to another and update counts.
                 */
                function moveMobileCard(leadId, fromStatus, toStatus) {
                    const cardEl = document.querySelector(`.mobile-pipeline .lead-card[data-id="${leadId}"]`);
                    const toBody = document.getElementById(`mobile-body-${toStatus}`);
                    if (!cardEl || !toBody) {
                        // Fallback: page reload to re-sync state
                        window.location.reload();
                        return;
                    }

                    const fromBody = cardEl.closest('[id^="mobile-body-"]');

                    // Update card's current status attribute and the Move-to dropdown
                    cardEl.dataset.status = toStatus;
                    // Rebuild the Move-to dropdown items to exclude new status and include old one
                    const moveBtnContainer = cardEl.querySelector('.dropdown');
                    if (moveBtnContainer) {
                        const ul = moveBtnContainer.querySelector('.dropdown-menu');
                        if (ul) {
                            // Update each item: hide items matching toStatus, show item for fromStatus
                            ul.querySelectorAll('.mobile-status-change').forEach(item => {
                                if (item.dataset.newStatus === toStatus) {
                                    item.closest('li').style.display = 'none';
                                }
                                if (item.dataset.newStatus === fromStatus) {
                                    item.closest('li').style.display = '';
                                }
                            });
                        }
                        // Restore move button label
                        const toggleBtn = moveBtnContainer.querySelector('.mobile-move-btn');
                        if (toggleBtn) {
                            toggleBtn.disabled = false;
                            toggleBtn.innerHTML = '<i class="fa fa-exchange-alt me-1"></i>Move to';
                        }
                    }

                    // Move DOM node
                    toBody.insertBefore(cardEl, toBody.firstChild);

                    // Update empty placeholders in both panels
                    [fromBody, toBody].forEach(body => {
                        if (!body) return;
                        const hasCards = body.querySelectorAll('.lead-card').length > 0;
                        let placeholder = body.querySelector('.empty-placeholder');
                        if (!hasCards && !placeholder) {
                            placeholder = document.createElement('div');
                            placeholder.className = 'empty-placeholder';
                            placeholder.innerHTML =
                                '<i class="fa fa-inbox fa-2x mb-2 d-block"></i><p class="mb-0 small">No leads in this stage</p>';
                            body.appendChild(placeholder);
                        } else if (hasCards && placeholder) {
                            placeholder.style.display = 'none';
                        }
                    });

                    // Update tab badge counts
                    updateMobileTabCounts();
                }

                function updateMobileTabCounts() {
                    document.querySelectorAll('.mobile-column-panel').forEach(panel => {
                        const status = panel.dataset.status;
                        const count = panel.querySelectorAll('.lead-card').length;
                        const badge = document.getElementById(`mobile-count-${status}`);
                        if (badge) badge.textContent = count;
                        // Also update desktop count badges
                        const deskBadge = document.getElementById(`count-${status}`);
                        if (deskBadge) deskBadge.textContent = count;
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
