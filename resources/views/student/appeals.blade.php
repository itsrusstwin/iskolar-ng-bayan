@extends('layouts.student')
@section('title', 'My Appeals - Iskolar ng Bayan')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Appeals</h1>
    <p class="text-muted-soft small mb-0">Ask for a review if you believe a disqualification was a mistake.</p>
</div>

<div class="card-elevated p-4">
    <div class="panel-head">
        <span class="panel-head__icon"><i class="bi bi-shield-exclamation"></i></span>
        <div>
            <p class="panel-head__title">Disqualification &amp; appeal</p>
            <p class="panel-head__sub">Current standing of your application</p>
        </div>
    </div>

    @if ($isDisqualified && $latestDisqualification)
        <div class="alert-brand-danger p-3 mb-4">
            <p class="fw-bold mb-1">Application disqualified</p>
            <p class="small mb-0">
                {{ $latestDisqualification->reason ?? 'Reason not specified.' }}
                @if ($latestDisqualification->notice_issued_at)
                    <span class="d-block mt-1 opacity-75">Notice issued {{ \Carbon\Carbon::parse($latestDisqualification->notice_issued_at)->format('M d, Y') }}</span>
                @endif
            </p>
        </div>

        @if ($existingAppeal)
            <div class="p-3 rounded-md surface-inset mb-3">
                <p class="small text-muted-soft mb-1">Your appeal, filed {{ $existingAppeal->filed_at?->format('M d, Y') }}:</p>
                <p class="small mb-2" style="white-space: pre-line;">{{ $existingAppeal->reconsideration_notes }}</p>
                @if ($existingAppeal->result === 'pending')
                    <span class="badge-soft-navy">Under review</span>
                @elseif ($existingAppeal->result === 'approved')
                    <span class="badge-soft-gold">Approved — reinstated</span>
                @else
                    <span class="badge bg-danger-subtle text-danger-emphasis">Denied</span>
                @endif
            </div>
        @endif

        @if (! $existingAppeal || $existingAppeal->result === 'denied')
            <form method="POST" action="{{ route('appeals.store') }}">
                @csrf
                <input type="hidden" name="disqualification_id" value="{{ $latestDisqualification->id }}">
                <label class="form-label small fw-semibold" for="reconsideration_notes">
                    {{ $existingAppeal ? 'File another appeal' : 'Reason for reconsideration' }}
                </label>
                <textarea id="reconsideration_notes" name="reconsideration_notes" rows="4" class="form-control mb-3" required
                          placeholder="Explain why you believe this decision should be reconsidered...">{{ old('reconsideration_notes') }}</textarea>
                @error('reconsideration_notes') <p class="small text-danger">{{ $message }}</p> @enderror
                <button type="submit" class="btn btn-navy btn-sm px-3">Submit appeal</button>
            </form>
        @endif
    @else
        <div class="text-center py-4">
            <i class="bi bi-shield-check fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
            <p class="small text-muted-soft mb-0">
                You don't have any disqualification on record, so there's nothing to appeal.<br>
                If you think there's an error with your application, please reach out via
                <a href="{{ route('support.index') }}" class="fw-semibold link-brand">Contact Support</a>.
            </p>
        </div>
    @endif
</div>
@endsection
