@extends('layouts.student')
@section('title', 'My Profile - Iskolar ng Bayan')

@section('content')

@php
    $totalReqs = $applicant->requirements->count();
    $submittedReqs = $applicant->requirements->where('is_submitted', true)->count();
    $progressPercent = $totalReqs > 0 ? round(($submittedReqs / $totalReqs) * 100) : 0;

    $statusLabels = [
        'submitted' => ['label' => 'Application submitted', 'class' => 'admin-badge-warning'],
        'pending_mswdo' => ['label' => 'Pending MSWDO assessment', 'class' => 'admin-badge-warning'],
        'exam_scheduled' => ['label' => 'Exam scheduled', 'class' => 'admin-badge-released'],
        'exam_passed' => ['label' => 'Exam passed', 'class' => 'admin-badge-success'],
        'oriented' => ['label' => 'Orientation complete', 'class' => 'admin-badge-success'],
        'compliance_met' => ['label' => 'Compliance met', 'class' => 'admin-badge-success'],
        'paid_out' => ['label' => 'Scholarship released', 'class' => 'admin-badge-success'],
    ];
    $currentStatus = $statusLabels[$applicant->status] ?? ['label' => ucfirst(str_replace('_', ' ', $applicant->status)), 'class' => 'admin-badge-muted'];

    $initials = strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1));

    $isDisqualified = str_starts_with($applicant->status, 'disqualified');
    $latestDisqualification = $applicant->disqualifications->last();
    $existingAppeal = $latestDisqualification ? $latestDisqualification->appeals->last() : null;
@endphp

