@extends('layouts.app')
@section('title', $applicant->first_name . ' ' . $applicant->last_name)

@section('content')

@php
    $statusLabels = [
        'submitted' => ['label' => 'Application submitted', 'bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-700 dark:text-amber-300'],
        'pending_mswdo' => ['label' => 'Pending MSWDO assessment', 'bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-700 dark:text-amber-300'],
        'exam_scheduled' => ['label' => 'Exam scheduled', 'bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-700 dark:text-blue-300'],
        'exam_passed' => ['label' => 'Exam passed', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
        'oriented' => ['label' => 'Orientation complete', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
        'compliance_met' => ['label' => 'Compliance met', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
        'paid_out' => ['label' => 'Scholarship released', 'bg' => 'bg-emerald-100 dark:bg-emerald-900/40', 'text' => 'text-emerald-700 dark:text-emerald-300'],
    ];
    $currentStatus = $statusLabels[$applicant->status] ?? ['label' => ucfirst(str_replace('_', ' ', $applicant->status)), 'bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300'];
    $isDisqualified = str_starts_with($applicant->status, 'disqualified');
@endphp

<a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-4">
    <i class="ti ti-arrow-left"></i> Back to all applicants
</a>

<!-- Header card -->
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-5">
    <div class="flex justify-between items-start flex-wrap gap-3">
        <div class="flex gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-xl font-medium text-blue-700 dark:text-blue-300">
                {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
            </div>
            <div>
                <p class="font-medium text-lg mb-0.5 text-gray-900 dark:text-gray-100">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
                @if ($applicant->course || $applicant->year_level)
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
        {{ $applicant->course }}{{ $applicant->course && $applicant->year_level ? ', ' : '' }}{{ $applicant->year_level }}
    </p>
@endif
                @if ($applicant->school_name)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $applicant->school_name }}</p>
                @endif
            </div>
        </div>
        <span class="{{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} text-xs font-medium px-3 py-1.5 rounded-full">
            {{ $currentStatus['label'] }}
        </span>
    </div>

    <div class="border-t border-gray-100 dark:border-gray-700 mt-4 pt-3.5 grid grid-cols-1 md:grid-cols-4 gap-3.5 text-sm">
    <div>
        <span class="text-gray-500 dark:text-gray-400">Email</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ $applicant->user->email ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Contact number</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ $applicant->contact_number ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Date of birth</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ optional($applicant->date_of_birth)->format('M d, Y') ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Sex</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ $applicant->sex ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Address</span><br>
        <span class="text-gray-800 dark:text-gray-200">
            @php $addressParts = array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay]); @endphp
            {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
        </span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Application type</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ ucfirst($applicant->program_type) }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Father's name</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ $applicant->father_name ?? 'N/A' }}</span>
    </div>
    <div>
        <span class="text-gray-500 dark:text-gray-400">Mother's maiden name</span><br>
        <span class="text-gray-800 dark:text-gray-200">{{ $applicant->mother_maiden_name ?? 'N/A' }}</span>
    </div>
</div>
</div>

<!-- Requirements -->
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-5">
    <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-3">Requirements checklist</p>
   @foreach ($applicant->requirements as $req)
    @php $isPersonalSubmission = str_contains(strtolower($req->requirement->name), 'brown envelope'); @endphp
    <div class="flex items-center justify-between py-2.5 border-t border-gray-100 dark:border-gray-700 first:border-t-0">
        <span class="text-sm text-gray-800 dark:text-gray-200">{{ $req->requirement->name }}</span>
        @if ($isPersonalSubmission)
            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">
                Submitted in person
            </span>
        @elseif ($req->is_submitted)
            <div class="flex items-center gap-2.5">
                @if ($req->file_path)
                    <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank"
                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View file</a>
                @endif

                @if ($req->approval_status === 'approved')
                    <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-medium px-2.5 py-1 rounded-full">Approved</span>
                @elseif ($req->approval_status === 'rejected')
                    <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 text-xs font-medium px-2.5 py-1 rounded-full">Not approved</span>
                @else
                    <span class="bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-medium px-2.5 py-1 rounded-full">Pending review</span>
                @endif

                <form method="POST" action="{{ route('admin.requirements.approve', $req) }}">
                    @csrf
                    <button type="submit"
                        class="text-xs font-medium px-2.5 py-1 rounded-lg transition {{ $req->approval_status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-green-600 hover:text-white' }}">
                        Approve
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.requirements.reject', $req) }}">
                    @csrf
                    <button type="submit"
                        class="text-xs font-medium px-2.5 py-1 rounded-lg transition {{ $req->approval_status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-red-600 hover:text-white' }}">
                        Reject
                    </button>
                </form>
            </div>
        @else
            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">Not submitted</span>
        @endif
    </div>
@endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <!-- Step 4: Policy verification -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">Policy Verification</p>
        @if ($applicant->verification)
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Already recorded.</p>
            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1 mb-2">
                <p>SPES: {{ $applicant->verification->in_spes ? 'Yes' : 'No' }}</p>
                <p>4Ps: {{ $applicant->verification->in_4ps ? 'Yes' : 'No' }}</p>
                <p>One scholar per family OK: {{ $applicant->verification->one_scholar_per_family_ok ? 'Yes' : 'No' }}</p>
            </div>
        @else
            <form method="POST" action="{{ route('admin.verify-policy', $applicant) }}" class="space-y-2 text-sm">
                @csrf
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="in_spes" value="1"> Currently in SPES
                </label>
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="in_4ps" value="1"> Currently in 4Ps
                </label>
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="one_scholar_per_family_ok" value="1" checked> One-scholar-per-family OK
                </label>
                <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg mt-2">Submit Verification</button>
            </form>
        @endif
    </div>

    <!-- Step 5-6: MSWDO assessment -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">MSWDO Assessment</p>
        @if ($applicant->mswdoAssessment)
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Already recorded.</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">Referral slip: {{ $applicant->mswdoAssessment->referral_slip_no ?? 'N/A' }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">Qualified: {{ $applicant->mswdoAssessment->is_qualified ? 'Yes' : 'No' }}</p>
        @else
            <form method="POST" action="{{ route('admin.mswdo-assess', $applicant) }}" class="space-y-2 text-sm">
                @csrf
                <input type="text" name="referral_slip_no" placeholder="Referral slip no."
                       class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm">
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_qualified" value="1"> Qualified (meets poverty threshold)
                </label>
                <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg mt-2">Submit Assessment</button>
            </form>
        @endif
    </div>

   <!-- Step 8: Exam result -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">Qualifying Exam Result</p>
        @if ($applicant->examResults->count())
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Already recorded.</p>
            @foreach ($applicant->examResults as $result)
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $result->passed ? 'Passed' : 'Failed' }}</p>
            @endforeach
        @else
            <form method="POST" action="{{ route('admin.exam-result', $applicant) }}" class="space-y-2 text-sm">
                @csrf
                <select name="passed" class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm" required>
                    <option value="">-- Select result --</option>
                    <option value="1">Passed</option>
                    <option value="0">Failed</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg mt-2">Submit Result</button>
            </form>
        @endif
    </div>
    <!-- Step 9: Orientation -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">Orientation</p>
        @if ($applicant->orientation && $applicant->orientation->attended)
            <p class="text-sm text-green-700 dark:text-green-300">Marked as attended.</p>
        @else
            <form method="POST" action="{{ route('admin.orientation', $applicant) }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg">Mark Orientation Complete</button>
            </form>
        @endif
    </div>

    <!-- Step 10: Waste compliance -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">Waste Compliance</p>
        @if ($applicant->wasteCompliance->count())
            @foreach ($applicant->wasteCompliance as $wc)
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $wc->semester }}: {{ $wc->kilos_submitted }}kg — {{ $wc->is_compliant ? 'Compliant' : 'Not compliant' }}</p>
            @endforeach
        @endif
        <form method="POST" action="{{ route('admin.waste-compliance', $applicant) }}" class="space-y-2 text-sm mt-2">
            @csrf
            <input type="text" name="semester" placeholder="e.g. 1st Sem 2026-2027"
                   class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm" required>
            <input type="number" step="0.01" name="kilos_submitted" placeholder="Kilos submitted"
                   class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm" required>
            <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg mt-2">Record Compliance</button>
        </form>
    </div>

    <!-- Step 11: Payout -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
        <p class="font-medium text-sm text-gray-900 dark:text-gray-100 mb-1">Payout</p>
        @if ($applicant->payouts->count())
            @foreach ($applicant->payouts as $payout)
                <p class="text-sm text-gray-700 dark:text-gray-300">₱{{ number_format($payout->amount, 2) }} — Ref: {{ $payout->reference_no ?? 'N/A' }}</p>
            @endforeach
        @else
            <form method="POST" action="{{ route('admin.payout', $applicant) }}" class="space-y-2 text-sm">
                @csrf
                <input type="number" step="0.01" name="amount" placeholder="Amount"
                       class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm" required>
                <input type="text" name="reference_no" placeholder="Reference no. (optional)"
                       class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2 text-sm">
                <button type="submit" class="bg-blue-600 text-white text-xs font-medium px-4 py-2 rounded-lg mt-2">Release Payout</button>
            </form>
        @endif
    </div>

</div>

@if ($isDisqualified && $applicant->disqualifications->count())
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5 mt-5">
        <p class="font-medium text-sm text-red-700 dark:text-red-300 mb-2">Disqualification Record</p>
        @foreach ($applicant->disqualifications as $dq)
            <p class="text-sm text-red-700 dark:text-red-300">Stage: {{ $dq->stage }} — Reason: {{ $dq->reason }}</p>
        @endforeach
    </div>
@endif

@endsection