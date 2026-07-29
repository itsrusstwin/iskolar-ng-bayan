@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('subtitle', 'Overview of scholarship applications and program activity')

@section('header_actions')
    <span class="badge-soft-navy d-none d-md-inline-flex align-items-center gap-1">
        <i class="bi bi-calendar3"></i>
        {{ now()->format('F j, Y') }}
    </span>
@endsection

@section('content')

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Total Applications</p>
                    <p class="h3 fw-bold mb-0">{{ number_format($stats['total']) }}</p>
                    @if ($stats['this_month'] > 0)
                        <p class="small text-muted-soft mb-0 mt-1">
                            <i class="bi bi-arrow-up-short text-success"></i>
                            {{ $stats['this_month'] }} this month
                        </p>
                    @endif
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--navy"><i class="bi bi-people"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">In Progress</p>
                    <p class="h3 fw-bold mb-0" style="color: var(--gold-600);">{{ number_format($stats['in_progress']) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Under review & assessment</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--gold"><i class="bi bi-hourglass-split"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Qualified</p>
                    <p class="h3 fw-bold mb-0 text-success">{{ number_format($stats['qualified']) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Passed exam & compliance</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--green"><i class="bi bi-patch-check"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Scholarship Released</p>
                    <p class="h3 fw-bold mb-0" style="color: var(--ink-700);">{{ number_format($stats['released']) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Completed payouts</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--blue"><i class="bi bi-cash-stack"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Disqualified</p>
                    <p class="h3 fw-bold mb-0 text-danger">{{ number_format($stats['disqualified']) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Did not meet criteria</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--red"><i class="bi bi-x-circle"></i></span>
            </div>
        </div>
    </div>
</div>

@if ($stats['total_payout'] > 0)
<div class="alert border-0 mb-4 py-2 px-3 d-flex align-items-center gap-2"
     style="background: var(--surface-100); border-radius: var(--radius-sm);">
    <i class="bi bi-wallet2" style="color: var(--ink-700);"></i>
    <span class="small">
        <strong>Total disbursed:</strong>
        ₱{{ number_format($stats['total_payout'], 2) }}
        across all scholarship releases
    </span>
</div>
@endif

<!-- Charts + Recent Applications -->
<div class="row g-4 mb-4">
    <!-- Recent Applications -->
    <div class="col-lg-7 d-flex">
        <div class="admin-panel d-flex flex-column h-100 w-100">
            <div class="admin-panel__header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="h6 fw-bold mb-0">Recent Applications</h2>
                    <p class="small text-muted-soft mb-0">Latest student submissions</p>
                </div>
                <div class="admin-search">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="applicant-search" placeholder="Search applicants..." class="form-control border-start-0">
                    </div>
                </div>
            </div>
            <div class="admin-panel__body admin-panel__body--flush flex-grow-1 d-flex flex-column">
                @if ($recentApplicants->isNotEmpty())
                <div class="admin-table-scroll admin-table-scroll--y flex-grow-1">
                    <table class="table admin-table admin-table--compact mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3 col-applicant">Applicant</th>
                                <th class="col-status">Status</th>
                                <th class="col-date">Date</th>
                                <th class="text-end pe-3 col-action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentApplicants as $applicant)
                            <tr class="applicant-row">
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                        <span class="admin-avatar">
                                            {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                        </span>
                                        <div style="min-width:0;">
                                            <div class="applicant-name fw-semibold admin-table__name">{{ $applicant->first_name }} {{ $applicant->last_name }}</div>
                                            <span class="text-muted-soft admin-table__meta" style="font-size: .72rem;">
                                                {{ $applicant->school_name ?? '—' }}
                                                <span class="opacity-50">·</span>
                                                {{ \App\Services\AdminDashboardService::PROGRAM_LABELS[$applicant->program_type] ?? ucfirst($applicant->program_type) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $dashboard->statusBadgeClass($applicant->status) }}">
                                        {{ $dashboard->statusDisplayLabel($applicant->status) }}
                                    </span>
                                </td>
                                <td class="text-muted-soft">{{ $applicant->created_at?->format('M j, Y') ?? '—' }}</td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-sm btn-outline-navy py-0 px-2" style="font-size: .75rem;">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted-soft py-5 flex-grow-1 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                    No applications yet.
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-5 d-flex flex-column">
        <div class="admin-panel mb-4">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0">Application Progress</h2>
                <p class="small text-muted-soft mb-0">Distribution by current stage</p>
            </div>
            <div class="admin-panel__body">
                <div class="admin-chart-wrap">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>

        <div class="admin-panel flex-grow-1 d-flex flex-column">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0">Applications by Program</h2>
                <p class="small text-muted-soft mb-0">New vs renewal applicants</p>
            </div>
            <div class="admin-panel__body flex-grow-1 d-flex flex-column">
                <div class="admin-chart-wrap admin-chart-wrap--sm flex-grow-1">
                    <canvas id="programChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity + Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="admin-panel h-100">
            <div class="admin-panel__header d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h6 fw-bold mb-0">Recent Activity</h2>
                    <p class="small text-muted-soft mb-0">Latest admin actions</p>
                </div>
                <a href="{{ route('admin.audit-log.index') }}" class="small fw-semibold" style="color: var(--ink-700);">View all</a>
            </div>
            <div class="admin-panel__body">
                @forelse ($recentActivity as $log)
                <div class="admin-activity-item">
                    <div class="d-flex gap-2">
                        <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:32px;height:32px;font-size:.85rem;">
                            <i class="bi bi-journal-check"></i>
                        </span>
                        <div class="flex-grow-1 min-w-0">
                            <p class="small mb-0">{{ Str::limit($log->description, 80) }}</p>
                            <p class="text-muted-soft mb-0" style="font-size: .7rem;">
                                {{ $log->user?->name ?? 'System' }}
                                · {{ $log->created_at?->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted-soft small mb-0 text-center py-3">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-panel h-100">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0">Quick Actions</h2>
                <p class="small text-muted-soft mb-0">Common administrative tasks</p>
            </div>
            <div class="admin-panel__body">
                <div class="admin-quick-actions">
                    <a href="{{ route('admin.students.create') }}" class="admin-quick-action">
                        <span class="admin-quick-action__icon"><i class="bi bi-person-plus"></i></span>
                        <span class="admin-quick-action__label">
                            <span class="d-block fw-semibold small">Create Account</span>
                            <span class="text-muted-soft" style="font-size: .75rem;">Add student user</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.announcements.create') }}" class="admin-quick-action">
                        <span class="admin-quick-action__icon"><i class="bi bi-megaphone"></i></span>
                        <span class="admin-quick-action__label">
                            <span class="d-block fw-semibold small">Announcement</span>
                            <span class="text-muted-soft" style="font-size: .75rem;">Post update</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="admin-quick-action">
                        <span class="admin-quick-action__icon"><i class="bi bi-list-ul"></i></span>
                        <span class="admin-quick-action__label">
                            <span class="d-block fw-semibold small">Manage Posts</span>
                            <span class="text-muted-soft" style="font-size: .75rem;">Edit announcements</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.audit-log.index') }}" class="admin-quick-action">
                        <span class="admin-quick-action__icon"><i class="bi bi-shield-check"></i></span>
                        <span class="admin-quick-action__label">
                            <span class="d-block fw-semibold small">Audit Log</span>
                            <span class="text-muted-soft" style="font-size: .75rem;">Review actions</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Applicants by Status -->
@if ($applicants->isNotEmpty())
<div class="admin-panel">
    <div class="admin-panel__header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h2 class="h6 fw-bold mb-0">All Applicants by Status</h2>
            <p class="small text-muted-soft mb-0">Browse and manage applications grouped by workflow stage</p>
        </div>
        <span class="badge-soft-navy">{{ $stats['total'] }} total</span>
    </div>
    <div class="admin-panel__body">
        <div class="accordion admin-status-accordion" id="statusAccordion">
            @foreach ($applicants as $status => $group)
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#status-{{ Str::slug($status) }}">
                        <span>{{ $dashboard->statusDisplayLabel($status) }}</span>
                        <span class="badge-soft-navy ms-2">{{ $group->count() }}</span>
                    </button>
                </h3>
                <div id="status-{{ Str::slug($status) }}" class="accordion-collapse collapse" data-bs-parent="#statusAccordion">
                    <div class="admin-table-scroll">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>School</th>
                                    <th>Program</th>
                                    <th>Contact</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $applicant)
                                <tr class="applicant-row">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="admin-avatar" style="width:32px;height:32px;font-size:.7rem;">
                                                {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                            </span>
                                            <span class="applicant-name fw-semibold small">{{ $applicant->first_name }} {{ $applicant->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted-soft small">
                                        {{ $applicant->school_name ?? '—' }}
                                        @if ($applicant->course)
                                            <div style="font-size: .7rem;">{{ $applicant->course }} · {{ $applicant->year_level }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-soft-navy" style="font-size: .75rem;">
                                            {{ \App\Services\AdminDashboardService::PROGRAM_LABELS[$applicant->program_type] ?? ucfirst($applicant->program_type) }}
                                        </span>
                                    </td>
                                    <td class="text-muted-soft small">{{ $applicant->contact_number ?? '—' }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('applicants.show', $applicant) }}" class="fw-semibold small" style="color: var(--ink-700);">
                                            View <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartFont = "'Inter', system-ui, sans-serif";
    const inkColor = '#14213D';
    const mutedColor = '#6B7A93';

    Chart.defaults.font.family = chartFont;
    Chart.defaults.color = mutedColor;

    // Progress pie chart
    const progressCtx = document.getElementById('progressChart');
    if (progressCtx) {
        new Chart(progressCtx, {
            type: 'doughnut',
            data: {
                labels: @json($progressChart['labels']),
                datasets: [{
                    data: @json($progressChart['values']),
                    backgroundColor: @json($progressChart['chartColors']),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12, weight: '500' },
                            color: inkColor,
                        },
                    },
                    tooltip: {
                        backgroundColor: '#0A2647',
                        titleFont: { weight: '600' },
                        padding: 12,
                        callbacks: {
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((ctx.raw / total) * 100) : 0;
                                return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                            },
                        },
                    },
                },
            },
        });
    }

    // Program bar chart
    const programCtx = document.getElementById('programChart');
    if (programCtx) {
        new Chart(programCtx, {
            type: 'bar',
            data: {
                labels: @json($programChart['labels']),
                datasets: [{
                    label: 'Applications',
                    data: @json($programChart['values']),
                    backgroundColor: ['#2c65ac', '#E8A33D', '#1E6B3C', '#6B7A93'],
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 48,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0A2647',
                        padding: 12,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: inkColor },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: '#E9EFF7' },
                    },
                },
            },
        });
    }

    // Search filter
    const searchInput = document.getElementById('applicant-search');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.applicant-row').forEach(function (row) {
                const nameEl = row.querySelector('.applicant-name');
                if (!nameEl) return;
                const name = nameEl.textContent.toLowerCase();
                row.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush