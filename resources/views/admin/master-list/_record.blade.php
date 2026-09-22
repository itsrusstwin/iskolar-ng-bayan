{{--
    Full applicant record, rendered as an HTML fragment.

    Served by MasterListController@show and injected into the master list
    slide-out drawer over fetch(). It is a fragment, not a page — it must not
    extend a layout and must not push scripts, because it is inserted into an
    already-rendered document.
--}}
@php
    use Illuminate\Support\Str;

    $type = $applicant->program_type === 'renewal' ? 'renewal' : 'new';
    $verification = $applicant->verification;
    $mswdo = $applicant->mswdoAssessment;
    $examResults = $applicant->examResults->sortByDesc(fn ($r) => $r->posted_at ?? $r->created_at);
    $examResult = $examResults->first();
    $orientation = $applicant->orientation;

    $reqs = $applicant->requirements->sortBy(fn ($r) => $r->requirement?->name ?? '');
    $submittedReqs = $reqs->where('is_submitted', true)->count();
    $approvedReqs = $reqs->where('approval_status', 'approved')->count();
    $rejectedReqs = $reqs->where('approval_status', 'rejected')->count();
    $reqPercent = $reqs->count() ? (int) round(($submittedReqs / $reqs->count()) * 100) : 0;

    $waste = $applicant->wasteCompliance->sortByDesc('semester');
    $wasteCompliant = $waste->count() > 0 && $waste->every(fn ($w) => (bool) $w->is_compliant);
    $wasteKilos = $waste->sum(fn ($w) => (float) $w->kilos_submitted);

    $payouts = $applicant->payouts->sortByDesc(fn ($p) => $p->released_at ?? $p->created_at);
    $payoutTotal = $payouts->sum('amount');

    $disqualifications = $applicant->disqualifications->sortByDesc(fn ($d) => $d->notice_issued_at ?? $d->created_at);
    $disqualification = $disqualifications->first();
    $appeals = $disqualifications->flatMap->appeals;

    $logs = $applicant->auditLogs->sortByDesc('created_at');

    $accountCreated = $applicant->user?->created_at ?? $applicant->created_at;
    $archived = (bool) $applicant->deleted_at;
    $deletedAt = $applicant->deleted_at;

    $steps = [
        ['label' => 'Verification', 'icon' => 'bi-shield-check',     'done' => (bool) $verification && !$verification->is_disqualified],
        ['label' => 'MSWDO',        'icon' => 'bi-clipboard2-heart', 'done' => (bool) $mswdo && $mswdo->is_qualified],
        ['label' => 'Exam',         'icon' => 'bi-pencil-square',    'done' => (bool) $examResult && $examResult->passed],
        ['label' => 'Orientation',  'icon' => 'bi-mortarboard',      'done' => (bool) $orientation && $orientation->attended],
        ['label' => 'Requirements', 'icon' => 'bi-file-earmark-check', 'done' => $reqs->count() > 0 && $submittedReqs === $reqs->count()],
        ['label' => 'Payout',       'icon' => 'bi-cash-coin',        'done' => $payouts->count() > 0],
    ];
    $completedSteps = collect($steps)->where('done', true)->count();

    $age = $applicant->date_of_birth ? $applicant->date_of_birth->age : null;

    $address = collect([
        $applicant->sitio,
        $applicant->landmark,
        $applicant->barangay,
        $applicant->city_municipality,
        $applicant->province,
    ])->filter()->implode(', ');
@endphp

{{-- Critical drawer styles.
     This fragment is loaded with fetch(), so these rules intentionally live
     with the record markup and do not depend on Bootstrap collapse styles. --}}
