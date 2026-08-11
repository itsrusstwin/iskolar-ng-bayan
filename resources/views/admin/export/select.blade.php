@extends('layouts.app')
@section('title', 'Export Applicants to Excel')
@section('subtitle', 'Step 1 of 2 — choose which applicants to export')

@section('header_actions')
    <a href="{{ route('admin.applicants.index') }}" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to applicants
    </a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.export.fields') }}" id="export-form">
    @csrf

    <div class="admin-panel">
        <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
            <div>
                <h2 class="h6 fw-bold mb-0">Select Applicants ({{ $applicants->count() }})</h2>
                <p class="small text-muted-soft mb-0">Tick the applicants to include in the export, or select all.</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <label class="d-inline-flex align-items-center gap-2 small fw-semibold text-muted-soft" style="cursor:pointer;">
                    <input type="checkbox" id="select-all" class="form-check-input mt-0">
                    Select all
                </label>
                <span class="small text-muted-soft" id="selected-label">0 selected</span>
                <button type="submit" id="next-btn" class="btn btn-navy btn-sm d-inline-flex align-items-center gap-1" disabled>
                    Next: choose fields <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <div class="admin-panel__body admin-panel__body--flush" style="max-height: 560px; overflow-y: auto;">
            @if ($applicants->isEmpty())
                <div class="text-center text-muted-soft py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                    No applicants yet.
                </div>
            @else
                <table class="table admin-table admin-table--export mb-0" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 42px;">
                        <col style="width: 18%;">
                        <col style="width: 22%;">
                        <col style="width: 24%;">
                        <col style="width: 14%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="ps-3" style="width: 42px;">
                                <input type="checkbox" id="select-all-header" class="form-check-input mt-0" title="Select all">
                            </th>
                            <th style="width: 18%;">Name</th>
                            <th style="width: 22%;">Email</th>
                            <th style="width: 24%;">School</th>
                            <th style="width: 14%;">Program</th>
                            <th style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $applicant)
                            <tr>
                                <td class="ps-3">
                                    <input type="checkbox" class="form-check-input mt-0 applicant-check" name="ids[]" value="{{ $applicant->id }}">
                                </td>
                                <td style="overflow: hidden;">
                                    <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                        <span class="admin-avatar">{{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}</span>
                                        <span class="fw-semibold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $applicant->first_name }} {{ $applicant->last_name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted-soft" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $applicant->user?->email ?? '—' }}</td>
                                <td class="text-muted-soft" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $applicant->school_name ?? '—' }}</td>
                                <td class="text-muted-soft">{{ \App\Services\AdminDashboardService::PROGRAM_LABELS[$applicant->program_type] ?? ucfirst($applicant->program_type) }}</td>
                                <td><span class="{{ $dashboardStatusClasses[$applicant->status] ?? 'badge bg-secondary-subtle text-secondary-emphasis' }}" style="white-space: normal; text-align: center;">{{ $dashboardStatusLabels[$applicant->status] ?? ucfirst(str_replace('_', ' ', $applicant->status)) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($applicants->isNotEmpty())
            <div class="admin-panel__body border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="small text-muted-soft">Selected: <span class="fw-semibold" id="selected-count">0</span> / {{ $applicants->count() }}</span>
                <button type="submit" class="btn btn-navy d-inline-flex align-items-center gap-2">
                    Next: choose fields <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        @endif
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.applicant-check');
        const selectAll = document.getElementById('select-all');
        const selectAllHeader = document.getElementById('select-all-header');
        const nextBtns = document.querySelectorAll('#next-btn, .admin-panel__body .btn-navy');
        const countLabel = document.getElementById('selected-count');
        const selectedLabel = document.getElementById('selected-label');

        function update() {
            const count = Array.from(checkboxes).filter(c => c.checked).length;
            countLabel.textContent = count;
            selectedLabel.textContent = count + ' selected';
            nextBtns.forEach(btn => { btn.disabled = count === 0; });
            if (selectAll) selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
            if (selectAllHeader) selectAllHeader.checked = checkboxes.length > 0 && count === checkboxes.length;
        }

        function setAll(checked) {
            checkboxes.forEach(c => { c.checked = checked; });
            update();
        }

        checkboxes.forEach(c => c.addEventListener('change', update));
        if (selectAll) selectAll.addEventListener('change', function () { setAll(selectAll.checked); });
        if (selectAllHeader) selectAllHeader.addEventListener('change', function () { setAll(selectAllHeader.checked); });

        update();
    });
</script>
@endpush
