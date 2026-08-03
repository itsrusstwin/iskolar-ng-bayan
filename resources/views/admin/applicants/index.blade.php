@extends('layouts.app')
@section('title', 'All Applicants')
@section('subtitle', 'View student info, track stage status, and manage schedules')

@section('header_actions')
    <a href="{{ route('admin.students.create') }}" class="btn btn-navy btn-sm d-inline-flex align-items-center gap-2">
        <i class="bi bi-person-plus"></i> Create Account
    </a>
@endsection

@section('content')

@php
    $stageBadges = function ($applicant) {
        $status = $applicant->status;
        $verification = $applicant->verification;
        $mswdo = $applicant->mswdoAssessment;
        $examResult = $applicant->examResults->first();
        $orientation = $applicant->orientation;
        $waste = $applicant->wasteCompliance;
        $payouts = $applicant->payouts;

        $stages = [
            'policy' => [
                'label' => 'Policy Verification',
                'icon' => 'bi-shield-check',
                'text' => !$verification ? 'Pending' : ($verification->is_disqualified ? 'Not passed' : 'Passed'),
                'class' => !$verification ? 'badge bg-secondary-subtle text-secondary-emphasis'
                    : ($verification->is_disqualified ? 'badge bg-danger-subtle text-danger-emphasis' : 'badge-soft-gold'),
            ],
            'mswdo' => [
                'label' => 'MSWDO Assessment',
                'icon' => 'bi-clipboard2-heart',
                'text' => !$mswdo ? 'Pending' : ($mswdo->is_qualified ? 'Qualified' : 'Not qualified'),
                'class' => !$mswdo ? 'badge bg-secondary-subtle text-secondary-emphasis'
                    : ($mswdo->is_qualified ? 'badge-soft-gold' : 'badge bg-danger-subtle text-danger-emphasis'),
            ],
            'exam' => [
                'label' => 'Qualifying Exam',
                'icon' => 'bi-pencil-square',
                'text' => $examResult ? ($examResult->passed ? 'Passed' : 'Failed')
                    : ($applicant->exam_scheduled_at ? 'Scheduled' : 'Not scheduled'),
                'class' => $examResult ? ($examResult->passed ? 'badge-soft-gold' : 'badge bg-danger-subtle text-danger-emphasis')
                    : ($applicant->exam_scheduled_at ? 'badge-soft-navy' : 'badge bg-secondary-subtle text-secondary-emphasis'),
            ],
            'orientation' => [
                'label' => 'Orientation',
                'icon' => 'bi-mortarboard',
                'text' => $orientation && $orientation->attended ? 'Attended'
                    : ($applicant->orientation_scheduled_at ? 'Scheduled' : 'Pending'),
                'class' => $orientation && $orientation->attended ? 'badge-soft-gold'
                    : ($applicant->orientation_scheduled_at ? 'badge-soft-navy' : 'badge bg-secondary-subtle text-secondary-emphasis'),
            ],
            'waste' => [
                'label' => 'Waste Compliance',
                'icon' => 'bi-recycle',
                'text' => $waste->count() ? 'On record' : 'No records',
                'class' => $waste->count() ? 'badge-soft-gold' : 'badge bg-secondary-subtle text-secondary-emphasis',
            ],
            'payout' => [
                'label' => 'Payout',
                'icon' => 'bi-cash-stack',
                'text' => $payouts->count() ? 'Released' : 'No payout',
                'class' => $payouts->count() ? 'badge-soft-gold' : 'badge bg-secondary-subtle text-secondary-emphasis',
            ],
        ];

        if ($status === 'submitted') $stages['policy']['text'] = 'Pending';

        return $stages;
    };
@endphp

