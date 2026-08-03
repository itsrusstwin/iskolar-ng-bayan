@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Audit Log</h1>
    <div class="d-flex align-items-center gap-3">
        <span class="small text-muted-soft">{{ $logs->total() }} total {{ Str::plural('entry', $logs->total()) }}</span>
    </div>
</div>

@if (session('error'))
    <div class="alert-brand-danger p-3 mb-4 small">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.audit-log.destroy') }}" id="deleteForm">
    @csrf
    <div class="card-flat overflow-hidden">
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-surface">
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="selectAll" class="form-check-input m-0">
                <label for="selectAll" class="small fw-semibold text-muted-soft mb-0 cursor-pointer user-select-none">Select all</label>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" id="deleteSelected" disabled>
                <i class="bi bi-trash"></i> Delete selected
            </button>
        </div>

        @forelse ($logs as $log)
            <div class="d-flex align-items-start justify-content-between gap-3 p-3 border-top flex-wrap audit-log-row">
                <div class="d-flex align-items-start gap-3" style="min-width: 0;">
                    <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="form-check-input mt-2 log-checkbox">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-semibold small flex-shrink-0"
                         style="width:34px;height:34px;background: var(--surface-100); color: var(--ink-800);">
                        {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                    </div>
                    <div style="min-width: 0;">
                        <p class="mb-0">
                            <span class="fw-semibold">{{ $log->user->name ?? 'Unknown / system' }}</span>
                            <span class="badge-soft-navy ms-1" style="font-size: 11px;">{{ str_replace('_', ' ', $log->action) }}</span>
                        </p>
                        <p class="small text-muted-soft mb-0">{{ $log->description }}</p>
                        @if ($log->applicant)
                            <a href="{{ route('applicants.show', $log->applicant) }}" class="small fw-semibold" style="color: var(--ink-700);">
                                View {{ $log->applicant->first_name }} {{ $log->applicant->last_name }}'s profile
                            </a>
                        @endif
                    </div>
                </div>
                <span class="small text-muted-soft flex-shrink-0">{{ $log->created_at->format('M d, Y g:ia') }}</span>
            </div>
        @empty
            <div class="p-5 text-center text-muted-soft">
                No admin actions have been recorded yet.
            </div>
        @endforelse
    </div>
</form>

<div class="mt-4">
    {{ $logs->links() }}
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.log-checkbox');
    const deleteBtn = document.getElementById('deleteSelected');

    function updateDeleteBtn() {
        const checked = document.querySelectorAll('.log-checkbox:checked').length;
        deleteBtn.disabled = checked === 0;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteBtn();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const allChecked = document.querySelectorAll('.log-checkbox:checked').length === checkboxes.length;
            selectAll.checked = allChecked;
            updateDeleteBtn();
        });
    });

    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const checked = document.querySelectorAll('.log-checkbox:checked').length;
        if (checked === 0) return;
        if (confirm('Are you sure you want to delete ' + checked + ' selected ' + (checked === 1 ? 'entry' : 'entries') + '? This cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    });
});
</script>
@endpush