@extends('layouts.app')
@section('title', 'Master List')
@section('subtitle', 'Complete record of every applicant with their journey through the scholarship pipeline')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:36px;height:36px;font-size:1rem;flex-shrink:0;">
                <i class="bi bi-clipboard-data"></i>
            </span>
            Master List
        </h2>
        <p class="small text-muted-soft mb-0">
            Every applicant on record, with their full pipeline status — filter by account creation date, applicant type, or name.
        </p>
    </div>
    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['search']))
        <a href="{{ route('admin.master-list.index') }}" class="btn btn-sm btn-ghost text-muted-soft d-inline-flex align-items-center gap-1 flex-shrink-0">
            <i class="bi bi-x-lg"></i> Clear filters
        </a>
    @endif
</div>

<!-- Filter bar -->
<div class="admin-panel mb-4">
    <div class="admin-panel__body">
        <form method="GET" action="{{ route('admin.master-list.index') }}" id="masterListForm" class="row g-3 align-items-end">
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-calendar-week me-1"></i>Account Created Date</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="created_date" value="{{ $filters['created_date'] ?? '' }}" class="form-control border-start-0">
                </div>
                <div class="form-text mt-1" style="font-size:.68rem;">Shows applicants whose account was created on this date.</div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-tag me-1"></i>Applicant Type</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-funnel"></i></span>
                    <select name="program_type" class="form-select form-select-sm border-start-0">
                        <option value="">All types</option>
                        <option value="new" {{ ($filters['program_type'] ?? '') === 'new' ? 'selected' : '' }}>New Applicant</option>
                        <option value="renewal" {{ ($filters['program_type'] ?? '') === 'renewal' ? 'selected' : '' }}>Renewal</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
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
                @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['search']))
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
    <div class="col-6 col-xl-3">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #14213D, #1a2d50);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-people-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['total']) }}</p>
                <p class="admin-kpi__label text-white-50">Total Applicants</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #E8A33D, #d18a1f);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.2); color:#fff;"><i class="bi bi-hourglass-split"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['in_progress']) }}</p>
                <p class="admin-kpi__label text-white-50">In Progress</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #2c65ac, #1f4d85);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-patch-check-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['qualified']) }}</p>
                <p class="admin-kpi__label text-white-50">Qualified</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #1E6B3C, #15512d);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-cash-coin"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['released']) }}</p>
                <p class="admin-kpi__label text-white-50">Scholarship Released</p>
            </div>
        </div>
    </div>
</div>

