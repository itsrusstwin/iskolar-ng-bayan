@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0">Audit Log <span class="badge-soft-navy ms-1">{{ $logs->total() }}</span></h1>
        <p class="small text-muted-soft mb-0">Record of every admin action taken in the portal.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.audit-log.index') }}" class="mb-4">
    <div class="admin-panel">
        <div class="admin-panel__body d-flex align-items-end gap-2 flex-wrap">
            <div>
                <label class="form-label small fw-semibold mb-1" for="logDate">Filter by date</label>
                <input type="date" id="logDate" name="date" value="{{ request('date') }}" class="form-control form-control-sm" style="min-width: 180px;">
            </div>
            <button type="submit" class="btn btn-sm btn-navy d-inline-flex align-items-center gap-1">
                <i class="bi bi-funnel"></i> Apply
            </button>
            @if (request()->has('date') && request('date'))
                <a href="{{ route('admin.audit-log.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                    <i class="bi bi-x-lg"></i> Clear
                </a>
            @endif
        </div>
        @if (request()->filled('date'))
            <p class="small text-muted-soft px-4 pb-3 mb-0">
                Showing entries for <span class="fw-semibold" style="color: var(--ink-800);">{{ \Carbon\Carbon::parse(request('date'))->format('F j, Y') }}</span>
            </p>
        @endif
    </div>
</form>

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
            <div class="d-flex align-items-start justify-content-between gap-3 p-3 border-top flex-wrap audit-log-row" style="transition: background .15s ease;">
                <div class="d-flex align-items-start gap-3" style="min-width: 0;">
                    <input type="checkbox" name="ids[]" value="{{ $log->id }}" class="form-check-input mt-2 log-checkbox">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-semibold small flex-shrink-0"
                         style="width:34px;height:34px;background: linear-gradient(135deg, #123a6b, #2c65ac); color: #fff;">
                        {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                    </div>
                    <div style="min-width: 0;">
                        <p class="mb-0 d-flex align-items-center flex-wrap gap-2">
                            <span class="fw-semibold">{{ $log->user->name ?? 'Unknown / system' }}</span>
                            <span class="badge-soft-navy" style="font-size: 11px;">{{ str_replace('_', ' ', $log->action) }}</span>
                        </p>
                        <p class="small text-muted-soft mb-0">{{ $log->description }}</p>
                        @if ($log->applicant)
                            <a href="{{ route('applicants.show', $log->applicant) }}" class="small fw-semibold d-inline-flex align-items-center gap-1" style="color: var(--ink-700);">
                                <i class="bi bi-person"></i> View {{ $log->applicant->first_name }} {{ $log->applicant->last_name }}'s profile
                            </a>
                        @endif
                    </div>
                </div>
                <span class="small text-muted-soft flex-shrink-0 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-clock" style="font-size:.75rem;"></i> {{ $log->created_at->format('M d, Y g:ia') }}
                </span>
            </div>
        @empty
            <div class="empty-state">
                <span class="empty-state__icon"><i class="bi bi-journal-check"></i></span>
                <h6>No entries yet</h6>
                <p>No admin actions have been recorded for this filter.</p>
            </div>
        @endforelse
    </div>
</form>

<div class="mt-4 d-flex justify-content-center">
    {{ $logs->links('pagination::bootstrap-5') }}
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