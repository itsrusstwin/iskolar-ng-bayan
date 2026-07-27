@extends('layouts.student')
@section('title', 'My Profile - Iskolar ng Bayan')

@section('content')

@php
    $totalReqs = $applicant->requirements->count();
    $submittedReqs = $applicant->requirements->where('is_submitted', true)->count();
    $progressPercent = $totalReqs > 0 ? round(($submittedReqs / $totalReqs) * 100) : 0;

    $statusLabels = [
        'submitted' => ['label' => 'Application submitted', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
        'pending_mswdo' => ['label' => 'Pending MSWDO assessment', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
        'exam_scheduled' => ['label' => 'Exam scheduled', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
        'exam_passed' => ['label' => 'Exam passed', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'oriented' => ['label' => 'Orientation complete', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'compliance_met' => ['label' => 'Compliance met', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'paid_out' => ['label' => 'Scholarship released', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
    ];
    $currentStatus = $statusLabels[$applicant->status] ?? ['label' => ucfirst(str_replace('_', ' ', $applicant->status)), 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'];

    $initials = strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1));

    $isDisqualified = str_starts_with($applicant->status, 'disqualified');
    $latestDisqualification = $applicant->disqualifications->last();
    $existingAppeal = $latestDisqualification ? $latestDisqualification->appeals->last() : null;
@endphp

<!-- Profile card -->
<div id="status" class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
    <div class="flex justify-between items-start">
        <div class="flex gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-xl font-medium text-blue-700">
                {{ $initials }}
            </div>
            <div>
    <p class="font-medium text-lg mb-0.5">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
    <p class="text-sm text-gray-500 mb-1">
        Student ID: {{ $applicant->school_id ?? 'N/A' }}
        @if ($applicant->course_year)
            &nbsp;•&nbsp; {{ $applicant->course_year }}
        @endif
    </p>
    @if ($applicant->school_name)
        <p class="text-sm text-gray-500">{{ $applicant->school_name }}</p>
    @endif
</div>
        </div>
        <span class="{{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} text-xs font-medium px-3 py-1.5 rounded-full">
            {{ $currentStatus['label'] }}
        </span>
    </div>

    <div class="border-t border-gray-100 mt-4 pt-3.5 grid grid-cols-1 md:grid-cols-3 gap-3.5 text-sm">
    <div>
        <span class="text-gray-500">Email</span><br>
        <span class="text-gray-800">{{ $applicant->user->email ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500">Contact number</span><br>
        <span class="text-gray-800">{{ $applicant->contact_number ?? 'N/A' }}</span>
    </div>
    <div>
    <span class="text-gray-500">Address</span><br>
    <span class="text-gray-800">
        @php
            $addressParts = array_filter([
                $applicant->province,
                $applicant->city_municipality,
                $applicant->barangay,
            ]);
        @endphp
        {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
    </span>
</div>
</div>

<div class="mt-4 pt-3.5 border-t border-gray-100">
    <a href="{{ route('profile.edit') }}" class="text-blue-600 text-sm font-medium hover:underline">
        Edit Profile →
    </a>
</div>
</div>

<!-- Assessment & Exam Records -->
@if ($applicant->mswdoAssessment || $applicant->examResults->count())
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-5">
        <p class="font-medium text-sm mb-3">Assessment &amp; Exam Records</p>

        @if ($applicant->mswdoAssessment)
            <div class="flex items-center justify-between py-2.5 border-t border-gray-100">
                <div>
                    <p class="text-sm mb-0">MSWDO Social Case Study Report</p>
                    <p class="text-xs text-gray-500 mb-0">
                        {{ $applicant->mswdoAssessment->is_qualified ? 'Qualified' : 'Not qualified' }}
                        @if ($applicant->mswdoAssessment->assessed_at)
                            — assessed {{ $applicant->mswdoAssessment->assessed_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                @if ($applicant->mswdoAssessment->social_case_study_report_path)
                    <a href="{{ asset('storage/' . $applicant->mswdoAssessment->social_case_study_report_path) }}" target="_blank" class="text-blue-600 text-sm font-medium hover:underline">View Report</a>
                @else
                    <span class="text-xs text-gray-500">No file uploaded</span>
                @endif
            </div>
        @endif

        @foreach ($applicant->examResults as $result)
            <div class="flex items-center justify-between py-2.5 border-t border-gray-100">
                <div>
                    <p class="text-sm mb-0">Qualifying Exam</p>
                    <p class="text-xs text-gray-500 mb-0">
                        {{ $result->passed ? 'Passed' : 'Failed' }}
                        @if ($result->posted_at)
                            — posted {{ $result->posted_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                @if ($result->file_path)
                    <a href="{{ asset('storage/' . $result->file_path) }}" target="_blank" class="text-blue-600 text-sm font-medium hover:underline">View Exam File</a>
                @else
                    <span class="text-xs text-gray-500">No file uploaded</span>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Disqualification / Appeal -->
@if ($isDisqualified && $latestDisqualification)
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-5">
        <p class="font-medium text-red-700 mb-1">Application Disqualified</p>
        <p class="text-sm text-red-600 mb-3">{{ $latestDisqualification->reason }}</p>

        @if ($existingAppeal)
            <div class="text-sm bg-white border border-red-100 rounded-lg p-3">
                <p class="text-gray-500 mb-1">Your appeal, filed {{ $existingAppeal->filed_at?->format('M d, Y') }}:</p>
                <p class="text-gray-800 mb-2">{{ $existingAppeal->reconsideration_notes }}</p>
                @if ($existingAppeal->result === 'pending')
                    <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-full">Under review</span>
                @elseif ($existingAppeal->result === 'approved')
                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Approved — reinstated</span>
                @else
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">Denied</span>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('appeals.store') }}" class="mt-2">
                @csrf
                <input type="hidden" name="disqualification_id" value="{{ $latestDisqualification->id }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for reconsideration</label>
                <textarea name="reconsideration_notes" rows="4" class="w-full border rounded-lg p-2 text-sm mb-3" required placeholder="Explain why you believe this decision should be reconsidered...">{{ old('reconsideration_notes') }}</textarea>
                <button type="submit" class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">File an Appeal</button>
            </form>
        @endif
    </div>
@endif

<!-- Requirements checklist -->
<div id="requirements" class="bg-white border border-gray-200 rounded-xl p-5">
    <div class="flex justify-between items-center mb-1">
        <p class="font-medium text-sm">Requirements checklist</p>
        <span class="text-xs text-gray-500">{{ $submittedReqs }} of {{ $totalReqs }} submitted</span>
    </div>
    <div class="h-1.5 bg-gray-100 rounded-full my-2.5 mb-4 overflow-hidden">
        <div class="h-full bg-blue-600" style="width: {{ $progressPercent }}%;"></div>
    </div>

    @foreach ($applicant->requirements as $req)
        <div class="flex items-center justify-between py-3 border-t border-gray-100">
            <div class="flex items-center gap-2.5">
                <i class="ti ti-file-text text-lg text-gray-500"></i>
                <div>
                    <p class="text-sm mb-0">{{ $req->requirement->name }}</p>
                    <p class="text-xs text-gray-500 mb-0">PDF or image, max 5MB</p>
                </div>
            </div>
            @if ($req->is_submitted)
                <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Uploaded</span>
            @else
                <button class="bg-blue-600 text-white text-xs font-medium px-3.5 py-1.5 rounded-lg flex items-center gap-1.5">
                    <i class="ti ti-upload text-sm"></i> Upload
                </button>
            @endif
        </div>
    @endforeach

    <div class="mt-4 pt-3.5 border-t border-gray-100 flex justify-end">
        <button class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg">
            Submit application
        </button>
    </div>
</div>

@endsection