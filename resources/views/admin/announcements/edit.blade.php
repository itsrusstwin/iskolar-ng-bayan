@extends('layouts.app')
@section('title', 'Edit Announcement')

@section('content')

<a href="{{ route('admin.announcements.index') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to announcements
</a>

<div class="row justify-content-start">
    <div class="col-lg-7">
        <div class="card-flat p-4 p-md-5">
            <h1 class="h5 fw-bold mb-4">Edit Announcement</h1>

            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="body" rows="6" class="form-control" required>{{ old('body', $announcement->body) }}</textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }} id="isPublished">
                    <label class="form-check-label small" for="isPublished">Published (visible on the Home page)</label>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2">Save Changes</button>
            </form>
        </div>
    </div>
</div>

@endsection