<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
        <div>
            <h2 class="h6 fw-bold mb-0">Applicants ({{ number_format($applicants->count()) }})</h2>
            <p class="small text-muted-soft mb-0">Select applicants below to schedule the exam or orientation.</p>
        </div>

        <form method="GET" action="{{ route('admin.applicants.index') }}" class="d-flex gap-2 flex-wrap">
            <div class="admin-search">
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, school, email..." class="form-control border-start-0" style="min-width: 220px;">
                </div>
            </div>
            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status', 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Bulk schedule bar -->
    <div id="bulkBar" class="d-none align-items-center gap-2 px-3 py-2 border-bottom" style="background: var(--surface-100);">
        <span class="small fw-semibold me-2" id="bulkCount">0 selected</span>
        <button type="button" class="btn btn-sm btn-navy" onclick="openScheduleModal('exam')">
            <i class="bi bi-calendar-check"></i> Set Exam Schedule
        </button>
        <button type="button" class="btn btn-sm btn-outline-navy" onclick="openScheduleModal('orientation')">
            <i class="bi bi-mortarboard"></i> Set Orientation Schedule
        </button>
        <button type="button" class="btn btn-sm btn-link text-muted-soft ms-auto" onclick="clearSelection()">Clear</button>
    </div>

    <div class="admin-panel__body admin-panel__body--flush">
        <form id="bulkForm" method="POST" action="{{ route('admin.applicants.schedule-bulk') }}">
            @csrf
            <input type="hidden" name="type" id="bulkType" value="exam">
            <div id="selectedIdsContainer"></div>
        </form>

        @if ($applicants->isNotEmpty())
        <div class="admin-table-scroll admin-table-scroll--y">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll" onclick="toggleAll(this)">
                        </th>
                        <th>Applicant</th>
                        <th>Status</th>
                        <th>Exam Schedule</th>
                        <th>Orientation</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                    <tr class="applicant-row">
                        <td class="ps-3">
                            <input type="checkbox" class="form-check-input bulk-select" value="{{ $applicant->id }}" onclick="updateSelection()">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                <span class="admin-avatar">
                                    {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                </span>
                                <div style="min-width:0;">
                                    <div class="applicant-name fw-semibold admin-table__name">{{ $applicant->first_name }} {{ $applicant->last_name }}</div>
                                    <span class="text-muted-soft admin-table__meta" style="font-size: .72rem;">
                                        {{ $applicant->school_name ?? '—' }}
                                        <span class="opacity-50">·</span>
                                        {{ $applicant->user->email ?? 'no account' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="{{ $dashboard->statusBadgeClass($applicant->status) }}">
                                {{ $dashboard->statusDisplayLabel($applicant->status) }}
                            </span>
                        </td>
                        <td class="small">
                            @if ($applicant->exam_scheduled_at)
                                <span class="fw-semibold" style="color: var(--ink-700);">{{ $applicant->exam_scheduled_at->format('M d, Y g:ia') }}</span>
                            @else
                                <span class="text-muted-soft">—</span>
                            @endif
                        </td>
                        <td class="small">
                            @if ($applicant->orientation_scheduled_at)
                                <span class="fw-semibold" style="color: var(--ink-700);">{{ $applicant->orientation_scheduled_at->format('M d, Y g:ia') }}</span>
                            @else
                                <span class="text-muted-soft">—</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-navy py-0 px-2" style="font-size: .75rem;" data-bs-toggle="collapse" data-bs-target="#detail-{{ $applicant->id }}">
                                    <i class="bi bi-eye"></i> Details
                                </button>
                                <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-sm btn-outline-navy py-0 px-2" style="font-size: .75rem;">
                                    Manage
                                </a>
                                <form method="POST" action="{{ route('admin.applicants.destroy', $applicant) }}" onsubmit="return confirm('Delete this student account? This will permanently remove their application and all related records.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: .75rem;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr class="applicant-detail-row">
                        <td colspan="6" class="p-0 border-0">
                            <div class="collapse" id="detail-{{ $applicant->id }}">
                                <div class="p-4" style="background: var(--surface-50);">
                                    <div class="row g-3">
                                        <div class="col-lg-5">
                                            <p class="small fw-bold text-uppercase text-muted-soft mb-2" style="letter-spacing: .04em;">Student Info</p>
                                            <div class="card-flat p-3">
                                                <div class="d-flex gap-3 mb-2">
                                                    <span class="admin-avatar" style="width:44px;height:44px;font-size:.85rem;">
                                                        {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <p class="fw-bold mb-0">{{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name . ' ' : '' }}{{ $applicant->last_name }}</p>
                                                        <p class="small text-muted-soft mb-0">{{ $applicant->user->email ?? 'No account email' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2 small">
                                                    <div class="col-6"><span class="text-muted-soft">Contact:</span> {{ $applicant->contact_number ?? 'N/A' }}</div>
                                                    <div class="col-6"><span class="text-muted-soft">Birthday:</span> {{ $applicant->date_of_birth?->format('M d, Y') ?? 'N/A' }}</div>
                                                    <div class="col-6"><span class="text-muted-soft">Sex:</span> {{ $applicant->sex ?? 'N/A' }}</div>
                                                    <div class="col-6"><span class="text-muted-soft">School ID:</span> {{ $applicant->school_id ?? 'N/A' }}</div>
                                                    <div class="col-12"><span class="text-muted-soft">School:</span> {{ $applicant->school_name ?? 'N/A' }}@if ($applicant->course) — {{ $applicant->course }}@if ($applicant->year_level), {{ $applicant->year_level }}@endif @endif</div>
                                                    <div class="col-12">
                                                        <span class="text-muted-soft">Address:</span>
                                                        {{ implode(', ', array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay])) ?: 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <p class="small fw-bold text-uppercase text-muted-soft mb-2" style="letter-spacing: .04em;">Stage Status</p>
                                            <div class="row g-2">
                                                @foreach ($stageBadges($applicant) as $stage)
                                                    <div class="col-md-6">
                                                        <div class="card-flat p-2 d-flex align-items-center gap-2" style="min-height: 52px;">
                                                            <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;">
                                                                <i class="bi {{ $stage['icon'] }}"></i>
                                                            </span>
                                                            <div class="flex-grow-1" style="min-width:0;">
                                                                <p class="mb-0 fw-semibold small text-truncate">{{ $stage['label'] }}</p>
                                                            </div>
                                                            <span class="{{ $stage['class'] }} flex-shrink-0" style="font-size: .7rem;">{{ $stage['text'] }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-muted-soft py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
            No applicants match your filters.
        </div>
        @endif
    </div>
</div>

<!-- Schedule modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.applicants.schedule-bulk') }}">
            @csrf
            <input type="hidden" name="type" id="modalType">
            <input type="hidden" name="scheduled_at" id="modalScheduledAt">

            <div class="modal-header py-3">
                <h5 class="modal-title small fw-bold mb-0" id="scheduleModalTitle">Set Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted-soft mb-3" id="scheduleModalDesc">Select a date and time for the selected applicants.</p>
                <label class="form-label small fw-semibold">Date &amp; time</label>
                <input type="datetime-local" class="form-control" id="scheduleDateInput" required>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-outline-navy" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-navy px-3">Save Schedule</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const selected = new Set();

    function updateSelection() {
        selected.clear();
        document.querySelectorAll('.bulk-select:checked').forEach(cb => selected.add(Number(cb.value)));
        syncBulkBar();
    }

    function toggleAll(source) {
        document.querySelectorAll('.bulk-select').forEach(cb => { cb.checked = source.checked; });
        updateSelection();
    }

    function syncBulkBar() {
        const bar = document.getElementById('bulkBar');
        const count = document.getElementById('bulkCount');
        count.textContent = selected.size + ' selected';
        bar.classList.toggle('d-none', selected.size === 0);
        bar.classList.toggle('d-flex', selected.size > 0);
    }

    function clearSelection() {
        document.querySelectorAll('.bulk-select').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateSelection();
    }

    function openScheduleModal(type) {
        if (selected.size === 0) return;
        document.getElementById('modalType').value = type;
        document.getElementById('scheduleModalTitle').textContent = type === 'exam' ? 'Set Exam Schedule' : 'Set Orientation Schedule';
        document.getElementById('scheduleModalDesc').textContent =
            'Schedule the ' + (type === 'exam' ? 'qualifying exam' : 'orientation') + ' for ' + selected.size + ' selected applicant(s).';
        document.getElementById('scheduleDateInput').value = '';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('scheduleModal')).show();
    }

    document.getElementById('scheduleDateInput').addEventListener('change', function () {
        document.getElementById('modalScheduledAt').value = this.value;
    });

    document.getElementById('scheduleModal').addEventListener('hidden.bs.modal', function () {
        clearSelection();
    });

    // Live search within the table
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !new URLSearchParams(window.location.search).get('search')) {
        searchInput.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.applicant-row').forEach(function (row) {
                const name = (row.querySelector('.applicant-name')?.textContent || '').toLowerCase();
                row.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }
</script>
@endpush