<!-- Master table -->
<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2 class="h6 fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-table"></i> Applicant Records
                <span class="badge-soft-navy ms-1">{{ number_format($applicants->count()) }}</span>
            </h2>
            <p class="small text-muted-soft mb-0 mt-1">Click the chevron to expand a full record.</p>
        </div>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($applicants->isEmpty())
            <div class="empty-state py-5">
                <span class="empty-state__icon"><i class="bi bi-clipboard-x"></i></span>
                <h6>No applicants found</h6>
                <p>
                    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['search']))
                        No applicants match the current filters. Try a different date or clear the filters.
                    @else
                        There are no applicant records yet.
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
                        <th class="text-end pe-3 col-ml-toggle"></th>
                    </tr>
                </thead>
                <tbody>
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
                    @endphp
                    <tr class="applicant-row">
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
                            <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">{{ $dashboard->statusDisplayLabel($applicant->status) }}</span>
                        </td>
                        <td class="text-end pe-3 col-ml-toggle">
                            <button type="button" class="btn-icon-sm ml-expand" data-bs-toggle="collapse" data-bs-target="#ml-detail-{{ $applicant->id }}" aria-expanded="false" title="View full record">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="ml-detail-row">
                        <td colspan="7" class="p-0 border-0">
                            <div id="ml-detail-{{ $applicant->id }}" class="collapse ml-detail">
                                <div class="ml-detail__inner">

                                    {{-- ── Profile header banner ── --}}
                                    <div class="ml-profile-banner">
                                        <div class="ml-profile-banner__avatar">
                                            {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-profile-banner__info">
                                            <h6 class="ml-profile-banner__name">
                                                {{ $applicant->first_name }} {{ $applicant->middle_name ? mb_substr($applicant->middle_name, 0, 1) . '.' : '' }} {{ $applicant->last_name }}
                                            </h6>
                                            <div class="ml-profile-banner__meta">
                                                <span><i class="bi bi-envelope me-1"></i>{{ $applicant->user?->email ?? '—' }}</span>
                                                <span><i class="bi bi-telephone me-1"></i>{{ $applicant->contact_number ?? '—' }}</span>
                                                <span><i class="bi bi-geo-alt me-1"></i>{{ collect([$applicant->barangay, $applicant->city_municipality, $applicant->province])->filter()->implode(', ') ?: '—' }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-profile-banner__badges">
                                            <span class="badge {{ $type === 'renewal' ? 'badge-soft-gold' : 'badge-soft-navy' }}">{{ $type === 'renewal' ? 'Renewal' : 'New Applicant' }}</span>
                                            <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">{{ $dashboard->statusDisplayLabel($applicant->status) }}</span>
                                        </div>
                                    </div>

                                    {{-- ── Pipeline mini-tracker ── --}}
                                    @php
                                        $steps = [
                                            ['label' => 'Verification', 'icon' => 'bi-shield-check',       'done' => (bool) $verification && !$verification->is_disqualified],
                                            ['label' => 'MSWDO',        'icon' => 'bi-clipboard2-heart',   'done' => (bool) $mswdo && $mswdo->is_qualified],
                                            ['label' => 'Exam',         'icon' => 'bi-pencil-square',      'done' => (bool) $examResult && $examResult->passed],
                                            ['label' => 'Orientation',  'icon' => 'bi-mortarboard',        'done' => (bool) $orientation && $orientation->attended],
                                            ['label' => 'Requirements', 'icon' => 'bi-file-earmark-check', 'done' => $reqs->count() > 0 && $submittedReqs === $reqs->count()],
                                            ['label' => 'Payout',       'icon' => 'bi-cash-coin',          'done' => $applicant->payouts->count() > 0],
                                        ];
                                        $completedSteps = collect($steps)->where('done', true)->count();
                                    @endphp
                                    <div class="ml-pipeline">
                                        <div class="ml-pipeline__bar">
                                            <div class="ml-pipeline__fill" style="width: {{ count($steps) ? round(($completedSteps / count($steps)) * 100) : 0 }}%;"></div>
                                        </div>
                                        <div class="ml-pipeline__steps">
                                            @foreach ($steps as $step)
                                                <div class="ml-pipeline__step {{ $step['done'] ? 'ml-pipeline__step--done' : '' }}">
                                                    <span class="ml-pipeline__dot">
                                                        <i class="bi {{ $step['done'] ? 'bi-check-lg' : $step['icon'] }}"></i>
                                                    </span>
                                                    <span class="ml-pipeline__label">{{ $step['label'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- ── Section cards grid ── --}}
                                    <div class="ml-cards-grid">

                                        {{-- Policy Verification --}}
                                        <div class="ml-card2 ml-card2--navy">
                                            <div class="ml-card2__icon"><i class="bi bi-shield-check"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Policy Verification</h6>
                                                @if ($verification)
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Status</dt><dd>{!! $verification->is_disqualified ? '<span class="admin-badge admin-badge-danger">Disqualified</span>' : '<span class="admin-badge admin-badge-success">Passed</span>' !!}</dd></div>
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

                                        {{-- MSWDO Assessment --}}
                                        <div class="ml-card2 ml-card2--teal">
                                            <div class="ml-card2__icon"><i class="bi bi-clipboard2-heart"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">MSWDO Assessment</h6>
                                                @if ($mswdo)
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Result</dt><dd>{!! $mswdo->is_qualified ? '<span class="admin-badge admin-badge-success">Qualified</span>' : '<span class="admin-badge admin-badge-danger">Not qualified</span>' !!}</dd></div>
                                                        <div><dt>Referral slip</dt><dd>{{ $mswdo->referral_slip_no ?? '—' }}</dd></div>
                                                        <div><dt>Assessed</dt><dd>{{ $mswdo->assessed_at?->format('M d, Y') ?? '—' }}</dd></div>
                                                    </dl>
                                                @else
                                                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Not yet assessed</p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Qualifying Exam --}}
                                        <div class="ml-card2 ml-card2--blue">
                                            <div class="ml-card2__icon"><i class="bi bi-pencil-square"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Qualifying Exam</h6>
                                                @if ($examResult)
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Result</dt><dd>{!! $examResult->passed ? '<span class="admin-badge admin-badge-success">Passed</span>' : '<span class="admin-badge admin-badge-danger">Failed</span>' !!}</dd></div>
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

                                        {{-- Orientation --}}
                                        <div class="ml-card2 ml-card2--purple">
                                            <div class="ml-card2__icon"><i class="bi bi-mortarboard"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Orientation</h6>
                                                @if ($orientation && $orientation->attended)
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Status</dt><dd><span class="admin-badge admin-badge-success">Attended</span></dd></div>
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

                                        {{-- Requirements --}}
                                        <div class="ml-card2 ml-card2--green">
                                            <div class="ml-card2__icon"><i class="bi bi-file-earmark-check"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Requirements</h6>
                                                @if ($reqs->count())
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <div class="ml-card2__progress">
                                                            <div class="ml-card2__progress-fill" style="width: {{ $reqs->count() ? round(($submittedReqs / $reqs->count()) * 100) : 0 }}%;"></div>
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

                                        {{-- Waste Compliance --}}
                                        <div class="ml-card2 ml-card2--emerald">
                                            <div class="ml-card2__icon"><i class="bi bi-recycle"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Waste Compliance</h6>
                                                @if ($waste->count())
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Status</dt><dd>{!! $wasteCompliant ? '<span class="admin-badge admin-badge-success">Compliant</span>' : '<span class="admin-badge admin-badge-pending">Pending</span>' !!}</dd></div>
                                                        @foreach ($waste as $w)
                                                            <div><dt>Sem {{ $w->semester }}</dt><dd>{{ $w->kilos_submitted ?? 0 }}/{{ $w->kilos_required ?? 0 }} kg</dd></div>
                                                        @endforeach
                                                    </dl>
                                                @else
                                                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No waste compliance record</p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Payout History --}}
                                        <div class="ml-card2 ml-card2--gold">
                                            <div class="ml-card2__icon"><i class="bi bi-cash-stack"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Payout History</h6>
                                                @if ($applicant->payouts->count())
                                                    <div class="ml-card2__payout-total">
                                                        <span class="ml-card2__payout-amount">₱{{ number_format((float) $payoutTotal, 2) }}</span>
                                                        <span class="ml-card2__payout-count">{{ $applicant->payouts->count() }} release{{ $applicant->payouts->count() > 1 ? 's' : '' }}</span>
                                                    </div>
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

                                        {{-- Disqualification / Appeal --}}
                                        <div class="ml-card2 ml-card2--red">
                                            <div class="ml-card2__icon"><i class="bi bi-exclamation-octagon"></i></div>
                                            <div class="ml-card2__body">
                                                <h6 class="ml-card2__title">Disqualification / Appeal</h6>
                                                @if ($disqualification)
                                                    <dl class="ml-card2__list">
                                                        <div><dt>Reason</dt><dd>{{ $disqualification->reason ?? '—' }}</dd></div>
                                                        <div><dt>Status</dt><dd>
                                                            @if ($applicant->status === 'appealed')
                                                                <span class="admin-badge admin-badge-exam">Under appeal</span>
                                                            @else
                                                                <span class="admin-badge admin-badge-danger">Disqualified</span>
                                                            @endif
                                                        </dd></div>
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

                                    {{-- ── Footer actions ── --}}
                                    <div class="ml-detail__footer">
                                        <div class="ml-detail__footer-meta">
                                            <i class="bi bi-clock-history"></i>
                                            Account created {{ $accountCreated?->format('M d, Y \a\t h:i A') ?? '—' }}
                                        </div>
                                        <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-sm btn-navy d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-arrow-up-right"></i> Open full profile
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (el) {
            el.addEventListener('click', function () {
                this.classList.toggle('is-open');
                const icon = this.querySelector('i');
                if (icon) icon.classList.toggle('bi-chevron-down');
                if (icon) icon.classList.toggle('bi-chevron-up');
            });
        });
    });
</script>
@endpush