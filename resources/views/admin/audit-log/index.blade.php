@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Audit Log</h1>
    <span class="small text-muted-soft">{{ $logs->total() }} total {{ Str::plural('entry', $logs->total()) }}</span>
</div>

<div class="card-flat overflow-hidden">
    @forelse ($logs as $log)
        <div class="d-flex align-items-start justify-content-between gap-3 p-3 border-top flex-wrap">
            <div class="d-flex align-items-start gap-3" style="min-width: 0;">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-semibold small flex-shrink-0"
                     style="width:34px;height:34px;background: var(--surface-100); color: var(--ink-800);">
                    {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                </div>
                <div style="min-width: 0;">
                    <p class="mb-0">
                        <span class="fw-semibold">{{ $log->user->name ?? 'Unknown / system' }}</span>
                        <span class="badge-soft-navy ms-1" style="font-size: 11px;">{{ str_replace('_', ' ', $log->action) }}</span>
                    </p>
                    <p class="small text-muted-soft mb-0">{{ $log->description }}</p>
                    @if ($log->applicant)
                        <a href="{{ route('applicants.show', $log->applicant) }}" class="small fw-semibold" style="color: var(--ink-700);">
                            View {{ $log->applicant->first_name }} {{ $log->applicant->last_name }}'s profile
                        </a>
                    @endif
                </div>
            </div>
            <span class="small text-muted-soft flex-shrink-0">{{ $log->created_at->format('M d, Y g:ia') }}</span>
        </div>
    @empty
        <div class="p-5 text-center text-muted-soft">
            No admin actions have been recorded yet.
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>

@endsection