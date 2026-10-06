@extends('layouts.student')
@section('title', 'My Schedules - Iskolar ng Bayan')

@section('content')
@php
    $events = collect([
        ['label' => 'Qualifying Exam', 'icon' => 'bi-calendar-event', 'at' => $applicant->exam_scheduled_at],
        ['label' => 'Orientation', 'icon' => 'bi-mortarboard', 'at' => $applicant->orientation_scheduled_at],
    ])->filter(fn ($e) => $e['at'])->sortBy('at');
@endphp

<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Upcoming Schedules</h1>
    <p class="text-muted-soft small mb-0">Your qualifying exam and orientation dates.</p>
</div>

<div class="card-elevated p-4">
    <div class="panel-head">
        <span class="panel-head__icon"><i class="bi bi-calendar3"></i></span>
        <div>
            <p class="panel-head__title">Schedule</p>
            <p class="panel-head__sub">You'll also get a notification whenever a date is set or changed</p>
        </div>
    </div>

    @forelse ($events as $event)
        @php $isPast = $event['at']->isPast(); @endphp
        <div class="d-flex align-items-center justify-content-between py-3 flex-wrap gap-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
            <div class="d-flex align-items-center gap-3">
                <span class="panel-head__icon"><i class="bi {{ $event['icon'] }}"></i></span>
                <div>
                    <p class="mb-0 fw-semibold">{{ $event['label'] }}</p>
                    <p class="small text-muted-soft mb-0">{{ $event['at']->format('l, F j, Y \a\t g:ia') }}</p>
                </div>
            </div>
            <div class="text-end">
                <span class="{{ $isPast ? 'badge-soft-gold' : 'badge-soft-navy' }}">{{ $isPast ? 'Done' : 'Scheduled' }}</span>
                <p class="small text-muted-soft mb-0 mt-1">{{ $event['at']->diffForHumans() }}</p>
            </div>
        </div>
    @empty
        <div class="text-center py-4">
            <i class="bi bi-calendar-x fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
            <p class="small text-muted-soft mb-0">Nothing has been scheduled for you yet. We'll notify you as soon as a date is set.</p>
        </div>
    @endforelse
</div>
@endsection
