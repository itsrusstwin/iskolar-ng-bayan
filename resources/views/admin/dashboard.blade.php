@extends('layouts.app')
@section('title', 'Applicants Overview')

@section('content')

@php
    $allApplicants = $applicants->flatten();
    $total = $allApplicants->count();
    $submittedCount = $allApplicants->whereIn('status', ['submitted', 'pending_mswdo', 'exam_scheduled'])->count();
    $passedCount = $allApplicants->whereIn('status', ['exam_passed', 'oriented', 'compliance_met'])->count();
    $paidCount = $allApplicants->where('status', 'paid_out')->count();
    $disqualifiedCount = $allApplicants->filter(fn($a) => str_starts_with($a->status, 'disqualified'))->count();

    $statusLabels = [
        'submitted' => 'Application submitted',
        'pending_mswdo' => 'Pending MSWDO assessment',
        'exam_scheduled' => 'Exam scheduled',
        'exam_passed' => 'Exam passed',
        'oriented' => 'Orientation complete',
        'compliance_met' => 'Compliance met',
        'paid_out' => 'Scholarship released',
    ];
@endphp

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-flat p-3">
            <p class="small text-muted-soft mb-1">Total Applicants</p>
            <p class="h4 fw-bold mb-0">{{ $total }}</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-flat p-3">
            <p class="small text-muted-soft mb-1">In Progress</p>
            <p class="h4 fw-bold mb-0" style="color: var(--gold-600);">{{ $submittedCount }}</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-flat p-3">
            <p class="small text-muted-soft mb-1">Qualified / Passed</p>
            <p class="h4 fw-bold mb-0 text-success">{{ $passedCount }}</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-flat p-3">
            <p class="small text-muted-soft mb-1">Disqualified</p>
            <p class="h4 fw-bold mb-0 text-danger">{{ $disqualifiedCount }}</p>
        </div>
    </div>
</div>

<!-- Search -->
<div class="mb-4" style="max-width: 340px;">
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted-soft"></i></span>
        <input type="text" id="applicant-search" placeholder="Search by name..." class="form-control border-start-0">
    </div>
</div>

@forelse ($applicants as $status => $group)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <h2 class="h6 fw-bold mb-0">{{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}</h2>
            <span class="small text-muted-soft">({{ $group->count() }})</span>
        </div>

        <div class="card-flat overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: var(--surface-50);">
                        <tr class="small text-uppercase text-muted-soft">
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
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-semibold small flex-shrink-0"
                                              style="width:32px;height:32px;background: var(--surface-100); color: var(--ink-800);">
                                            {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                        </span>
                                        <span class="applicant-name fw-semibold">{{ $applicant->first_name }} {{ $applicant->last_name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted-soft">
                                    {{ $applicant->school_name ?? '—' }}
                                    @if ($applicant->course)
                                        <div class="small text-muted-soft">{{ $applicant->course }} {{ $applicant->year_level }}</div>
                                    @endif
                                </td>
                                <td><span class="badge-soft-navy">{{ ucfirst($applicant->program_type) }}</span></td>
                                <td class="text-muted-soft">{{ $applicant->contact_number ?? '—' }}</td>
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
@empty
    <div class="card-flat p-5 text-center text-muted-soft">
        No applicants yet.
    </div>
@endforelse

<script>
document.getElementById('applicant-search').addEventListener('input', function (e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.applicant-row').forEach(row => {
        const name = row.querySelector('.applicant-name').textContent.toLowerCase();
        row.style.display = name.includes(term) ? '' : 'none';
    });
});
</script>
@endsection