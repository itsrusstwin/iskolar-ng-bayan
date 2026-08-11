@extends('layouts.app')
@section('title', 'Export Applicants to Excel')
@section('subtitle', 'Step 2 of 2 — choose which info to include')

@section('header_actions')
    <a href="{{ route('admin.export.applicants') }}" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to selection
    </a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.export.download') }}" id="export-form">
    @csrf
    @foreach ($ids as $id)
        <input type="hidden" name="ids[]" value="{{ $id }}">
    @endforeach

    <div class="admin-panel mb-4">
        <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 flex-wrap">
            <div>
                <h2 class="h6 fw-bold mb-0">Choose Fields</h2>
                <p class="small text-muted-soft mb-0">
                    {{ $applicants->count() }} applicant(s) selected. Pick which details to record in the Excel file.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <label class="d-inline-flex align-items-center gap-2 small fw-semibold text-muted-soft" style="cursor:pointer;">
                    <input type="checkbox" id="select-all" class="form-check-input mt-0" checked>
                    Select all fields
                </label>
            </div>
        </div>
        <div class="admin-panel__body">
            <div class="row g-3">
                @foreach ($fieldOptions as $key => $label)
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <label class="d-flex align-items-center gap-2 form-check-label w-100 border rounded px-3 py-2"
                               style="cursor:pointer; border-color: var(--surface-border) !important; background: var(--surface-50);">
                            <input type="checkbox" class="form-check-input mt-0 field-check" name="fields[]" value="{{ $key }}" checked>
                            <span class="small fw-semibold">{{ $label }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-panel">
        <div class="admin-panel__header">
            <h2 class="h6 fw-bold mb-0">Preview of Selected Applicants</h2>
            <p class="small text-muted-soft mb-0">Name and email shown here for confirmation</p>
        </div>
        <div class="admin-panel__body admin-panel__body--flush" style="max-height: 300px; overflow-y: auto;">
            @if ($applicants->isEmpty())
                <div class="text-center text-muted-soft py-4">No matching applicants.</div>
            @else
                <table class="table admin-table admin-table--export mb-0" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 48px;">
                        <col style="width: 28%;">
                        <col style="width: 32%;">
                        <col style="width: 40%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="ps-3" style="width: 48px;">#</th>
                            <th style="width: 28%;">Name</th>
                            <th style="width: 32%;">Email</th>
                            <th style="width: 40%;">School</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $applicant)
                            <tr>
                                <td class="ps-3 text-muted-soft">{{ $loop->iteration }}</td>
                                <td class="fw-semibold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $applicant->first_name }} {{ $applicant->last_name }}</td>
                                <td class="text-muted-soft" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $applicant->user?->email ?? '—' }}</td>
                                <td class="text-muted-soft" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $applicant->school_name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="admin-panel__body border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="small text-muted-soft">
                Selected fields: <span class="fw-semibold" id="field-count">0</span>
            </span>
            <button type="submit" class="btn btn-navy d-inline-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-spreadsheet"></i> Download Excel
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checks = document.querySelectorAll('.field-check');
        const selectAll = document.getElementById('select-all');
        const countLabel = document.getElementById('field-count');

        function update() {
            const count = Array.from(checks).filter(c => c.checked).length;
            countLabel.textContent = count;
            selectAll.checked = count === checks.length;
        }

        checks.forEach(c => c.addEventListener('change', update));
        selectAll.addEventListener('change', function () {
            checks.forEach(c => { c.checked = selectAll.checked; });
            update();
        });

        update();
    });
</script>
@endpush
