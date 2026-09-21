@extends('layouts.app')
@section('title', 'Master List')
@section('subtitle', 'Complete record of every applicant with their journey through the scholarship pipeline')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:36px;height:36px;font-size:1rem;flex-shrink:0;">
                <i class="bi bi-box2-fill"></i>
            </span>
            Master List Archive
        </h2>
        <p class="small text-muted-soft mb-0">
            The permanent record of every applicant — including archived student accounts — with their full pipeline journey.
        </p>
    </div>
    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
        <a href="{{ route('admin.master-list.index') }}" class="btn btn-sm btn-ghost text-muted-soft d-inline-flex align-items-center gap-1 flex-shrink-0">
            <i class="bi bi-x-lg"></i> Clear filters
        </a>
    @endif
</div>

<!-- Filter bar -->
<div class="admin-panel mb-4">
    <div class="admin-panel__body">
        <form method="GET" action="{{ route('admin.master-list.index') }}" id="masterListForm" class="row g-3 align-items-end">
            <div class="col-12 col-md-6 col-xl-2">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-calendar-week me-1"></i>Account Created Date</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="created_date" value="{{ $filters['created_date'] ?? '' }}" class="form-control border-start-0">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-tag me-1"></i>Applicant Type &amp; Archive</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-funnel"></i></span>
                    <select name="program_type" class="form-select form-select-sm border-start-0">
                        <option value="" {{ !$filters['program_type'] && $filters['archive_status'] === 'all' ? 'selected' : '' }}>All</option>
                        <optgroup label="Program Type">
                            <option value="new" {{ $filters['program_type'] === 'new' ? 'selected' : '' }}>New Applicant</option>
                            <option value="renewal" {{ $filters['program_type'] === 'renewal' ? 'selected' : '' }}>Renewal</option>
                        </optgroup>
                        <optgroup label="Archive Status">
                            <option value="active" {{ $filters['archive_status'] === 'active' ? 'selected' : '' }}>Active accounts</option>
                            <option value="archived" {{ $filters['archive_status'] === 'archived' ? 'selected' : '' }}>Archived accounts</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-2">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-flag me-1"></i>Status</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-check-circle"></i></span>
                    <select name="status" class="form-select form-select-sm border-start-0" onchange="this.form.submit()">
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-search me-1"></i>Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, school, email, contact..." class="form-control border-start-0">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-navy flex-fill d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-filter"></i> Apply
                </button>
                @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
                    <a href="{{ route('admin.master-list.index') }}" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #14213D, #1a2d50);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-box2-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['total']) }}</p>
                <p class="admin-kpi__label text-white-50">Total Records</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #2c65ac, #1f4d85);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-people-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['active']) }}</p>
                <p class="admin-kpi__label text-white-50">Active Accounts</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #E8A33D, #d18a1f);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.2); color:#fff;"><i class="bi bi-hourglass-split"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['in_progress']) }}</p>
                <p class="admin-kpi__label text-white-50">In Progress</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #1E6B3C, #15512d);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-cash-coin"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['released']) }}</p>
                <p class="admin-kpi__label text-white-50">Scholarship Released</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #55606e, #3d4653);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-archive-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['archived']) }}</p>
                <p class="admin-kpi__label text-white-50">Archived Accounts</p>
            </div>
        </div>
    </div>
</div>

