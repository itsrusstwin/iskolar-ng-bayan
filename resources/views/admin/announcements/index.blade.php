@extends('layouts.app')
@section('title', 'Announcements')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
    <div>
        <h1 class="h4 fw-bold mb-0">Announcements <span class="badge-soft-navy ms-1">{{ number_format($announcements->count()) }}</span></h1>
        <p class="small text-muted-soft mb-0 mt-1">Publish updates that appear on the Home page.</p>
    </div>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-navy d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> New Announcement
    </a>
</div>

<div class="card-flat overflow-hidden">
    @forelse ($announcements as $announcement)
        <div class="d-flex align-items-center justify-content-between p-4 border-top flex-wrap gap-3" style="transition: background .15s ease;">
            <div class="min-w-0" style="flex: 1 1 300px;">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <p class="fw-semibold mb-0 text-truncate">{{ $announcement->title }}</p>
                    @if ($announcement->is_published)
                        <span class="badge-soft-gold"><i class="bi bi-megaphone"></i> Published</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Draft</span>
                    @endif
                </div>
                <p class="small text-muted-soft mb-1 text-truncate">{{ Str::limit($announcement->body, 100) }}</p>
                <p class="small text-muted-soft opacity-75 mb-0" style="font-size:.75rem;">
                    <i class="bi bi-clock"></i> {{ $announcement->created_at->format('M d, Y g:ia') }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-ghost btn-icon text-danger" style="width:32px;height:32px;" title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <span class="empty-state__icon"><i class="bi bi-megaphone"></i></span>
            <h6>No announcements yet</h6>
            <p>Click "New Announcement" to publish an update for students and site visitors.</p>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-sm btn-navy d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> New Announcement
            </a>
        </div>
    @endforelse
</div>

@endsection