@extends('layouts.student')
@section('title', 'My Profile - Iskolar ng Bayan')

@section('content')

@php
    $isDisqualified = str_starts_with($applicant->status, 'disqualified');
    $initials = strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1));
@endphp

<!-- Profile header -->
<div class="card-elevated p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-4"
                 style="width:64px;height:64px;background: var(--surface-100); color: var(--ink-800); flex-shrink:0;">
                {{ $initials }}
            </div>
            <div>
                <p class="small text-muted-soft mb-0 fw-semibold" style="letter-spacing:.04em;">Application ID: {{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="fw-bold fs-5 mb-0">{{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name . ' ' : '' }}{{ $applicant->last_name }}</p>
                <p class="small text-muted-soft mb-0">
                    Student ID: {{ $applicant->school_id ?? 'N/A' }}
                    @if ($applicant->course_year)
                        &nbsp;•&nbsp; {{ $applicant->course_year }}
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-navy d-inline-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Edit Profile
        </a>
    </div>
</div>

<div class="row g-4">

    <!-- Personal Information -->
    <div class="col-lg-6">
        <div class="card-elevated p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="fw-bold mb-0">Personal Information</p>
                <a href="{{ route('profile.edit') }}" class="small fw-semibold link-brand">Edit →</a>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Last Name</p>
                    <p class="mb-0">{{ $applicant->last_name ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">First Name</p>
                    <p class="mb-0">{{ $applicant->first_name ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Middle Name</p>
                    <p class="mb-0">{{ $applicant->middle_name ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Contact Number</p>
                    <p class="mb-0">{{ $applicant->contact_number ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Date of Birth</p>
                    <p class="mb-0">{{ $applicant->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Sex</p>
                    <p class="mb-0">{{ $applicant->sex ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Application Type</p>
                    <p class="mb-0">{{ ucfirst($applicant->program_type) }}</p>
                </div>
                <div class="col-12">
                    <p class="small text-muted-soft mb-0">Address</p>
                    <p class="mb-0">
                        {{ implode(', ', array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay])) ?: 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Family & Educational -->
    <div class="col-lg-6">
        <div class="card-elevated p-4 h-100">
            <p class="fw-bold mb-3">Family &amp; Educational Background</p>
            <div class="row g-3">
                <div class="col-12">
                    <p class="small text-muted-soft mb-0">Father's Full Name</p>
                    <p class="mb-0">{{ $applicant->father_name ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="small text-muted-soft mb-0">Mother's Maiden Name</p>
                    <p class="mb-0">{{ $applicant->mother_maiden_name ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="small text-muted-soft mb-0">School Enrolled</p>
                    <p class="mb-0">{{ $applicant->school_name ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Year Level</p>
                    <p class="mb-0">{{ $applicant->year_level ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="small text-muted-soft mb-0">Full Course</p>
                    <p class="mb-0">{{ $applicant->course ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="small text-muted-soft mb-0">Email Address</p>
                    <p class="mb-0">{{ $applicant->user->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="mt-4 text-end">
    <a href="{{ route('profile.edit') }}" class="btn btn-navy d-inline-flex align-items-center gap-2">
        <i class="bi bi-pencil"></i> Edit Profile
    </a>
</div>

@endsection