<style>
    #mlDrawer .ml-drawer__body {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.25rem !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    #mlDrawer .ml-drawer__body > .ml-info-section,
    #mlDrawer .ml-drawer__body > .ml-drawer-pipeline,
    #mlDrawer .ml-drawer__body > .ml-section-block {
        display: block !important;
        flex: 0 0 auto !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        min-width: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-info-section__header {
        display: flex !important;
        min-height: 42px !important;
        height: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-info-grid {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-info-grid__item {
        display: block !important;
        width: auto !important;
        height: auto !important;
        min-height: 55px !important;
        padding: .7rem 1rem !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-info-grid__label,
    #mlDrawer .ml-info-grid__value {
        display: block !important;
        height: auto !important;
        min-height: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
        overflow: visible !important;
    }

    #mlDrawer .ml-info-grid__value {
        color: var(--text-900) !important;
        white-space: normal !important;
        overflow-wrap: anywhere !important;
        word-break: break-word !important;
    }

    #mlDrawer .ml-section-block__toggle {
        display: flex !important;
        width: 100% !important;
        min-height: 52px !important;
        height: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
        cursor: pointer !important;
    }

    #mlDrawer .ml-drawer-collapse {
        display: none;
        width: 100%;
        height: auto;
        overflow: visible;
    }

    #mlDrawer .ml-drawer-collapse.show {
        display: block !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-section-block__content {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        min-height: 40px !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    #mlDrawer .ml-card2__list,
    #mlDrawer .ml-record-list,
    #mlDrawer .ml-payout-list,
    #mlDrawer .ml-timeline {
        height: auto !important;
        min-height: 0 !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    @media (max-width: 700px) {
        #mlDrawer .ml-info-grid {
            grid-template-columns: minmax(0, 1fr) !important;
        }
    }
</style>

