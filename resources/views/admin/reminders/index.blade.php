@extends('layouts.app')
@section('title', 'Important Reminders')
@section('subtitle', 'Edit the reminders shown to students on their dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">Important Reminders</h1>
        <p class="small text-muted-soft mb-0 mt-1">Each reminder appears as a bullet point on the student dashboard.</p>
    </div>
    <a href="{{ route('admin.reminders.create') }}" class="btn btn-navy d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> New Reminder
    </a>
</div>

<div class="card-flat overflow-hidden">
    @forelse ($reminders as $reminder)
        <div class="d-flex align-items-center justify-content-between p-4 border-top flex-wrap gap-3">
            <div class="min-w-0" style="flex: 1 1 300px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    @if ($reminder->is_active)
                        <span class="badge-soft-gold"><i class="bi bi-eye"></i> Visible</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-eye-slash"></i> Hidden</span>
                    @endif
                    <span class="small text-muted-soft opacity-75">Position {{ $reminder->sort_order }}</span>
                </div>
                <p class="mb-0 text-body">{{ $reminder->body }}</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <form method="POST" action="{{ route('admin.reminders.toggle', $reminder) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1">
                        <i class="bi {{ $reminder->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                        {{ $reminder->is_active ? 'Hide' : 'Show' }}
                    </button>
                </form>
                <a href="{{ route('admin.reminders.edit', $reminder) }}" class="small fw-semibold" style="color: var(--ink-700);">Edit</a>
                <form method="POST" action="{{ route('admin.reminders.destroy', $reminder) }}" onsubmit="return confirm('Delete this reminder?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none p-0 small">Delete</button>
                </form>
                <form method="POST" action="{{ route('admin.reminders.move-up', $reminder) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-icon text-muted-soft" title="Move up" {{ $loop->first ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-up-short"></i>
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.reminders.move-down', $reminder) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-icon text-muted-soft" title="Move down" {{ $loop->last ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-down-short"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-5 text-center text-muted-soft">
            No reminders yet. Click "New Reminder" to add one.
        </div>
    @endforelse
</div>

@endsection