<!-- Profile card -->
<div id="status" class="card-elevated p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-4"
                 style="width:64px;height:64px;background: var(--surface-100); color: var(--ink-800); flex-shrink:0;">
                {{ $initials }}
            </div>
            <div>
                <p class="small text-muted-soft mb-0 fw-semibold" style="letter-spacing:.04em;">Application ID: {{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="fw-bold fs-5 mb-0">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
                <p class="small text-muted-soft mb-1">
                    Student ID: {{ $applicant->school_id ?? 'N/A' }}
                    @if ($applicant->course_year)
                        &nbsp;•&nbsp; {{ $applicant->course_year }}
                    @endif
                </p>
                @if ($applicant->school_name)
                    <p class="small text-muted-soft mb-0">{{ $applicant->school_name }}</p>
                @endif
            </div>
        </div>
        <span class="{{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
    </div>

    <div class="row g-3 mt-3 pt-3 border-top">
        <div class="col-md-4">
            <p class="small text-muted-soft mb-0">Email</p>
            <p class="mb-0">{{ $applicant->user->email ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4">
            <p class="small text-muted-soft mb-0">Contact number</p>
            <p class="mb-0">{{ $applicant->contact_number ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4">
            <p class="small text-muted-soft mb-0">Address</p>
            <p class="mb-0">
                @php
                    $addressParts = array_filter([
                        $applicant->province,
                        $applicant->city_municipality,
                        $applicant->barangay,
                    ]);
                @endphp
                {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
            </p>
        </div>
    </div>

    <div class="mt-3 pt-3 border-top">
        <a href="{{ route('profile.edit') }}" class="link-brand small">Edit Profile →</a>
    </div>
</div>

@if ($applicant->exam_scheduled_at || $applicant->orientation_scheduled_at)
    <!-- Upcoming schedules -->
    <div class="card-elevated p-4 mb-4">
        <p class="fw-bold mb-3">Upcoming Schedules</p>

        @if ($applicant->exam_scheduled_at)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event fs-5 text-muted-soft"></i>
                    <div>
                        <p class="mb-0 fw-semibold small">Qualifying Exam</p>
                        <p class="small text-muted-soft mb-0">{{ $applicant->exam_scheduled_at->format('F j, Y \a\t g:ia') }}</p>
                    </div>
                </div>
                <span class="admin-badge-released">Scheduled</span>
            </div>
        @endif

        @if ($applicant->orientation_scheduled_at)
            <div class="d-flex align-items-center justify-content-between py-2 flex-wrap gap-2 {{ $applicant->exam_scheduled_at ? 'pt-3' : '' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard fs-5 text-muted-soft"></i>
                    <div>
                        <p class="mb-0 fw-semibold small">Orientation</p>
                        <p class="small text-muted-soft mb-0">{{ $applicant->orientation_scheduled_at->format('F j, Y \a\t g:ia') }}</p>
                    </div>
                </div>
                <span class="admin-badge-released">Scheduled</span>
            </div>
        @endif
    </div>
@endif

<!-- Assessment & Exam Records -->
@if ($applicant->mswdoAssessment || $applicant->examResults->count())
    <div class="card-elevated p-4 mb-4">
        <p class="fw-bold mb-3">Assessment &amp; Exam Records</p>

        @if ($applicant->mswdoAssessment)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom flex-wrap gap-2">
                <div>
                    <p class="mb-0 fw-semibold small">MSWDO Social Case Study Report</p>
                    <p class="small text-muted-soft mb-0">
                        {{ $applicant->mswdoAssessment->is_qualified ? 'Qualified' : 'Not qualified' }}
                        @if ($applicant->mswdoAssessment->assessed_at)
                            — assessed {{ $applicant->mswdoAssessment->assessed_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                @if ($applicant->mswdoAssessment->social_case_study_report_path)
                    <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $applicant->mswdoAssessment->social_case_study_report_path) }}', 'Social Case Study Report')" class="link-brand small">View Report</a>
                @else
                    <span class="small text-muted-soft">No file uploaded</span>
                @endif
            </div>
        @endif

        @foreach ($applicant->examResults as $result)
            <div class="d-flex align-items-center justify-content-between py-2 flex-wrap gap-2 {{ $applicant->mswdoAssessment ? 'pt-3' : '' }} {{ !$loop->last ? 'border-bottom' : '' }}">
                <div>
                    <p class="mb-0 fw-semibold small">Qualifying Exam</p>
                    <p class="small text-muted-soft mb-0">
                        {{ $result->passed ? 'Passed' : 'Failed' }}
                        @if ($result->posted_at)
                            — posted {{ $result->posted_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                @if ($result->file_path)
                    <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $result->file_path) }}', 'Exam File')" class="link-brand small">View Exam File</a>
                @else
                    <span class="small text-muted-soft">No file uploaded</span>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Disqualification / Appeal -->
@if ($isDisqualified && $latestDisqualification)
    <div class="alert-brand-danger p-4 mb-4">
        <p class="fw-bold mb-1">Application Disqualified</p>
        <p class="small mb-3">{{ $latestDisqualification->reason }}</p>

        @if ($existingAppeal)
            <div class="p-3 rounded-md surface-inset">
                <p class="small text-muted-soft mb-1">Your appeal, filed {{ $existingAppeal->filed_at?->format('M d, Y') }}:</p>
                <p class="small mb-2">{{ $existingAppeal->reconsideration_notes }}</p>
                @if ($existingAppeal->result === 'pending')
                    <span class="admin-badge-warning">Under review</span>
                @elseif ($existingAppeal->result === 'approved')
                    <span class="admin-badge-success">Approved — reinstated</span>
                @else
                    <span class="admin-badge-danger">Denied</span>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('appeals.store') }}" class="mt-2">
                @csrf
                <input type="hidden" name="disqualification_id" value="{{ $latestDisqualification->id }}">
                <label class="form-label small fw-semibold">Reason for reconsideration</label>
                <textarea name="reconsideration_notes" rows="4" class="form-control mb-3" required placeholder="Explain why you believe this decision should be reconsidered...">{{ old('reconsideration_notes') }}</textarea>
                <button type="submit" class="btn btn-navy btn-sm px-3">File an Appeal</button>
            </form>
        @endif
    </div>
@endif

<!-- Requirements checklist -->
<div id="requirements" class="card-elevated p-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <p class="fw-bold mb-0">Requirements checklist</p>
        <span class="small text-muted-soft">{{ $submittedReqs }} of {{ $totalReqs }} submitted</span>
    </div>
    <div class="progress mb-4" style="height: 6px;">
        <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%; background: var(--ink-700);"></div>
    </div>

    @foreach ($applicant->requirements as $req)
        <div class="d-flex align-items-center justify-content-between py-3 border-top flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-text fs-5 text-muted-soft"></i>
                <div>
                    <p class="mb-0">{{ $req->requirement->name }}</p>
                    <p class="small text-muted-soft mb-0">PDF or image, max 5MB</p>
                </div>
            </div>
            @if ($req->is_submitted)
                <span class="admin-badge-success">Uploaded</span>
            @else
                <button class="btn btn-navy btn-sm"><i class="bi bi-upload me-1"></i> Upload</button>
            @endif
        </div>
    @endforeach

    <div class="mt-4 pt-3 border-top text-end">
        <button class="btn btn-navy">Submit application</button>
    </div>
</div>

@endsection