@extends('layouts.app')
@section('title', $applicant->first_name . ' ' . $applicant->last_name)

@section('content')

@php
    $statusLabels = [
        'submitted' => 'Application submitted',
        'pending_mswdo' => 'Pending MSWDO assessment',
        'exam_scheduled' => 'Exam scheduled',
        'exam_passed' => 'Exam passed',
        'oriented' => 'Orientation complete',
        'compliance_met' => 'Compliance met',
        'paid_out' => 'Scholarship released',
    ];
    $currentStatusLabel = $statusLabels[$applicant->status] ?? ucfirst(str_replace('_', ' ', $applicant->status));
    $isDisqualified = str_starts_with($applicant->status, 'disqualified');
@endphp

<a href="{{ route('admin.dashboard') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to all applicants
</a>

<!-- Header card -->
<div class="card-flat p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-4 flex-shrink-0"
                 style="width:64px;height:64px; background: var(--surface-100); color: var(--ink-800);">
                {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
            </div>
            <div>
                <p class="fw-bold fs-5 mb-0">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
                @if ($applicant->course || $applicant->year_level)
                    <p class="small text-muted-soft mb-0">{{ $applicant->course }}{{ $applicant->course && $applicant->year_level ? ', ' : '' }}{{ $applicant->year_level }}</p>
                @endif
                @if ($applicant->school_name)
                    <p class="small text-muted-soft mb-0">{{ $applicant->school_name }}</p>
                @endif
            </div>
        </div>
        <span class="{{ $isDisqualified ? 'badge bg-danger-subtle text-danger-emphasis px-3 py-2' : 'badge-soft-gold' }}">
            {{ $isDisqualified ? $currentStatusLabel : $currentStatusLabel }}
        </span>
    </div>

    <div class="row g-3 mt-2 pt-3 border-top">
        <div class="col-md-3 col-6">
            <p class="small text-muted-soft mb-0">Email</p>
            <p class="mb-0">{{ $applicant->user->email ?? 'N/A' }}</p>
        </div>
        <div class="col-md-3 col-6">
            <p class="small text-muted-soft mb-0">Contact number</p>
            <p class="mb-0">{{ $applicant->contact_number ?? 'N/A' }}</p>
        </div>
        <div class="col-md-3 col-6">
            <p class="small text-muted-soft mb-0">Date of birth</p>
            <p class="mb-0">{{ optional($applicant->date_of_birth)->format('M d, Y') ?? 'N/A' }}</p>
        </div>
        <div class="col-md-3 col-6">
            <p class="small text-muted-soft mb-0">Sex</p>
            <p class="mb-0">{{ $applicant->sex ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4 col-6">
            <p class="small text-muted-soft mb-0">Address</p>
            <p class="mb-0">
                @php $addressParts = array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay]); @endphp
                {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
            </p>
        </div>
        <div class="col-md-4 col-6">
            <p class="small text-muted-soft mb-0">Application type</p>
            <p class="mb-0">{{ ucfirst($applicant->program_type) }}</p>
        </div>
        <div class="col-md-2 col-6">
            <p class="small text-muted-soft mb-0">Father's name</p>
            <p class="mb-0">{{ $applicant->father_name ?? 'N/A' }}</p>
        </div>
        <div class="col-md-2 col-6">
            <p class="small text-muted-soft mb-0">Mother's maiden name</p>
            <p class="mb-0">{{ $applicant->mother_maiden_name ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Requirements -->
<div class="card-flat p-4 mb-4">
    <p class="fw-bold mb-3">Requirements checklist</p>
    @foreach ($applicant->requirements as $req)
        @php $isPersonalSubmission = str_contains(strtolower($req->requirement->name), 'brown envelope'); @endphp
        <div class="d-flex align-items-center justify-content-between py-3 border-top flex-wrap gap-2">
            <span>{{ $req->requirement->name }}</span>
            @if ($isPersonalSubmission)
                <span class="badge-soft-navy">Submitted in person</span>
            @elseif ($req->is_submitted)
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    @if ($req->file_path)
                        <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="small fw-semibold" style="color: var(--ink-700);">View file</a>
                    @endif

                    @if ($req->approval_status === 'approved')
                        <span class="badge-soft-gold">Approved</span>
                    @elseif ($req->approval_status === 'rejected')
                        <span class="badge bg-danger-subtle text-danger-emphasis">Not approved</span>
                    @else
                        <span class="badge-soft-navy">Pending review</span>
                    @endif

                    <form method="POST" action="{{ route('admin.requirements.approve', $req) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $req->approval_status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.requirements.reject', $req) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $req->approval_status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Reject</button>
                    </form>
                </div>
            @else
                <span class="badge bg-secondary-subtle text-secondary-emphasis">Not submitted</span>
            @endif
        </div>
    @endforeach
</div>

<div class="row g-4">

    <!-- Step 4: Policy verification -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">Policy Verification</p>
            @if ($applicant->verification)
                <p class="small text-muted-soft mb-3">Already recorded.</p>
                <p class="small mb-1">SPES: {{ $applicant->verification->in_spes ? 'Yes' : 'No' }}</p>
                <p class="small mb-1">4Ps: {{ $applicant->verification->in_4ps ? 'Yes' : 'No' }}</p>
                <p class="small mb-0">One scholar per family OK: {{ $applicant->verification->one_scholar_per_family_ok ? 'Yes' : 'No' }}</p>
            @else
                <form method="POST" action="{{ route('admin.verify-policy', $applicant) }}">
                    @csrf
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="in_spes" value="1" id="inSpes">
                        <label class="form-check-label small" for="inSpes">Currently in SPES</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="in_4ps" value="1" id="in4ps">
                        <label class="form-check-label small" for="in4ps">Currently in 4Ps</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="one_scholar_per_family_ok" value="1" checked id="oneScholar">
                        <label class="form-check-label small" for="oneScholar">One-scholar-per-family OK</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted-soft mb-1">
                            Reason if disqualified <span class="fst-italic">(optional — only used if the boxes above trigger a disqualification)</span>
                        </label>
                        <textarea name="disqualification_reason" rows="2" class="form-control form-control-sm" placeholder="e.g. Household already has an active 4Ps beneficiary on file"></textarea>
                    </div>
                    <button type="submit" class="btn btn-navy btn-sm px-3">Submit Verification</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Step 5-6: MSWDO assessment -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">MSWDO Assessment</p>
            @if ($applicant->mswdoAssessment)
                <p class="small text-muted-soft mb-3">Already recorded.</p>
                <p class="small mb-1">Referral slip: {{ $applicant->mswdoAssessment->referral_slip_no ?? 'N/A' }}</p>
                <p class="small mb-2">Qualified: {{ $applicant->mswdoAssessment->is_qualified ? 'Yes' : 'No' }}</p>
                @if ($applicant->mswdoAssessment->social_case_study_report_path)
                    <a href="{{ asset('storage/' . $applicant->mswdoAssessment->social_case_study_report_path) }}" target="_blank" class="small fw-semibold" style="color: var(--ink-700);">
                        <i class="bi bi-file-earmark-pdf"></i> View Social Case Study Report
                    </a>
                @else
                    <span class="small text-muted-soft">No report was uploaded.</span>
                @endif
            @else
                <form method="POST" action="{{ route('admin.mswdo-assess', $applicant) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="referral_slip_no" placeholder="Referral slip no." class="form-control form-control-sm mb-2">
                    <div class="mb-3">
                        <label class="form-label small text-muted-soft mb-1">Social Case Study Report (PDF, optional)</label>
                        <input type="file" name="social_case_study_report" accept=".pdf" class="form-control form-control-sm">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_qualified" value="1" id="isQualified">
                        <label class="form-check-label small" for="isQualified">Qualified (meets poverty threshold)</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted-soft mb-1">
                            Reason if disqualified <span class="fst-italic">(optional — only used if left unchecked above)</span>
                        </label>
                        <textarea name="disqualification_reason" rows="2" class="form-control form-control-sm" placeholder="e.g. Household income exceeds the poverty threshold set by MSWDO"></textarea>
                    </div>
                    <button type="submit" class="btn btn-navy btn-sm px-3">Submit Assessment</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Step 8: Exam result -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">Qualifying Exam Result</p>
            @if ($applicant->examResults->count())
                <p class="small text-muted-soft mb-3">Already recorded.</p>
                @foreach ($applicant->examResults as $result)
                    <p class="small mb-1">{{ $result->passed ? 'Passed' : 'Failed' }}</p>
                    @if ($result->file_path)
                        <a href="{{ asset('storage/' . $result->file_path) }}" target="_blank" class="small fw-semibold d-inline-block mb-2" style="color: var(--ink-700);">
                            <i class="bi bi-file-earmark-pdf"></i> View Exam File
                        </a>
                    @else
                        <p class="small text-muted-soft mb-2">No exam file was uploaded.</p>
                    @endif
                @endforeach
            @else
                <form method="POST" action="{{ route('admin.exam-result', $applicant) }}" enctype="multipart/form-data">
                    @csrf
                    <select name="passed" class="form-select form-select-sm mb-3" required>
                        <option value="">-- Select result --</option>
                        <option value="1">Passed</option>
                        <option value="0">Failed</option>
                    </select>
                    <div class="mb-3">
                        <label class="form-label small text-muted-soft mb-1">Exam File (PDF, optional)</label>
                        <input type="file" name="exam_file" accept=".pdf" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted-soft mb-1">
                            Reason if disqualified <span class="fst-italic">(optional — only used if "Failed" is selected)</span>
                        </label>
                        <textarea name="disqualification_reason" rows="2" class="form-control form-control-sm" placeholder="e.g. Scored below the passing mark on the written portion"></textarea>
                    </div>
                    <button type="submit" class="btn btn-navy btn-sm px-3">Submit Result</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Step 9: Orientation -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">Orientation</p>
            @if ($applicant->orientation && $applicant->orientation->attended)
                <p class="small text-success mb-0">Marked as attended.</p>
            @else
                <form method="POST" action="{{ route('admin.orientation', $applicant) }}">
                    @csrf
                    <button type="submit" class="btn btn-navy btn-sm px-3">Mark Orientation Complete</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Step 10: Waste compliance -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">Waste Compliance</p>
            @if ($applicant->wasteCompliance->count())
                @foreach ($applicant->wasteCompliance as $wc)
                    <p class="small mb-1">{{ $wc->semester }}: {{ $wc->kilos_submitted }}kg — {{ $wc->is_compliant ? 'Compliant' : 'Not compliant' }}</p>
                @endforeach
            @endif
            <form method="POST" action="{{ route('admin.waste-compliance', $applicant) }}" class="mt-2">
                @csrf
                <input type="text" name="semester" placeholder="e.g. 1st Sem 2026-2027" class="form-control form-control-sm mb-2" required>
                <input type="number" step="0.01" name="kilos_submitted" placeholder="Kilos submitted" class="form-control form-control-sm mb-3" required>
                <button type="submit" class="btn btn-navy btn-sm px-3">Record Compliance</button>
            </form>
        </div>
    </div>

    <!-- Step 11: Payout -->
    <div class="col-lg-6">
        <div class="card-flat p-4 h-100">
            <p class="fw-bold mb-1">Payout</p>
            @if ($applicant->payouts->count())
                @foreach ($applicant->payouts as $payout)
                    <p class="small mb-1">₱{{ number_format($payout->amount, 2) }} — Ref: {{ $payout->reference_no ?? 'N/A' }}</p>
                @endforeach
            @else
                <form method="POST" action="{{ route('admin.payout', $applicant) }}">
                    @csrf
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="form-control form-control-sm mb-2" required>
                    <input type="text" name="reference_no" placeholder="Reference no. (optional)" class="form-control form-control-sm mb-3">
                    <button type="submit" class="btn btn-navy btn-sm px-3">Release Payout</button>
                </form>
            @endif
        </div>
    </div>

</div>

@if ($isDisqualified && $applicant->disqualifications->count())
    <div class="alert-brand-danger p-4 mt-4">
        <p class="fw-bold small mb-2">Disqualification Record</p>
        @foreach ($applicant->disqualifications as $dq)
            <p class="small mb-2">Stage: {{ $dq->stage }} — Reason: {{ $dq->reason }}</p>

            @forelse ($dq->appeals as $appeal)
                <div class="card-flat p-3 mb-2">
                    <p class="small text-muted-soft mb-1">
                        Appeal filed {{ $appeal->filed_at?->format('M d, Y g:ia') }}
                    </p>
                    <p class="small mb-2">{{ $appeal->reconsideration_notes }}</p>

                    @if ($appeal->result === 'pending')
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.appeals.approve', $appeal) }}"
                                  onsubmit="return confirm('Approve this appeal and reinstate the applicant?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">Approve &amp; Reinstate</button>
                            </form>
                            <form method="POST" action="{{ route('admin.appeals.reject', $appeal) }}"
                                  onsubmit="return confirm('Deny this appeal? The disqualification will stand.');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Deny Appeal</button>
                            </form>
                        </div>
                    @elseif ($appeal->result === 'approved')
                        <span class="badge bg-success">Approved — applicant reinstated</span>
                    @else
                        <span class="badge bg-danger">Denied</span>
                    @endif
                </div>
            @empty
                <p class="small text-muted-soft mb-2">No appeal has been filed for this disqualification.</p>
            @endforelse
        @endforeach
    </div>
@endif

<!-- Activity history -->
@if ($applicant->auditLogs->count())
    <div class="card-flat p-4 mt-4">
        <p class="fw-bold mb-3">History</p>
        @foreach ($applicant->auditLogs as $log)
            <div class="d-flex align-items-start justify-content-between gap-3 py-2 border-top flex-wrap">
                <div>
                    <p class="small mb-0">
                        <span class="fw-semibold">{{ $log->user->name ?? 'Unknown / system' }}</span>
                        — {{ $log->description }}
                    </p>
                </div>
                <span class="small text-muted-soft flex-shrink-0">{{ $log->created_at->format('M d, Y g:ia') }}</span>
            </div>
        @endforeach
    </div>
@endif

@endsection