<!-- Master table -->
<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2 class="h6 fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-archive"></i> Archived Records
                <span class="badge-soft-navy ms-1">{{ number_format($applicants->count()) }}</span>
            </h2>
            <p class="small text-muted-soft mb-0 mt-1">Click any row to open the full applicant record in the side panel.</p>
        </div>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($applicants->isEmpty())
            <div class="empty-state py-5">
                <span class="empty-state__icon"><i class="bi bi-clipboard-x"></i></span>
                <h6>No applicants found</h6>
                <p>
                    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
                        No records match the current filters. Try a different date or clear the filters.
                    @else
                        The archive is empty — no applicant records yet.
                    @endif
                </p>
            </div>
        @else
        <div class="admin-table-scroll admin-table-scroll--y">
            <table class="table admin-table admin-table--compact admin-table--master mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 col-ml-applicant">Applicant</th>
                        <th class="col-ml-type">Type</th>
                        <th class="col-ml-school">School &amp; Program</th>
                        <th class="col-ml-created">Account Created</th>
                        <th class="col-ml-payout">Payouts</th>
                        <th class="col-ml-status">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                    @php
                        $type = $applicant->program_type === 'renewal' ? 'renewal' : 'new';
                        $payoutTotal = $applicant->payouts->sum('amount');
                        $accountCreated = $applicant->user?->created_at ?? $applicant->created_at;
                        $archived = (bool) $applicant->deleted_at;
                    @endphp
                    <tr class="applicant-row {{ $archived ? 'ml-archived' : '' }}"
                        role="button"
                        tabindex="0"
                        data-applicant-id="{{ $applicant->id }}"
                        title="Click to view full record">
                        <td class="ps-3 col-ml-applicant">
                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                <span class="avatar-wrap flex-shrink-0">
                                    <span class="admin-avatar">
                                        {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                    </span>
                                    @if ($applicant->user?->isOnline())
                                        <span class="online-dot" title="Online now"></span>
                                    @endif
                                </span>
                                <div style="min-width:0;">
                                    <div class="applicant-name fw-semibold admin-table__name">{{ $applicant->first_name }} {{ $applicant->last_name }}</div>
                                    <span class="text-muted-soft admin-table__meta" style="font-size:.72rem;">
                                        {{ $applicant->user?->email ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="col-ml-type">
                            <span class="badge {{ $type === 'renewal' ? 'badge-soft-gold' : 'badge-soft-navy' }}">
                                {{ $type === 'renewal' ? 'Renewal' : 'New' }}
                            </span>
                        </td>
                        <td class="col-ml-school">
                            <div class="admin-table__meta text-truncate" style="max-width: 100%;">{{ $applicant->school_name ?? '—' }}</div>
                            <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">
                                {{ $applicant->course ?? 'N/A' }}@if ($applicant->year_level)· {{ $applicant->year_level }}@endif
                            </div>
                        </td>
                        <td class="col-ml-created">
                            <div class="small fw-semibold">{{ $accountCreated?->format('M d, Y') ?? '—' }}</div>
                            <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">{{ $accountCreated?->format('h:i A') ?? '' }}</div>
                        </td>
                        <td class="col-ml-payout">
                            @if ($applicant->payouts->count())
                                <div class="fw-semibold small text-success-emphasis">₱{{ number_format((float) $payoutTotal, 2) }}</div>
                                <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">{{ $applicant->payouts->count() }} release{{ $applicant->payouts->count() > 1 ? 's' : '' }}</div>
                            @else
                                <span class="text-muted-soft small">—</span>
                            @endif
                        </td>
                        <td class="col-ml-status">
                            @if ($archived)
                                <span class="admin-badge-archived">Archived</span>
                            @else
                                <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">{{ $dashboard->statusDisplayLabel($applicant->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SLIDE-OUT DRAWER — contains the detail of ONE applicant.
     Content is injected via JS when the user clicks a row.
     ══════════════════════════════════════════════════════════ --}}
<div class="ml-drawer-backdrop" id="mlDrawerBackdrop"></div>
<div class="ml-drawer" id="mlDrawer" aria-hidden="true">
    <div id="mlDrawerContent">
        {{-- Populated by JS from hidden data blocks --}}
    </div>
</div>

{{-- Hidden data blocks — one per applicant, rendered server-side --}}
@foreach ($applicants as $applicant)
@php
    $type = $applicant->program_type === 'renewal' ? 'renewal' : 'new';
    $verification = $applicant->verification;
    $mswdo = $applicant->mswdoAssessment;
    $examResult = $applicant->examResults->first();
    $orientation = $applicant->orientation;
    $reqs = $applicant->requirements;
    $submittedReqs = $reqs->filter(fn ($r) => $r->is_submitted)->count();
    $waste = $applicant->wasteCompliance;
    $wasteCompliant = $waste->contains('is_compliant', true);
    $payoutTotal = $applicant->payouts->sum('amount');
    $accountCreated = $applicant->user?->created_at ?? $applicant->created_at;
    $disqualification = $applicant->disqualifications->first();
    $archived = (bool) $applicant->deleted_at;
    $deletedAt = $applicant->deleted_at;

    $steps = [
        ['label' => 'Verification', 'icon' => 'bi-shield-check',       'done' => (bool) $verification && !$verification->is_disqualified],
        ['label' => 'MSWDO',        'icon' => 'bi-clipboard2-heart',   'done' => (bool) $mswdo && $mswdo->is_qualified],
        ['label' => 'Exam',         'icon' => 'bi-pencil-square',      'done' => (bool) $examResult && $examResult->passed],
        ['label' => 'Orientation',  'icon' => 'bi-mortarboard',        'done' => (bool) $orientation && $orientation->attended],
        ['label' => 'Requirements', 'icon' => 'bi-file-earmark-check', 'done' => $reqs->count() > 0 && $submittedReqs === $reqs->count()],
        ['label' => 'Payout',       'icon' => 'bi-cash-coin',          'done' => $applicant->payouts->count() > 0],
    ];
    $completedSteps = collect($steps)->where('done', true)->count();

    $age = $applicant->date_of_birth ? $applicant->date_of_birth->age : null;
    $reqPercent = $reqs->count() ? round(($submittedReqs / $reqs->count()) * 100) : 0;
@endphp
<template id="ml-data-{{ $applicant->id }}">
    {{-- ── Drawer header ── --}}
    <div class="ml-drawer__header">
        <button type="button" class="ml-drawer__close" id="mlDrawerClose" title="Close panel">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="ml-drawer__identity">
            <div class="ml-drawer__avatar">
                {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <h6 class="ml-drawer__name">
                    {{ $applicant->first_name }} {{ $applicant->middle_name ? mb_substr($applicant->middle_name, 0, 1) . '.' : '' }} {{ $applicant->last_name }}
                </h6>
                <div class="ml-drawer__badges">
                    <span class="badge {{ $type === 'renewal' ? 'badge-soft-gold' : 'badge-soft-navy' }}">{{ $type === 'renewal' ? 'Renewal' : 'New Applicant' }}</span>
                    @if ($archived)
                        <span class="admin-badge-archived">Archived</span>
                    @else
                        <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">{{ $dashboard->statusDisplayLabel($applicant->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Drawer body ── --}}
    <div class="ml-drawer__body">

        {{-- Personal Information --}}
        <div class="ml-info-section">
            <div class="ml-info-section__header">
                <i class="bi bi-person-vcard"></i> Personal Information
            </div>
            <div class="ml-info-grid">
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Full Name</div>
                    <div class="ml-info-grid__value">{{ $applicant->first_name }} {{ $applicant->middle_name ?? '' }} {{ $applicant->last_name }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Sex</div>
                    <div class="ml-info-grid__value">{{ $applicant->sex ? ucfirst($applicant->sex) : '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Date of Birth</div>
                    <div class="ml-info-grid__value">{{ $applicant->date_of_birth?->format('M d, Y') ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Age</div>
                    <div class="ml-info-grid__value">{{ $age !== null ? $age . ' years old' : '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Contact Number</div>
                    <div class="ml-info-grid__value">{{ $applicant->contact_number ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Email</div>
                    <div class="ml-info-grid__value">{{ $applicant->user?->email ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item ml-info-grid__item--full">
                    <div class="ml-info-grid__label">Address</div>
                    <div class="ml-info-grid__value">
                        {{ collect([$applicant->sitio, $applicant->landmark, $applicant->barangay, $applicant->city_municipality, $applicant->province])->filter()->implode(', ') ?: '—' }}
                    </div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Father's Name</div>
                    <div class="ml-info-grid__value">{{ $applicant->father_name ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Mother's Maiden Name</div>
                    <div class="ml-info-grid__value">{{ $applicant->mother_maiden_name ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Academic Information --}}
        <div class="ml-info-section">
            <div class="ml-info-section__header">
                <i class="bi bi-mortarboard"></i> Academic Information
            </div>
            <div class="ml-info-grid">
                <div class="ml-info-grid__item ml-info-grid__item--full">
                    <div class="ml-info-grid__label">School</div>
                    <div class="ml-info-grid__value">{{ $applicant->school_name ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Course / Program</div>
                    <div class="ml-info-grid__value">{{ $applicant->course ?? '—' }}</div>
                </div>
                <div class="ml-info-grid__item">
                    <div class="ml-info-grid__label">Year Level</div>
                    <div class="ml-info-grid__value">{{ $applicant->year_level ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Pipeline Tracker --}}
        <div class="ml-drawer-pipeline">
            <div class="ml-drawer-pipeline__title">
                <i class="bi bi-signpost-split"></i> Scholarship Pipeline
                <span class="ms-auto" style="font-weight:500; font-size:.72rem; color:var(--text-500);">
                    {{ $completedSteps }}/{{ count($steps) }} completed
                </span>
            </div>
            <div class="ml-drawer-pipeline__track">
                @foreach ($steps as $step)
                    <div class="ml-drawer-pipeline__step {{ $step['done'] ? 'ml-drawer-pipeline__step--done' : '' }}">
                        <span class="ml-drawer-pipeline__dot">
                            <i class="bi {{ $step['done'] ? 'bi-check-lg' : $step['icon'] }}"></i>
                        </span>
                        <span class="ml-drawer-pipeline__name">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pipeline detail sections (collapsible accordion) --}}

        {{-- Policy Verification --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-verif-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--navy"><i class="bi bi-shield-check"></i></span>
                <span class="ml-section-block__label">Policy Verification</span>
                <span class="ml-section-block__status">
                    @if ($verification)
                        {!! $verification->is_disqualified ? '<span class="admin-badge admin-badge-danger">Disqualified</span>' : '<span class="admin-badge admin-badge-success">Passed</span>' !!}
                    @else
                        <span class="admin-badge admin-badge-muted">Pending</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-verif-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($verification)
                        <dl class="ml-card2__list">
                            <div><dt>In SPES</dt><dd>{{ $verification->in_spes ? 'Yes' : 'No' }}</dd></div>
                            <div><dt>In 4Ps</dt><dd>{{ $verification->in_4ps ? 'Yes' : 'No' }}</dd></div>
                            <div><dt>One scholar / family</dt><dd>{{ $verification->one_scholar_per_family_ok ? 'Yes' : 'No' }}</dd></div>
                            @if ($verification->remarks)
                                <div><dt>Remarks</dt><dd>{{ $verification->remarks }}</dd></div>
                            @endif
                        </dl>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Not yet verified</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- MSWDO Assessment --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-mswdo-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--teal"><i class="bi bi-clipboard2-heart"></i></span>
                <span class="ml-section-block__label">MSWDO Assessment</span>
                <span class="ml-section-block__status">
                    @if ($mswdo)
                        {!! $mswdo->is_qualified ? '<span class="admin-badge admin-badge-success">Qualified</span>' : '<span class="admin-badge admin-badge-danger">Not qualified</span>' !!}
                    @else
                        <span class="admin-badge admin-badge-muted">Pending</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-mswdo-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($mswdo)
                        <dl class="ml-card2__list">
                            <div><dt>Referral slip</dt><dd>{{ $mswdo->referral_slip_no ?? '—' }}</dd></div>
                            <div><dt>Assessed</dt><dd>{{ $mswdo->assessed_at?->format('M d, Y') ?? '—' }}</dd></div>
                        </dl>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Not yet assessed</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Qualifying Exam --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-exam-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--blue"><i class="bi bi-pencil-square"></i></span>
                <span class="ml-section-block__label">Qualifying Exam</span>
                <span class="ml-section-block__status">
                    @if ($examResult)
                        {!! $examResult->passed ? '<span class="admin-badge admin-badge-success">Passed</span>' : '<span class="admin-badge admin-badge-danger">Failed</span>' !!}
                    @elseif ($applicant->exam_scheduled_at)
                        <span class="admin-badge admin-badge-exam">Scheduled</span>
                    @else
                        <span class="admin-badge admin-badge-muted">Pending</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-exam-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($examResult)
                        <dl class="ml-card2__list">
                            @if ($examResult->score !== null)<div><dt>Score</dt><dd>{{ $examResult->score }}</dd></div>@endif
                            <div><dt>Posted</dt><dd>{{ $examResult->posted_at?->format('M d, Y') ?? '—' }}</dd></div>
                        </dl>
                    @elseif ($applicant->exam_scheduled_at)
                        <p class="ml-card2__empty"><i class="bi bi-calendar-event"></i> Scheduled for <strong>{{ $applicant->exam_scheduled_at->format('M d, Y') }}</strong></p>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No exam record yet</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Orientation --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-orient-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--purple"><i class="bi bi-mortarboard"></i></span>
                <span class="ml-section-block__label">Orientation</span>
                <span class="ml-section-block__status">
                    @if ($orientation && $orientation->attended)
                        <span class="admin-badge admin-badge-success">Attended</span>
                    @elseif ($applicant->orientation_scheduled_at)
                        <span class="admin-badge admin-badge-exam">Scheduled</span>
                    @else
                        <span class="admin-badge admin-badge-muted">Pending</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-orient-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($orientation && $orientation->attended)
                        <dl class="ml-card2__list">
                            <div><dt>Attended</dt><dd>{{ $orientation->attended_at?->format('M d, Y h:i A') ?? '—' }}</dd></div>
                            <div><dt>Acknowledgement</dt><dd>{{ $orientation->signed_acknowledgement ? 'Signed' : 'Not signed' }}</dd></div>
                        </dl>
                    @elseif ($applicant->orientation_scheduled_at)
                        <p class="ml-card2__empty"><i class="bi bi-calendar-event"></i> Scheduled for <strong>{{ $applicant->orientation_scheduled_at->format('M d, Y') }}</strong></p>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No orientation record yet</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Requirements --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-reqs-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--green"><i class="bi bi-file-earmark-check"></i></span>
                <span class="ml-section-block__label">Requirements</span>
                <span class="ml-section-block__status">
                    @if ($reqs->count())
                        <span class="admin-badge {{ $submittedReqs === $reqs->count() ? 'admin-badge-success' : 'admin-badge-pending' }}">{{ $submittedReqs }}/{{ $reqs->count() }}</span>
                    @else
                        <span class="admin-badge admin-badge-muted">None</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-reqs-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($reqs->count())
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="ml-card2__progress">
                                <div class="ml-card2__progress-fill" style="<?php echo 'width:' . (int) $reqPercent . '%;'; ?>"></div>
                            </div>
                            <span class="ml-card2__progress-label">{{ $submittedReqs }}/{{ $reqs->count() }}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach ($reqs as $req)
                                <span class="ml-req {{ $req->is_submitted ? 'ml-req--done' : '' }}" title="{{ $req->requirement?->name ?? 'Requirement' }}">
                                    <i class="bi {{ $req->is_submitted ? 'bi-check-lg' : 'bi-clock' }}"></i>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No requirements on record</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Waste Compliance --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-waste-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--emerald"><i class="bi bi-recycle"></i></span>
                <span class="ml-section-block__label">Waste Compliance</span>
                <span class="ml-section-block__status">
                    @if ($waste->count())
                        {!! $wasteCompliant ? '<span class="admin-badge admin-badge-success">Compliant</span>' : '<span class="admin-badge admin-badge-pending">Pending</span>' !!}
                    @else
                        <span class="admin-badge admin-badge-muted">None</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-waste-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($waste->count())
                        <dl class="ml-card2__list">
                            @foreach ($waste->take(4) as $w)
                                <div><dt>{{ $w->semester }}</dt><dd>{{ number_format((float) $w->kilos_submitted, 1) }}/{{ number_format((float) $w->kilos_required, 1) }} kg</dd></div>
                            @endforeach
                            @if ($waste->count() > 4)
                                <div class="ml-card2__note">+ {{ $waste->count() - 4 }} earlier {{ Str::plural('semester', $waste->count() - 4) }} on record</div>
                            @endif
                        </dl>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No waste compliance record</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payout History --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-payout-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--gold"><i class="bi bi-cash-stack"></i></span>
                <span class="ml-section-block__label">Payout History</span>
                <span class="ml-section-block__status">
                    @if ($applicant->payouts->count())
                        <span class="admin-badge admin-badge-success">₱{{ number_format((float) $payoutTotal, 2) }}</span>
                    @else
                        <span class="admin-badge admin-badge-muted">None</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-payout-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($applicant->payouts->count())
                        <ul class="ml-payout-list">
                            @foreach ($applicant->payouts as $payout)
                                <li>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="small fw-semibold">₱{{ number_format((float) $payout->amount, 2) }}</span>
                                        <span class="text-muted-soft" style="font-size:.7rem;">{{ $payout->released_at?->format('M d, Y') }}</span>
                                    </div>
                                    @if ($payout->reference_no)
                                        <div class="text-muted-soft" style="font-size:.68rem;">Ref: {{ $payout->reference_no }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No payouts released</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Disqualification / Appeal --}}
        <div class="ml-section-block">
            <button class="ml-section-block__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#drawer-disq-{{ $applicant->id }}" aria-expanded="false">
                <span class="ml-section-block__icon ml-section-block__icon--red"><i class="bi bi-exclamation-octagon"></i></span>
                <span class="ml-section-block__label">Disqualification / Appeal</span>
                <span class="ml-section-block__status">
                    @if ($disqualification)
                        @if ($applicant->status === 'appealed')
                            <span class="admin-badge admin-badge-exam">Under appeal</span>
                        @else
                            <span class="admin-badge admin-badge-danger">Disqualified</span>
                        @endif
                    @else
                        <span class="admin-badge admin-badge-success" style="font-size:.68rem;">Clear</span>
                    @endif
                </span>
                <i class="bi bi-chevron-down ml-section-block__chevron"></i>
            </button>
            <div class="collapse" id="drawer-disq-{{ $applicant->id }}">
                <div class="ml-section-block__content">
                    @if ($disqualification)
                        <dl class="ml-card2__list">
                            <div><dt>Reason</dt><dd>{{ $disqualification->reason ?? '—' }}</dd></div>
                            @if ($disqualification->appeals->count())
                                <div><dt>Appeals</dt><dd>{{ $disqualification->appeals->count() }} submitted</dd></div>
                            @endif
                        </dl>
                    @else
                        <p class="ml-card2__empty"><i class="bi bi-check-circle"></i> No disqualification on record</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── Drawer footer ── --}}
    <div class="ml-drawer__footer">
        <div class="ml-drawer__footer-meta">
            <i class="bi bi-clock-history"></i>
            Created {{ $accountCreated?->format('M d, Y') ?? '—' }}
            @if ($archived)
                <span class="dot-sep">•</span>
                <span class="text-danger"><i class="bi bi-archive me-1"></i>Archived {{ $deletedAt?->format('M d, Y') ?? '—' }}</span>
            @endif
        </div>
        @if ($archived)
            <span class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1" style="cursor:default;">
                <i class="bi bi-archive-fill"></i> Read-only
            </span>
        @else
            <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-sm btn-navy d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-up-right"></i> Full profile
            </a>
        @endif
    </div>
</template>
@endforeach

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var drawer = document.getElementById('mlDrawer');
        var backdrop = document.getElementById('mlDrawerBackdrop');
        var content = document.getElementById('mlDrawerContent');
        var activeApplicantId = null;
        var activeRow = null;

        function openDrawer(applicantId) {
            if (activeApplicantId === applicantId && drawer.classList.contains('is-open')) {
                closeDrawer();
                return;
            }

            var tpl = document.getElementById('ml-data-' + applicantId);
            if (!tpl) return;

            // Clone template content into the drawer
            content.innerHTML = '';
            content.appendChild(tpl.content.cloneNode(true));

            // Wire up close button inside the cloned content
            var closeBtn = content.querySelector('#mlDrawerClose');
            if (closeBtn) {
                closeBtn.removeAttribute('id');
                closeBtn.addEventListener('click', closeDrawer);
            }

            // Scroll drawer body to top on open
            var drawerBody = content.querySelector('.ml-drawer__body');
            if (drawerBody) {
                drawerBody.scrollTop = 0;
            }

            // Open drawer
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            backdrop.classList.add('is-open');
            document.body.classList.add('ml-drawer-open');

            // Highlight active row
            if (activeRow) activeRow.classList.remove('is-active');
            var row = document.querySelector('[data-applicant-id="' + applicantId + '"]');
            if (row) {
                row.classList.add('is-active');
                activeRow = row;
            }
            activeApplicantId = applicantId;
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            backdrop.classList.remove('is-open');
            document.body.classList.remove('ml-drawer-open');
            if (activeRow) {
                activeRow.classList.remove('is-active');
                activeRow = null;
            }
            activeApplicantId = null;
        }

        // Click row → open drawer
        document.querySelectorAll('.applicant-row[data-applicant-id]').forEach(function (row) {
            row.addEventListener('click', function (e) {
                // Don't trigger if user clicked an interactive link or button inside the row
                if (e.target.closest('a, button, input, select')) return;
                openDrawer(this.dataset.applicantId);
            });
            row.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openDrawer(this.dataset.applicantId);
                }
            });
        });

        // Backdrop click → close
        backdrop.addEventListener('click', closeDrawer);

        // Escape key → close
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });
    });
</script>
@endpush
