@extends('layouts.student')
@section('title', 'Notifications - Iskolar ng Bayan')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">Notifications</h1>
        <p class="small text-muted-soft mb-0">Updates on your application and scholarship</p>
    </div>
    @if ($notifications->isNotEmpty())
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-check2-all"></i> Mark all as read
            </button>
        </form>
    @endif
</div>

@forelse ($notifications as $notification)
    @php
        $data = $notification->data;
        $isUnread = $notification->read_at === null;
        $icon = match (true) {
            str_contains($data['title'] ?? '', 'approved') => 'bi-patch-check-fill',
            str_contains($data['title'] ?? '', 'approved') || str_contains($data['body'] ?? '', 'approved') => 'bi-patch-check-fill',
            str_contains($data['title'] ?? '', 'scheduled') => 'bi-calendar-event',
            str_contains($data['title'] ?? '', 'Disqualif') => 'bi-x-octagon-fill',
            str_contains($data['title'] ?? '', 'reply') => 'bi-chat-dots',
            default => 'bi-bell-fill',
        };
    @endphp
    <div class="card-elevated p-3 mb-3 {{ $isUnread ? 'card-elevated--highlight' : '' }}">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div class="d-flex gap-3" style="min-width:0;">
                <span class="admin-kpi-icon {{ $isUnread ? 'admin-kpi-icon--navy' : 'admin-kpi-icon--muted' }}"
                      style="width:36px;height:36px;font-size:.95rem;flex-shrink:0;">
                    <i class="bi {{ $icon }}"></i>
                </span>
                <div style="min-width:0;">
                    <p class="fw-semibold mb-0 small">
                        {{ $data['title'] ?? 'Application update' }}
                        @if ($isUnread)
                            <span class="badge bg-primary-subtle text-primary-emphasis ms-1" style="font-size:10px;">New</span>
                        @endif
                    </p>
                    <p class="small text-muted-soft mb-0">{{ $data['body'] ?? '' }}</p>
                    <p class="text-muted-soft mb-0 mt-1" style="font-size:.72rem;">{{ $notification->created_at?->diffForHumans() }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                @if (!empty($data['url']))
                    <a href="{{ route('notifications.open', ['id' => $notification->id, 'redirect' => $data['url']]) }}"
                       class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1" style="padding:.25rem .6rem; font-size:.75rem;">
                        <i class="bi bi-eye"></i> View
                    </a>
                @endif
                @if ($isUnread)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                                style="padding:.25rem .6rem; font-size:.75rem;" title="Mark as read">
                            <i class="bi bi-check2"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="card-elevated p-5 text-center">
        <i class="bi bi-bell-slash fs-2 d-block mb-2 opacity-50" style="color: var(--text-500);"></i>
        <p class="small text-muted-soft mb-0">No notifications yet. You'll be notified here whenever the admin updates your application.</p>
    </div>
@endforelse

@endsection
