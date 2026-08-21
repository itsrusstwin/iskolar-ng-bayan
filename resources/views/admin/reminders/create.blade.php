@extends('layouts.app')
@section('title', 'New Reminder')

@section('content')

<a href="{{ route('admin.reminders.index') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to reminders
</a>

<div class="row justify-content-start">
    <div class="col-lg-7">
        <div class="card-flat p-4 p-md-5">
            <h1 class="h5 fw-bold mb-4">New Reminder</h1>

            <form method="POST" action="{{ route('admin.reminders.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Reminder</label>
                    <textarea name="body" rows="4" class="form-control" required placeholder="e.g. All applications must be submitted within the deadline.">{{ old('body') }}</textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="isActive">
                    <label class="form-check-label small" for="isActive">Active (visible to students on their dashboard)</label>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2">Save Reminder</button>
            </form>
        </div>
    </div>
</div>

@endsection