{{-- ── Drawer header ── --}}
<div class="ml-drawer__header">
    <button type="button" class="ml-drawer__close" data-ml-close title="Close panel">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="ml-drawer__identity">
        <div class="ml-drawer__avatar">
            {{ strtoupper(Str::substr($applicant->first_name, 0, 1) . Str::substr($applicant->last_name, 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <h6 class="ml-drawer__name">
                {{ $applicant->first_name }}
                {{ $applicant->middle_name ? Str::substr($applicant->middle_name, 0, 1) . '.' : '' }}
                {{ $applicant->last_name }}
            </h6>
            <div class="ml-drawer__badges">
                <span class="badge {{ $type === 'renewal' ? 'badge-soft-gold' : 'badge-soft-navy' }}">
                    {{ $type === 'renewal' ? 'Renewal' : 'New Applicant' }}
                </span>
                @if ($archived)
                    <span class="admin-badge-archived">Archived</span>
                @else
                    <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">
                        {{ $dashboard->statusDisplayLabel($applicant->status) }}
                    </span>
                @endif
                <span class="ml-ref">#{{ str_pad((string) $applicant->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ── Drawer body ── --}}
<div class="ml-drawer__body">

    @if ($archived)
        <div class="ml-record-notice ml-record-notice--archived">
            <i class="bi bi-archive-fill"></i>
            <span>
                This account was archived on
                <strong>{{ $deletedAt?->format('M d, Y') ?? '—' }}</strong>.
                The record is kept for reference and is read-only.
            </span>
        </div>
    @endif

    {{-- Personal Information --}}
    <div class="ml-info-section">
        <div class="ml-info-section__header">
            <i class="bi bi-person-vcard"></i> Personal Information
        </div>
        <div class="ml-info-grid">
            <div class="ml-info-grid__item ml-info-grid__item--full">
                <div class="ml-info-grid__label">Full Name</div>
                <div class="ml-info-grid__value">{{ trim("{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name}") }}</div>
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
            <div class="ml-info-grid__item ml-info-grid__item--full">
                <div class="ml-info-grid__label">Address</div>
                <div class="ml-info-grid__value">{{ $address ?: '—' }}</div>
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

    {{-- Account Information --}}
    <div class="ml-info-section">
        <div class="ml-info-section__header">
            <i class="bi bi-person-badge"></i> Account
        </div>
        <div class="ml-info-grid">
            <div class="ml-info-grid__item ml-info-grid__item--full">
                <div class="ml-info-grid__label">Email</div>
                <div class="ml-info-grid__value">{{ $applicant->user?->email ?? '—' }}</div>
            </div>
            <div class="ml-info-grid__item">
                <div class="ml-info-grid__label">Account Created</div>
                <div class="ml-info-grid__value">{{ $accountCreated?->format('M d, Y') ?? '—' }}</div>
            </div>
            <div class="ml-info-grid__item">
                <div class="ml-info-grid__label">Last Seen</div>
                <div class="ml-info-grid__value">
                    @if ($applicant->user?->isOnline())
                        <span class="ml-online-flag"><span class="online-dot online-dot--inline"></span> Online now</span>
                    @elseif ($applicant->user?->last_seen_at)
                        {{ $applicant->user->last_seen_at->diffForHumans() }}
                    @else
                        Never signed in
                    @endif
                </div>
            </div>
            <div class="ml-info-grid__item">
                <div class="ml-info-grid__label">Sign-in Method</div>
                <div class="ml-info-grid__value">{{ $applicant->user?->provider ? ucfirst($applicant->user->provider) : 'Email & password' }}</div>
            </div>
            <div class="ml-info-grid__item">
                <div class="ml-info-grid__label">Terms Accepted</div>
               <div class="ml-info-grid__value">
    {{ $applicant->user?->terms_accepted_at ? \Illuminate\Support\Carbon::parse($applicant->user->terms_accepted_at)->format('M d, Y') : 'Not yet' }}
</div>
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

    {{-- ══ Pipeline detail sections ══ --}}

    {{-- Policy Verification --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-verif" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--navy"><i class="bi bi-shield-check"></i></span>
            <span class="ml-section-block__label">Policy Verification</span>
            <span class="ml-section-block__status">
                @if ($verification)
                    @if ($verification->is_disqualified)
                        <span class="admin-badge admin-badge-danger">Disqualified</span>
                    @else
                        <span class="admin-badge admin-badge-success">Passed</span>
                    @endif
                @else
                    <span class="admin-badge admin-badge-muted">Pending</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-verif">
            <div class="ml-section-block__content">
                @if ($verification)
                    <dl class="ml-card2__list">
                        <div><dt>Enrolled in SPES</dt><dd>{{ $verification->in_spes ? 'Yes' : 'No' }}</dd></div>
                        <div><dt>Enrolled in 4Ps</dt><dd>{{ $verification->in_4ps ? 'Yes' : 'No' }}</dd></div>
                        <div><dt>One scholar per family</dt><dd>{{ $verification->one_scholar_per_family_ok ? 'Satisfied' : 'Not satisfied' }}</dd></div>
                        <div><dt>Outcome</dt><dd>{{ $verification->is_disqualified ? 'Disqualified' : 'Cleared' }}</dd></div>
                        @if ($verification->remarks)
                            <div><dt>Remarks</dt><dd>{{ $verification->remarks }}</dd></div>
                        @endif
                        <div><dt>Recorded</dt><dd>{{ $verification->created_at?->format('M d, Y h:i A') ?? '—' }}</dd></div>
                    </dl>
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Not yet verified</p>
                @endif
            </div>
        </div>
    </div>

    {{-- MSWDO Assessment --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-mswdo" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--teal"><i class="bi bi-clipboard2-heart"></i></span>
            <span class="ml-section-block__label">MSWDO Assessment</span>
            <span class="ml-section-block__status">
                @if ($mswdo)
                    @if ($mswdo->is_qualified)
                        <span class="admin-badge admin-badge-success">Qualified</span>
                    @else
                        <span class="admin-badge admin-badge-danger">Not qualified</span>
                    @endif
                @else
                    <span class="admin-badge admin-badge-muted">Pending</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-mswdo">
            <div class="ml-section-block__content">
                @if ($mswdo)
                    <dl class="ml-card2__list">
                        <div><dt>Referral slip no.</dt><dd>{{ $mswdo->referral_slip_no ?? '—' }}</dd></div>
                        <div><dt>Result</dt><dd>{{ $mswdo->is_qualified ? 'Qualified' : 'Not qualified' }}</dd></div>
                        <div><dt>Assessed on</dt><dd>{{ $mswdo->assessed_at?->format('M d, Y') ?? '—' }}</dd></div>
                    </dl>
                    @if ($mswdo->social_case_study_report_path)
                        <button type="button" class="ml-file-link"
                                onclick="previewFile('{{ asset('storage/' . $mswdo->social_case_study_report_path) }}', 'Social Case Study Report')">
                            <i class="bi bi-file-earmark-text"></i> View Social Case Study Report
                        </button>
                    @else
                        <p class="ml-card2__note mb-0">No case study report on file.</p>
                    @endif
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Not yet assessed</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Qualifying Exam --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-exam" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--blue"><i class="bi bi-pencil-square"></i></span>
            <span class="ml-section-block__label">Qualifying Exam</span>
            <span class="ml-section-block__status">
                @if ($examResult)
                    @if ($examResult->passed)
                        <span class="admin-badge admin-badge-success">Passed</span>
                    @else
                        <span class="admin-badge admin-badge-danger">Failed</span>
                    @endif
                @elseif ($applicant->exam_scheduled_at)
                    <span class="admin-badge admin-badge-exam">Scheduled</span>
                @else
                    <span class="admin-badge admin-badge-muted">Pending</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-exam">
            <div class="ml-section-block__content">
                @if ($applicant->exam_scheduled_at)
                    <p class="ml-card2__note">
                        <i class="bi bi-calendar-event"></i>
                        Scheduled for <strong>{{ $applicant->exam_scheduled_at->format('M d, Y h:i A') }}</strong>
                    </p>
                @endif

                @if ($examResults->count())
                    <ul class="ml-record-list">
                        @foreach ($examResults as $result)
                            <li class="ml-record-list__item">
                                <div class="ml-record-list__head">
                                    <span class="ml-record-list__title">
                                        {{ $result->exam?->name ?? 'Qualifying Exam' }}
                                        @if ($examResults->count() > 1)
                                            <span class="ml-card2__note">· attempt {{ $examResults->count() - $loop->index }}</span>
                                        @endif
                                    </span>
                                    @if ($result->passed)
                                        <span class="admin-badge admin-badge-success">Passed</span>
                                    @else
                                        <span class="admin-badge admin-badge-danger">Failed</span>
                                    @endif
                                </div>
                                <div class="ml-record-list__meta">
                                    @if ($result->score !== null)
                                        <span><i class="bi bi-123"></i> Score: <strong>{{ $result->score }}</strong></span>
                                    @endif
                                    <span><i class="bi bi-calendar3"></i> Posted {{ $result->posted_at?->format('M d, Y') ?? '—' }}</span>
                                </div>
                                @if ($result->file_path)
                                    <button type="button" class="ml-file-link"
                                            onclick="previewFile('{{ asset('storage/' . $result->file_path) }}', 'Exam Result File')">
                                        <i class="bi bi-file-earmark-arrow-down"></i> View result file
                                    </button>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @elseif (!$applicant->exam_scheduled_at)
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No exam record yet</p>
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> Result not posted yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Orientation --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-orient" aria-expanded="false">
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
        <div class="ml-drawer-collapse" id="drawer-orient">
            <div class="ml-section-block__content">
                @if ($applicant->orientation_scheduled_at)
                    <p class="ml-card2__note">
                        <i class="bi bi-calendar-event"></i>
                        Scheduled for <strong>{{ $applicant->orientation_scheduled_at->format('M d, Y h:i A') }}</strong>
                    </p>
                @endif
                @if ($orientation)
                    <dl class="ml-card2__list">
                        <div><dt>Attendance</dt><dd>{{ $orientation->attended ? 'Attended' : 'Did not attend' }}</dd></div>
                        <div><dt>Attended on</dt><dd>{{ $orientation->attended_at?->format('M d, Y h:i A') ?? '—' }}</dd></div>
                        <div><dt>Acknowledgement</dt><dd>{{ $orientation->signed_acknowledgement ? 'Signed' : 'Not signed' }}</dd></div>
                    </dl>
                @elseif (!$applicant->orientation_scheduled_at)
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No orientation record yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Requirements --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-reqs" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--green"><i class="bi bi-file-earmark-check"></i></span>
            <span class="ml-section-block__label">Requirements</span>
            <span class="ml-section-block__status">
                @if ($reqs->count())
                    <span class="admin-badge {{ $submittedReqs === $reqs->count() ? 'admin-badge-success' : 'admin-badge-pending' }}">
                        {{ $submittedReqs }}/{{ $reqs->count() }}
                    </span>
                @else
                    <span class="admin-badge admin-badge-muted">None</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-reqs">
            <div class="ml-section-block__content">
                @if ($reqs->count())
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="ml-card2__progress">
                            <div class="ml-card2__progress-fill" style="width: {{ $reqPercent }}%;"></div>
                        </div>
                        <span class="ml-card2__progress-label">{{ $reqPercent }}%</span>
                    </div>
                    <p class="ml-card2__note">
                        {{ $submittedReqs }} submitted · {{ $approvedReqs }} approved · {{ $rejectedReqs }} rejected
                    </p>
                    <ul class="ml-record-list">
                        @foreach ($reqs as $req)
                            <li class="ml-record-list__item">
                                <div class="ml-record-list__head">
                                    <span class="ml-record-list__title">
                                        <i class="bi {{ $req->is_submitted ? 'bi-file-earmark-check' : 'bi-file-earmark-x' }} me-1"></i>
                                        {{ $req->requirement?->name ?? 'Unnamed requirement' }}
                                    </span>
                                    @if ($req->approval_status === 'approved')
                                        <span class="admin-badge admin-badge-success">Approved</span>
                                    @elseif ($req->approval_status === 'rejected')
                                        <span class="admin-badge admin-badge-danger">Rejected</span>
                                    @elseif ($req->is_submitted)
                                        <span class="admin-badge admin-badge-pending">For review</span>
                                    @else
                                        <span class="admin-badge admin-badge-muted">Not submitted</span>
                                    @endif
                                </div>
                                <div class="ml-record-list__meta">
                                    <span>
                                        <i class="bi bi-clock"></i>
                                        {{ $req->submitted_at ? 'Submitted ' . \Illuminate\Support\Carbon::parse($req->submitted_at)->format('M d, Y') : 'Awaiting upload' }}
                                    </span>
                                </div>
                                @if ($req->file_path)
                                    <button type="button" class="ml-file-link"
                                            onclick="previewFile('{{ asset('storage/' . $req->file_path) }}', '{{ addslashes($req->requirement?->name ?? 'Requirement') }}')">
                                        <i class="bi bi-eye"></i> View file
                                    </button>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No requirements on record</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Waste Compliance --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-waste" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--emerald"><i class="bi bi-recycle"></i></span>
            <span class="ml-section-block__label">Waste Compliance</span>
            <span class="ml-section-block__status">
                @if ($waste->count())
                    @if ($wasteCompliant)
                        <span class="admin-badge admin-badge-success">Compliant</span>
                    @else
                        <span class="admin-badge admin-badge-pending">Pending</span>
                    @endif
                @else
                    <span class="admin-badge admin-badge-muted">None</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-waste">
            <div class="ml-section-block__content">
                @if ($waste->count())
                    <p class="ml-card2__note">
                        {{ number_format($wasteKilos, 1) }} kg submitted across
                        {{ $waste->count() }} {{ Str::plural('semester', $waste->count()) }}
                    </p>
                    <ul class="ml-record-list">
                        @foreach ($waste as $w)
                            @php
                                $required = (float) $w->kilos_required;
                                $submitted = (float) $w->kilos_submitted;
                                $pct = $required > 0 ? (int) min(100, round(($submitted / $required) * 100)) : 0;
                            @endphp
                            <li class="ml-record-list__item">
                                <div class="ml-record-list__head">
                                    <span class="ml-record-list__title">{{ $w->semester }}</span>
                                    @if ($w->is_compliant)
                                        <span class="admin-badge admin-badge-success">Compliant</span>
                                    @else
                                        <span class="admin-badge admin-badge-pending">Short</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="ml-card2__progress">
                                        <div class="ml-card2__progress-fill" style="width: {{ $pct }}%;"></div>
                                    </div>
                                    <span class="ml-card2__progress-label">
                                        {{ number_format($submitted, 1) }}/{{ number_format($required, 1) }} kg
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No waste compliance record</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Payout History --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-payout" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--gold"><i class="bi bi-cash-stack"></i></span>
            <span class="ml-section-block__label">Payout History</span>
            <span class="ml-section-block__status">
                @if ($payouts->count())
                    <span class="admin-badge admin-badge-success">₱{{ number_format((float) $payoutTotal, 2) }}</span>
                @else
                    <span class="admin-badge admin-badge-muted">None</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-payout">
            <div class="ml-section-block__content">
                @if ($payouts->count())
                    <p class="ml-card2__note">
                        {{ $payouts->count() }} {{ Str::plural('release', $payouts->count()) }} ·
                        total <strong>₱{{ number_format((float) $payoutTotal, 2) }}</strong>
                    </p>
                    <ul class="ml-payout-list">
                        @foreach ($payouts as $payout)
                            <li>
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="small fw-semibold">₱{{ number_format((float) $payout->amount, 2) }}</span>
                                    <span class="text-muted-soft" style="font-size:.7rem;">
                                        {{ $payout->released_at?->format('M d, Y') ?? 'Date not recorded' }}
                                    </span>
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
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-disq" aria-expanded="false">
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
        <div class="ml-drawer-collapse" id="drawer-disq">
            <div class="ml-section-block__content">
                @if ($disqualifications->count())
                    <ul class="ml-record-list">
                        @foreach ($disqualifications as $disq)
                            <li class="ml-record-list__item">
                                <div class="ml-record-list__head">
                                    <span class="ml-record-list__title">
                                        {{ $disq->stage ? ucfirst(str_replace('_', ' ', $disq->stage)) : 'Disqualification' }}
                                    </span>
                                    <span class="admin-badge admin-badge-danger">Disqualified</span>
                                </div>
                                <dl class="ml-card2__list">
                                    <div><dt>Reason</dt><dd>{{ $disq->reason ?? '—' }}</dd></div>
                                    <div>
                                        <dt>Notice issued</dt>
                                        <dd>{{ $disq->notice_issued_at ? \Illuminate\Support\Carbon::parse($disq->notice_issued_at)->format('M d, Y') : '—' }}</dd>
                                    </div>
                                    <div><dt>Requirements returned</dt><dd>{{ $disq->requirements_returned ? 'Yes' : 'No' }}</dd></div>
                                </dl>

                                @if ($disq->appeals->count())
                                    <div class="ml-appeal-group">
                                        <div class="ml-appeal-group__title">
                                            <i class="bi bi-megaphone"></i>
                                            {{ $disq->appeals->count() }} {{ Str::plural('appeal', $disq->appeals->count()) }}
                                        </div>
                                        @foreach ($disq->appeals->sortByDesc('filed_at') as $appeal)
                                            <div class="ml-appeal">
                                                <div class="ml-record-list__head">
                                                    <span class="text-muted-soft" style="font-size:.7rem;">
                                                        Filed {{ $appeal->filed_at?->format('M d, Y') ?? '—' }}
                                                    </span>
                                                    @if ($appeal->result === 'approved')
                                                        <span class="admin-badge admin-badge-success">Approved</span>
                                                    @elseif ($appeal->result === 'rejected')
                                                        <span class="admin-badge admin-badge-danger">Rejected</span>
                                                    @else
                                                        <span class="admin-badge admin-badge-pending">Under review</span>
                                                    @endif
                                                </div>
                                                @if ($appeal->reconsideration_notes)
                                                    <p class="ml-appeal__notes">{{ $appeal->reconsideration_notes }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="ml-card2__note mb-0">No appeal filed for this disqualification.</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="ml-card2__empty"><i class="bi bi-check-circle"></i> No disqualification on record</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Activity Timeline (audit trail) --}}
    <div class="ml-section-block">
        <button class="ml-section-block__toggle" type="button" data-bs-target="#drawer-activity" aria-expanded="false">
            <span class="ml-section-block__icon ml-section-block__icon--navy"><i class="bi bi-clock-history"></i></span>
            <span class="ml-section-block__label">Activity Timeline</span>
            <span class="ml-section-block__status">
                @if ($logs->count())
                    <span class="admin-badge admin-badge-muted">{{ $logs->count() }}</span>
                @else
                    <span class="admin-badge admin-badge-muted">None</span>
                @endif
            </span>
            <i class="bi bi-chevron-down ml-section-block__chevron"></i>
        </button>
        <div class="ml-drawer-collapse" id="drawer-activity">
            <div class="ml-section-block__content">
                @if ($logs->count())
                    <ol class="ml-timeline">
                        @foreach ($logs->take(25) as $log)
                            <li class="ml-timeline__item">
                                <span class="ml-timeline__dot"></span>
                                <div class="ml-timeline__body">
                                    <p class="ml-timeline__text">{{ $log->description }}</p>
                                    <div class="ml-timeline__meta">
                                        <span><i class="bi bi-person"></i> {{ $log->user?->name ?? 'System' }}</span>
                                        <span><i class="bi bi-calendar3"></i> {{ $log->created_at?->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                    @if ($logs->count() > 25)
                        <p class="ml-card2__note mb-0">
                            Showing the 25 most recent of {{ $logs->count() }} entries.
                            <a href="{{ route('admin.audit-log.index') }}">Open the full audit log</a>.
                        </p>
                    @endif
                @else
                    <p class="ml-card2__empty"><i class="bi bi-hourglass"></i> No recorded activity for this applicant</p>
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