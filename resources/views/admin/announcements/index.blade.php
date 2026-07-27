@extends('layouts.app')
@section('title', 'Announcements')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Announcements</h1>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-navy d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> New Announcement
    </a>
</div>

<div class="card-flat overflow-hidden">
    @forelse ($announcements as $announcement)
        <div class="d-flex align-items-center justify-content-between p-4 border-top flex-wrap gap-3">
            <div class="min-w-0" style="flex: 1 1 300px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <p class="fw-semibold mb-0 text-truncate">{{ $announcement->title }}</p>
                    @if ($announcement->is_published)
                        <span class="badge-soft-gold">Published</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Draft</span>
                    @endif
                </div>
                <p class="small text-muted-soft mb-1 text-truncate">{{ Str::limit($announcement->body, 100) }}</p>
                <p class="small text-muted-soft opacity-75 mb-0">{{ $announcement->created_at->format('M d, Y g:ia') }}</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-shrink-0">
                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="small fw-semibold" style="color: var(--ink-700);">Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none p-0 small">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-5 text-center text-muted-soft">
            No announcements yet. Click "New Announcement" to add one.
        </div>
    @endforelse
</div>

@endsection