@extends('layouts.student')
@section('title', 'Contact Support - Iskolar ng Bayan')

@section('content')

<div class="card-elevated overflow-hidden">
    <!-- Chat header -->
    <div class="d-flex align-items-center gap-3 p-4 border-bottom" style="background: var(--surface-50);">
        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white"
              style="width:48px;height:48px;background: linear-gradient(135deg, var(--ink-800), var(--ink-600)); flex-shrink:0;">
            <i class="bi bi-headset"></i>
        </span>
        <div>
            <p class="fw-bold mb-0">Iskolar ng Bayan Support</p>
            <p class="small text-muted-soft mb-0">
                <i class="bi bi-circle-fill" style="font-size:.55rem; color:#1E6B3C;"></i>
                We usually reply within 1 business day
            </p>
        </div>
    </div>

    <!-- Messages -->
    <div class="p-4" style="max-height: 480px; overflow-y: auto; background: var(--surface-50);" id="chatThread">
        @forelse ($messages as $message)
            @php $isMine = $message->sender_id === auth()->id(); @endphp
            <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                <div class="chat-bubble {{ $isMine ? 'chat-bubble--mine' : 'chat-bubble--theirs' }}">
                    <p class="mb-1" style="white-space: pre-line;">{{ $message->message }}</p>
                    <div class="d-flex align-items-center gap-1 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                        <small class="text-muted-soft opacity-75">{{ $message->created_at->format('M d, g:ia') }}</small>
                        @if ($isMine)
                            <i class="bi bi-check2 text-muted-soft opacity-50" style="font-size:.75rem;"></i>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted-soft py-5">
                <i class="bi bi-chat-dots fs-2 d-block mb-2 opacity-50"></i>
                No messages yet. Send a message below and our team will get back to you.
            </div>
        @endforelse
    </div>

    <!-- Composer -->
    <form method="POST" action="{{ route('support.store') }}" class="p-4 border-top" style="background: var(--surface-0);">
        @csrf
        <label class="form-label small fw-semibold" for="supportMessage">Send a message</label>
        <div class="input-group">
            <textarea name="message" id="supportMessage" rows="2" class="form-control" maxlength="2000"
                      placeholder="Type your question or concern here..." required>{{ old('message') }}</textarea>
            <button type="submit" class="btn btn-navy px-4 d-inline-flex align-items-center gap-2">
                <i class="bi bi-send"></i> Send
            </button>
        </div>
        <p class="small text-muted-soft mt-2 mb-0">
            <i class="bi bi-info-circle"></i> Messages go directly to the LYDO administrator — no email account needed.
        </p>
    </form>
</div>

<script>
    // Scroll to the newest message on load
    const thread = document.getElementById('chatThread');
    if (thread) thread.scrollTop = thread.scrollHeight;
</script>

@endsection
