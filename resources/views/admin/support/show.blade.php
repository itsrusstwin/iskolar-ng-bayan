@extends('layouts.app')
@section('title', 'Conversation with ' . $user->name)

@section('content')

<a href="{{ route('admin.support.index') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to inbox
</a>

<div class="admin-panel overflow-hidden">
    <!-- Thread header -->
    <div class="admin-panel__header d-flex align-items-center gap-3">
        <span class="admin-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
        <div>
            <p class="fw-bold mb-0">{{ $user->name }}</p>
            <p class="small text-muted-soft mb-0">
                {{ $user->email }}
                @if ($user->applicant)
                    · {{ $user->applicant->school_name ?? 'No school' }}
                @endif
            </p>
        </div>
    </div>

    <!-- Messages -->
    <div class="p-4" style="max-height: 460px; overflow-y: auto; background: var(--surface-50);" id="chatThread">
        @forelse ($messages as $message)
            @php $isMine = $message->sender_id === auth()->id(); @endphp
            <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                <div class="chat-bubble thread-message {{ $isMine ? 'chat-bubble--mine thread-message--outgoing' : 'chat-bubble--theirs thread-message--incoming' }}">
                    <p class="mb-1" style="white-space: pre-line;">{{ $message->message }}</p>
                    <div class="d-flex align-items-center gap-1 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                        <small class="{{ $isMine ? 'text-white-50' : 'text-muted-soft' }} opacity-75">{{ $message->created_at->format('M d, g:ia') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <span class="empty-state__icon"><i class="bi bi-chat-dots"></i></span>
                <h6>Start the conversation</h6>
                <p>No messages yet. Send the student a reply below.</p>
            </div>
        @endforelse
    </div>

    <!-- Reply -->
    <form method="POST" action="{{ route('admin.support.reply', $user) }}" class="p-4 border-top" style="background: var(--surface-0);">
        @csrf
        <label class="form-label small fw-semibold" for="adminReply">Reply to {{ $user->name }}</label>
        <div class="input-group">
            <textarea name="message" id="adminReply" rows="2" class="form-control" maxlength="2000"
                      placeholder="Type your reply..." required>{{ old('message') }}</textarea>
            <button type="submit" class="btn btn-navy px-4 d-inline-flex align-items-center gap-2">
                <i class="bi bi-send"></i> Send Reply
            </button>
        </div>
    </form>
</div>

<script>
    const thread = document.getElementById('chatThread');
    if (thread) thread.scrollTop = thread.scrollHeight;
</script>

@endsection
