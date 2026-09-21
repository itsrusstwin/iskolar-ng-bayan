@extends('layouts.app')
@section('title', 'Export Applicants to Excel')
@section('subtitle', 'Step 2 of 2 — choose details, preview live, then download')

@section('header_actions')
    <a href="{{ route('admin.export.applicants') }}" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to selection
    </a>
@endsection

@section('content')

@php
    $fieldIcons = [
        'first_name' => 'bi-person',
        'middle_name' => 'bi-person-add',
        'last_name' => 'bi-person-vcard',
        'email' => 'bi-envelope',
        'contact_number' => 'bi-telephone',
        'sex' => 'bi-gender-ambiguous',
        'date_of_birth' => 'bi-calendar-heart',
        'school_name' => 'bi-mortarboard',
        'course' => 'bi-journal-code',
        'year_level' => 'bi-layers',
        'program_type' => 'bi-tags',
        'status' => 'bi-flag',
        'barangay' => 'bi-geo-alt',
        'sitio' => 'bi-signpost-split',
        'landmark' => 'bi-geo',
        'father_name' => 'bi-person-badge',
        'mother_maiden_name' => 'bi-person-heart',
    ];
@endphp

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
                    {{ $applicants->count() }} applicant(s) selected. Tick the details to include — the preview updates instantly.
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
            <div class="row g-2">
                @foreach ($fieldOptions as $key => $label)
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <label class="export-field-chip d-flex align-items-center gap-2">
                            <input type="checkbox" class="form-check-input mt-0 field-check" name="fields[]" value="{{ $key }}" checked>
                            <i class="bi {{ $fieldIcons[$key] ?? 'bi-check2-square' }} export-field-chip__icon"></i>
                            <span class="small fw-semibold">{{ $label }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-panel">
        <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 flex-wrap">
            <div>
                <h2 class="h6 fw-bold mb-0">
                    <i class="bi bi-eye-fill me-1" style="color: var(--gold-500);"></i>Live Preview
                </h2>
                <p class="small text-muted-soft mb-0">This is exactly what the Excel file will contain — tint the grid as you change fields.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="export-sheet__chip">
                    <i class="bi bi-grid-3x3-gap"></i> <span id="col-count">{{ count($fieldOptions) }}</span> columns
                </span>
                <span class="export-sheet__chip">
                    <i class="bi bi-rows"></i> {{ $applicants->count() }} rows
                </span>
            </div>
        </div>

        <div class="export-sheet">
            <div class="export-sheet__toolbar">
                <span class="export-sheet__file">
                    <i class="bi bi-file-earmark-excel"></i>
                    applicants_{{ now()->format('Y-m-d_His') }}.xls
                </span>
                
            </div>

            <div class="export-sheet__scroll">
                <table class="export-preview">
                    <colgroup>
                        <col class="ep-num">
                        @foreach ($fieldOptions as $key => $label)
                            <col data-field="{{ $key }}">
                        @endforeach
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="ep-num">#</th>
                            @foreach ($fieldOptions as $key => $label)
                                <th data-field="{{ $key }}">{{ strtoupper($label) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview as $row)
                            <tr>
                                <td class="ep-num">{{ $loop->iteration }}</td>
                                @foreach ($fieldOptions as $key => $label)
                                    <td data-field="{{ $key }}">{{ $row[$key] !== '' ? $row[$key] : '—' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="export-sheet__footer">
                <span class="small text-muted-soft">
                    Selected fields: <span class="fw-bold" id="field-count">{{ count($fieldOptions) }}</span>
                </span>
                <button type="submit" class="btn btn-navy d-inline-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Download Excel
                </button>
            </div>
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
        const colCountLabel = document.getElementById('col-count');

        function update() {
            const count = Array.from(checks).filter(c => c.checked).length;
            countLabel.textContent = count;
            if (colCountLabel) colCountLabel.textContent = count;
            if (selectAll) selectAll.checked = count === checks.length;

            checks.forEach(c => {
                const show = c.checked;
                document.querySelectorAll('[data-field="' + c.value + '"]').forEach(el => {
                    el.classList.toggle('ep-hidden', !show);
                });
            });
        }

        checks.forEach(c => c.addEventListener('change', update));
        if (selectAll) selectAll.addEventListener('change', function () {
            checks.forEach(c => { c.checked = selectAll.checked; });
            update();
        });

        update();
    });
</script>
@endpush