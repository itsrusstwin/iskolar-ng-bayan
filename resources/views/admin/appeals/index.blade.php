@extends('layouts.app')
@section('title', 'Appeals')
@section('subtitle', 'Review disqualification appeals')

@section('content')

<!-- Status tabs -->
<div class="d-flex gap-2 flex-wrap mb-4">
    @php
        $tabs = [
            'pending' => ['Pending', $counts['pending']],
            'approved' => ['Approved', $counts['approved']],
            'denied' => ['Denied', $counts['denied']],
            'all' => ['All', array_sum($counts)],
        ];
    @endphp
    @foreach ($tabs as $key => [$label, $count])
        <a href="{{ route('admin.appeals.index', ['status' => $key]) }}"
           class="btn btn-sm {{ $status === $key ? 'btn-navy' : 'btn-outline-navy' }}">
            {{ $label }} <span class="badge-soft-navy ms-1">{{ $count }}</span>
        </a>
    @endforeach

    <form method="GET" action="{{ route('admin.appeals.index') }}" class="ms-md-auto">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="input-group input-group-sm">
            <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search applicant…" class="form-control border-start-0" style="min-width:200px;">
        </div>
    </form>
</div>

@forelse ($appeals as $appeal)
    @php
        $dq = $appeal->disqualification;
        $a = $dq?->applicant;
        $badge = match ($appeal->result) {
            'approved' => 'admin-badge-success',
            'denied' => 'admin-badge-danger',
            default => 'admin-badge-warning',
        };
    @endphp
    <div class="admin-panel mb-3">
        <div class="admin-panel__body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-avatar">{{ $a ? strtoupper(substr($a->first_name, 0, 1) . substr($a->last_name, 0, 1)) : '?' }}</span>
                    <div>
                        @if ($a)
                            <a href="{{ route('applicants.show', $a) }}" class="fw-semibold text-decoration-none">{{ $a->first_name }} {{ $a->last_name }}</a>
                        @else
                            <span class="fw-semibold text-muted-soft">Removed applicant</span>
                        @endif
                        <p class="small text-muted-soft mb-0">Filed {{ $appeal->filed_at?->format('M d, Y g:ia') ?? '—' }}</p>
                    </div>
                </div>
                <span class="{{ $badge }}">{{ ucfirst($appeal->result) }}</span>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <p class="small text-muted-soft mb-1">Disqualification
                        @if ($dq?->stage) · <span class="text-capitalize">{{ str_replace('_', ' ', $dq->stage) }}</span> @endif
                    </p>
                    <p class="small mb-0">{{ $dq->reason ?? 'No reason recorded.' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="small text-muted-soft mb-1">Student's explanation</p>
                    <p class="small mb-0" style="white-space: pre-line;">{{ $appeal->reconsideration_notes }}</p>
                </div>
            </div>

            @if ($appeal->result === 'pending')
                <div class="d-flex gap-2 mt-3 pt-3 border-top">
                    <form method="POST" action="{{ route('admin.appeals.approve', $appeal) }}"
                          onsubmit="return confirm('Approve this appeal and reinstate the applicant?');">
                        @csrf
                        <button class="btn btn-navy btn-sm" type="submit"><i class="bi bi-check-lg me-1"></i> Approve &amp; reinstate</button>
                    </form>
                    <form method="POST" action="{{ route('admin.appeals.reject', $appeal) }}"
                          onsubmit="return confirm('Deny this appeal? The disqualification will stand.');">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-x-lg me-1"></i> Deny</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="admin-panel">
        <div class="admin-panel__body text-center text-muted-soft py-5">
            <i class="bi bi-shield-check fs-2 d-block mb-2 opacity-50"></i>
            No {{ $status === 'all' ? '' : $status }} appeals.
        </div>
    </div>
@endforelse
@endsection
