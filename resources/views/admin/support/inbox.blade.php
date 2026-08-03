@extends('layouts.app')
@section('title', 'Support Inbox')
@section('subtitle', 'Conversations between students and the administrator')

@section('content')

<div class="admin-panel">
    <div class="admin-panel__header">
        <h2 class="h6 fw-bold mb-0">Support Messages</h2>
        <p class="small text-muted-soft mb-0">Reply to students who contacted the program.</p>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($threads->isNotEmpty())
            <div class="admin-table-scroll">
                @foreach ($threads as $thread)
                    <a href="{{ route('admin.support.show', $thread->user) }}" class="d-flex align-items-center gap-3 px-4 py-3 border-bottom text-decoration-none" style="color: inherit;" onmouseover="this.style.background='var(--surface-50)'" onmouseout="this.style.background='transparent'">
                        <span class="admin-avatar">
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
                        <span class="small text-muted-soft flex-shrink-0">{{ $thread->last_time->diffForHumans() }}</span>
                        <i class="bi bi-chevron-right text-muted-soft flex-shrink-0"></i>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted-soft py-5">
                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                No messages yet. Student messages will appear here.
            </div>
        @endif
    </div>
</div>

@endsection
