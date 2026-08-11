@extends('layouts.app')
@section('title', 'Support Inbox')
@section('subtitle', 'Conversations between students and the administrator')

@section('content')

<div class="admin-panel">
    <div class="admin-panel__header d-flex align-items-center gap-2">
        <div>
            <h2 class="h6 fw-bold mb-0">Support Messages <span class="badge-soft-navy ms-1">{{ number_format($threads->count()) }}</span></h2>
            <p class="small text-muted-soft mb-0">Reply to students who contacted the program.</p>
        </div>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($threads->isNotEmpty())
            <div class="admin-table-scroll">
                @foreach ($threads as $thread)
                    <a href="{{ route('admin.support.show', $thread->user) }}" class="d-flex align-items-center gap-3 px-4 py-3 border-bottom text-decoration-none" style="color: inherit; transition: background .15s ease;" onmouseover="this.style.background='var(--surface-50)'" onmouseout="this.style.background='transparent'">
                        <span class="admin-avatar" style="background: linear-gradient(135deg, #123a6b, #2c65ac); color: #fff;">
                            {{ strtoupper(substr($thread->user->name ?? 'U', 0, 1)) }}
                        </span>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="d-flex align-items-center gap-2">
                                <p class="mb-0 fw-semibold small text-truncate">{{ $thread->user->name }}</p>
                                @if ($thread->unread > 0)
                                    <span class="badge-soft-gold" style="font-size:.68rem;">{{ $thread->unread }} new</span>
                                @endif
                            </div>
                            <p class="small text-muted-soft mb-0 text-truncate">{{ $thread->last_message }}</p>
                        </div>
                        <span class="small text-muted-soft flex-shrink-0 d-inline-flex align-items-center gap-1">
                            <i class="bi bi-clock" style="font-size:.75rem;"></i> {{ $thread->last_time->diffForHumans() }}
                        </span>
                        <i class="bi bi-chevron-right text-muted-soft flex-shrink-0"></i>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <span class="empty-state__icon"><i class="bi bi-inbox"></i></span>
                <h6>Inbox is empty</h6>
                <p>Student messages will appear here once they reach out through the support page.</p>
            </div>
        @endif
    </div>
</div>

@endsection
