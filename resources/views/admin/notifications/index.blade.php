@extends('layouts.app')
@section('title', 'Notifications')
@section('subtitle', 'Student activity and actions awaiting your attention')

@section('header_actions')
    @if ($notifications->isNotEmpty())
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-check2-all"></i> Mark all as read
            </button>
        </form>
    @endif
@endsection

@section('content')

<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center gap-3">
        <div>
            <h2 class="h6 fw-bold mb-0">Notifications</h2>
            <p class="small text-muted-soft mb-0">Appeals, requirement submissions, profile updates, and support messages</p>
        </div>
        @if ($notifications->isNotEmpty())
            <div class="d-flex align-items-center gap-2 ms-md-auto">
                <label class="d-inline-flex align-items-center gap-1 small fw-semibold text-muted-soft" style="cursor:pointer;">
                    <input type="checkbox" id="select-all" class="form-check-input mt-0">
                    Select all
                </label>
                <button id="delete-selected" type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" disabled>
                    <i class="bi bi-trash"></i> Delete selected (<span id="selected-count">0</span>)
                </button>
            </div>
        @endif
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        <form method="POST" action="{{ route('admin.notifications.destroy-many') }}" id="bulk-delete-form" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        @forelse ($notifications as $notification)
            @php
                $data = $notification->data;
                $isUnread = $notification->read_at === null;
            @endphp
            <div class="admin-activity-item {{ $isUnread ? 'bg-surface' : '' }}">
                <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div class="d-flex gap-2 align-items-center" style="min-width:0;">
                        <input type="checkbox" class="form-check-input notif-check mt-0 flex-shrink-0" value="{{ $notification->id }}"
                               data-count-label="selected-count">
                        <span class="admin-kpi-icon {{ $isUnread ? 'admin-kpi-icon--navy' : 'admin-kpi-icon--muted' }}"
                              style="width:32px;height:32px;font-size:.85rem;flex-shrink:0;">
                            @php $icon = match(true) { str_contains($data['title'] ?? '', 'appeal') => 'bi-shield-exclamation', str_contains($data['title'] ?? '', 'Requirement') => 'bi-file-earmark-arrow-up', str_contains($data['title'] ?? '', 'support') => 'bi-chat-dots', default => 'bi-person-fill-check' }; @endphp
                            <i class="bi {{ $icon }}"></i>
                        </span>
                        <div class="flex-grow-1 min-w-0">
                            <p class="small mb-0">
                                <span class="fw-semibold">{{ $data['title'] ?? 'Update' }}</span>
                                @if ($isUnread)
                                    <span class="badge bg-primary-subtle text-primary-emphasis ms-1" style="font-size:10px;">New</span>
                                @endif
                            </p>
                            <p class="small text-muted-soft mb-0">{{ $data['body'] ?? '' }}</p>
                            <p class="text-muted-soft mb-0" style="font-size:.7rem;">
                                {{ $notification->created_at?->diffForHumans() }}
                                @if (!empty($data['student_name']))
                                    <span class="opacity-50">·</span> {{ $data['student_name'] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        @if (!empty($data['url']))
                            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ $data['url'] }}">
                                <button type="submit" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1"
                                        style="padding:.25rem .6rem; font-size:.75rem;">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </form>
                        @endif
                        @if ($isUnread)
                            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                                        style="padding:.25rem .6rem; font-size:.75rem;" title="Mark as read">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}"
                              onsubmit="return confirm('Delete this notification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                    style="padding:.25rem .6rem; font-size:.75rem;" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <span class="empty-state__icon"><i class="bi bi-bell-slash"></i></span>
                <h6>All caught up</h6>
                <p>Appeals, requirement submissions, profile updates, and support messages will show up here.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.notif-check');
        const selectAll = document.getElementById('select-all');
        const deleteSelectedBtn = document.getElementById('delete-selected');
        const countLabel = document.getElementById('selected-count');
        const bulkForm = document.getElementById('bulk-delete-form');

        function updateSelection() {
            const count = Array.from(checkboxes).filter(c => c.checked).length;
            if (countLabel) countLabel.textContent = count;
            if (deleteSelectedBtn) deleteSelectedBtn.disabled = count === 0;
            if (selectAll) selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
        }

        checkboxes.forEach(c => c.addEventListener('change', updateSelection));
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(c => { c.checked = selectAll.checked; });
                updateSelection();
            });
        }

        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const checked = Array.from(checkboxes).filter(c => c.checked);
                if (!checked.length) return;
                if (!confirm('Delete ' + checked.length + ' selected notification(s)?')) return;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids';
                input.value = checked.map(c => c.value).join(',');
                bulkForm.appendChild(input);
                bulkForm.submit();
            });
        }
    });
</script>
@endpush
