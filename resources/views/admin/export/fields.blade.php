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

    $fieldGroups = [
        'Identity' => ['first_name', 'middle_name', 'last_name', 'sex', 'date_of_birth'],
        'Contact' => ['email', 'contact_number'],
        'Academic' => ['school_name', 'course', 'year_level', 'program_type', 'status'],
        'Address & Family' => ['barangay', 'sitio', 'landmark', 'father_name', 'mother_maiden_name'],
    ];
@endphp

<style>
    /* =========================================================
       EXPORT FIELDS — MODERN FIELD PICKER + LIVE DATA GRID
       ========================================================= */

    .export-builder {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .export-card {
        border: 1px solid var(--surface-border);
        border-radius: 1rem;
        background: var(--surface-0);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .export-card__header {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--surface-border);
        background:
            linear-gradient(180deg, rgba(255,255,255,.035), transparent),
            var(--surface-0);
    }

    .export-card__title {
        display: flex;
        align-items: center;
        gap: .65rem;
        font-size: .98rem;
        font-weight: 800;
        color: var(--text-900);
        margin: 0;
    }

    .export-card__title-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .65rem;
        background: rgba(232,163,61,.12);
        color: var(--gold-500);
        flex: 0 0 auto;
    }

    .export-card__subtitle {
        margin: .3rem 0 0;
        color: var(--text-500);
        font-size: .8rem;
        line-height: 1.5;
    }

    .export-field-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-50);
    }

    .export-field-toolbar__left,
    .export-field-toolbar__right {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .export-count {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .65rem;
        border-radius: 999px;
        background: var(--surface-100);
        border: 1px solid var(--surface-border);
        color: var(--text-700);
        font-size: .75rem;
        font-weight: 700;
    }

    .export-count strong {
        color: var(--text-900);
    }

    .export-mini-btn {
        border: 1px solid var(--surface-border);
        background: var(--surface-0);
        color: var(--text-700);
        border-radius: .55rem;
        padding: .38rem .65rem;
        font-size: .74rem;
        font-weight: 700;
        transition: .18s ease;
    }

    .export-mini-btn:hover {
        border-color: var(--gold-500);
        color: var(--text-900);
        background: var(--surface-100);
    }

    .export-field-groups {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.25rem 1.25rem;
    }

    .export-field-group {
        min-width: 0;
        border: 1px solid var(--surface-border);
        border-radius: .8rem;
        background: var(--surface-50);
        overflow: hidden;
    }

    .export-field-group__heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
        padding: .7rem .8rem;
        border-bottom: 1px solid var(--surface-border);
    }

    .export-field-group__heading span:first-child {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: var(--text-900);
        font-size: .75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .055em;
    }

    .export-field-group__count {
        color: var(--text-500);
        font-size: .7rem;
        font-weight: 700;
    }

    .export-field-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .55rem;
        padding: .7rem;
    }

    .export-field-item {
        position: relative;
        display: flex;
        align-items: center;
        gap: .6rem;
        min-width: 0;
        min-height: 52px;
        padding: .6rem .7rem;
        border: 1px solid var(--surface-border);
        border-radius: .7rem;
        background: var(--surface-0);
        color: var(--text-700);
        cursor: pointer;
        user-select: none;
        transition: border-color .16s ease, background .16s ease, transform .16s ease, box-shadow .16s ease;
    }

    .export-field-item:hover {
        border-color: rgba(232,163,61,.55);
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(0,0,0,.08);
    }

    .export-field-item.is-checked {
        border-color: rgba(232,163,61,.7);
        background: rgba(232,163,61,.08);
        color: var(--text-900);
    }

    .export-field-item__check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .export-field-item__box {
        width: 20px;
        height: 20px;
        flex: 0 0 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .38rem;
        border: 1.5px solid var(--surface-border);
        background: var(--surface-0);
        color: transparent;
        font-size: .72rem;
        transition: .16s ease;
    }

    .export-field-item.is-checked .export-field-item__box {
        border-color: var(--gold-500);
        background: var(--gold-500);
        color: #172033;
    }

    .export-field-item__icon {
        width: 29px;
        height: 29px;
        flex: 0 0 29px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .5rem;
        background: var(--surface-100);
        color: var(--text-500);
        font-size: .85rem;
    }

    .export-field-item.is-checked .export-field-item__icon {
        color: var(--gold-500);
        background: rgba(232,163,61,.12);
    }

    .export-field-item__label {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: .77rem;
        font-weight: 700;
    }

    .export-field-item.is-filtered {
        display: none;
    }

    .export-search {
        width: min(250px, 100%);
        position: relative;
    }

    .export-search i {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-500);
        pointer-events: none;
    }

    .export-search input {
        width: 100%;
        height: 34px;
        border: 1px solid var(--surface-border);
        border-radius: .55rem;
        background: var(--surface-0);
        color: var(--text-900);
        padding: .35rem .65rem .35rem 2rem;
        font-size: .75rem;
        outline: none;
    }

    .export-search input:focus {
        border-color: var(--gold-500);
        box-shadow: 0 0 0 .18rem rgba(232,163,61,.12);
    }

    /* Preview */
    .export-preview-card {
        overflow: visible;
    }

    .export-preview-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        padding: .8rem 1.25rem;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-50);
    }

    .export-file-pill {
        min-width: 0;
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        color: var(--text-700);
        font-size: .78rem;
        font-weight: 700;
    }

    .export-file-pill i {
        color: #63c174;
        font-size: 1rem;
    }

    .export-file-pill span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .export-preview-stats {
        display: flex;
        align-items: center;
        gap: .45rem;
        flex-wrap: wrap;
    }

    .export-stat {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .6rem;
        border-radius: 999px;
        border: 1px solid var(--surface-border);
        background: var(--surface-0);
        color: var(--text-500);
        font-size: .7rem;
        font-weight: 700;
    }

    .export-stat i {
        color: var(--gold-500);
    }

    .export-grid-shell {
        position: relative;
        margin: 1rem;
        border: 1px solid var(--surface-border);
        border-radius: .8rem;
        background: var(--surface-0);
        overflow: hidden;
    }

    .export-grid-scroll {
        width: 100%;
        max-height: 480px;
        overflow: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
    }

    .export-grid-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .export-grid-scroll::-webkit-scrollbar-thumb {
        background: var(--surface-border);
        border-radius: 99px;
    }

    .export-preview {
        width: max-content;
        min-width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: auto;
        font-size: .76rem;
    }

    .export-preview th,
    .export-preview td {
        border-right: 1px solid var(--surface-border);
        border-bottom: 1px solid var(--surface-border);
        padding: .72rem .8rem;
        text-align: left;
        vertical-align: middle;
    }

    .export-preview th:last-child,
    .export-preview td:last-child {
        border-right: 0;
    }

    .export-preview thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        min-width: 125px;
        background: #17233b;
        color: #fff;
        font-size: .67rem;
        font-weight: 800;
        letter-spacing: .045em;
        white-space: normal;
        line-height: 1.3;
        box-shadow: 0 2px 0 rgba(0,0,0,.12);
    }

    [data-theme="light"] .export-preview thead th {
        background: #14213d;
    }

    .export-preview tbody td {
        min-width: 125px;
        color: var(--text-700);
        background: var(--surface-0);
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.4;
    }

    .export-preview tbody tr:nth-child(even) td {
        background: var(--surface-50);
    }

    .export-preview tbody tr:hover td {
        background: rgba(232,163,61,.055);
    }

    .export-preview .ep-num {
        min-width: 54px;
        width: 54px;
        text-align: center;
        color: var(--text-500);
        font-weight: 800;
        background: var(--surface-50);
        position: sticky;
        left: 0;
        z-index: 4;
    }

    .export-preview thead .ep-num {
        background: #0f1a2d;
        color: #fff;
        z-index: 7;
    }

    .export-preview td[data-field="email"],
    .export-preview th[data-field="email"] {
        min-width: 230px;
    }

    .export-preview td[data-field="school_name"],
    .export-preview th[data-field="school_name"] {
        min-width: 220px;
    }

    .export-preview td[data-field="course"],
    .export-preview th[data-field="course"] {
        min-width: 240px;
    }

    .export-preview td[data-field="status"],
    .export-preview th[data-field="status"] {
        min-width: 180px;
    }

    .export-preview .ep-hidden {
        display: none !important;
    }

    .export-preview-empty {
        display: none;
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--text-500);
    }

    .export-preview-empty.is-visible {
        display: block;
    }

    .export-preview-empty i {
        display: block;
        font-size: 1.8rem;
        margin-bottom: .55rem;
        color: var(--gold-500);
    }

    .export-preview-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        padding: .85rem 1.25rem;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-50);
    }

    .export-selection-summary {
        display: flex;
        align-items: center;
        gap: .45rem;
        color: var(--text-500);
        font-size: .75rem;
    }

    .export-selection-summary strong {
        color: var(--text-900);
    }

    @media (max-width: 991.98px) {
        .export-field-groups {
            grid-template-columns: 1fr;
        }

        .export-field-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .export-grid-scroll {
            max-height: 420px;
        }
    }

    @media (max-width: 575.98px) {
        .export-field-grid {
            grid-template-columns: 1fr;
        }

        .export-field-toolbar,
        .export-preview-meta,
        .export-preview-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .export-search {
            width: 100%;
        }

        .export-grid-shell {
            margin: .75rem;
        }
    }
</style>

<form method="POST" action="{{ route('admin.export.download') }}" id="export-form">
    @csrf

    @foreach ($ids as $id)
        <input type="hidden" name="ids[]" value="{{ $id }}">
    @endforeach

    <div class="export-builder">

        {{-- =====================================================
             STEP 1 — FIELD PICKER
             ===================================================== --}}
        <section class="export-card">
            <div class="export-card__header">
                <h2 class="export-card__title">
                    <span class="export-card__title-icon"><i class="bi bi-ui-checks-grid"></i></span>
                    Choose Fields
                </h2>
                <p class="export-card__subtitle">
                    Select the information you want to include in the Excel file.
                    The preview below updates instantly.
                </p>
            </div>

            <div class="export-field-toolbar">
                <div class="export-field-toolbar__left">
                    <span class="export-count">
                        <i class="bi bi-check2-circle"></i>
                        <strong id="field-count">0</strong> of {{ count($fieldOptions) }} selected
                    </span>

                    <button type="button" class="export-mini-btn" id="select-all-btn">
                        <i class="bi bi-check-all me-1"></i>Select all
                    </button>

                    <button type="button" class="export-mini-btn" id="clear-all-btn">
                        <i class="bi bi-x-circle me-1"></i>Clear all
                    </button>
                </div>

                <div class="export-field-toolbar__right">
                    <label class="export-search" aria-label="Search fields">
                        <i class="bi bi-search"></i>
                        <input type="search" id="field-search" placeholder="Search fields...">
                    </label>

                    <label class="d-inline-flex align-items-center gap-2 small fw-semibold text-muted-soft" style="cursor:pointer;">
                        <input type="checkbox" id="select-all" class="form-check-input mt-0" checked>
                        Select all
                    </label>
                </div>
            </div>

            <div class="export-field-groups">
                @foreach ($fieldGroups as $groupName => $groupKeys)
                    @php
                        $groupFields = collect($groupKeys)
                            ->filter(fn ($key) => isset($fieldOptions[$key]))
                            ->mapWithKeys(fn ($key) => [$key => $fieldOptions[$key]]);
                    @endphp

                    <div class="export-field-group" data-field-group>
                        <div class="export-field-group__heading">
                            <span>
                                @if ($groupName === 'Identity')
                                    <i class="bi bi-person-vcard"></i>
                                @elseif ($groupName === 'Contact')
                                    <i class="bi bi-person-lines-fill"></i>
                                @elseif ($groupName === 'Academic')
                                    <i class="bi bi-mortarboard"></i>
                                @else
                                    <i class="bi bi-geo-alt"></i>
                                @endif
                                {{ $groupName }}
                            </span>
                            <span class="export-field-group__count">
                                <span data-group-count>0</span> / {{ $groupFields->count() }}
                            </span>
                        </div>

                        <div class="export-field-grid">
                            @foreach ($groupFields as $key => $label)
                                <label class="export-field-item is-checked" data-field-item data-label="{{ strtolower($label) }}">
                                    <input
                                        type="checkbox"
                                        class="export-field-item__check field-check"
                                        name="fields[]"
                                        value="{{ $key }}"
                                        checked
                                    >
                                    <span class="export-field-item__box">
                                        <i class="bi bi-check2"></i>
                                    </span>
                                    <span class="export-field-item__icon">
                                        <i class="bi {{ $fieldIcons[$key] ?? 'bi-check2-square' }}"></i>
                                    </span>
                                    <span class="export-field-item__label">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- =====================================================
             STEP 2 — LIVE PREVIEW
             ===================================================== --}}
        <section class="export-card export-preview-card">
            <div class="export-card__header">
                <h2 class="export-card__title">
                    <span class="export-card__title-icon"><i class="bi bi-eye"></i></span>
                    Live Preview
                </h2>
                <p class="export-card__subtitle">
                    This preview shows the same fields and applicant rows that will be exported.
                    Scroll horizontally to view additional columns.
                </p>
            </div>

            <div class="export-preview-meta">
                <div class="export-file-pill">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>applicants_{{ now()->format('Y-m-d_His') }}.xls</span>
                </div>

                <div class="export-preview-stats">
                    <span class="export-stat">
                        <i class="bi bi-columns-gap"></i>
                        <strong id="col-count">{{ count($fieldOptions) }}</strong> columns
                    </span>
                    <span class="export-stat">
                        <i class="bi bi-people"></i>
                        <strong>{{ $applicants->count() }}</strong> applicants
                    </span>
                    <span class="export-stat">
                        <i class="bi bi-arrow-left-right"></i>
                        Horizontal scroll enabled
                    </span>
                </div>
            </div>

            <div class="export-grid-shell">
                <div class="export-grid-scroll">
                    <table class="export-preview" id="export-preview-table">
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

                    <div class="export-preview-empty" id="preview-empty">
                        <i class="bi bi-layout-three-columns"></i>
                        <strong>No fields selected</strong>
                        <div class="small mt-1">Select at least one field above to populate the preview.</div>
                    </div>
                </div>
            </div>

            <div class="export-preview-footer">
                <div class="export-selection-summary">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        <strong id="footer-field-count">{{ count($fieldOptions) }}</strong> fields selected
                        · {{ $applicants->count() }} applicant{{ $applicants->count() === 1 ? '' : 's' }}
                    </span>
                </div>

                <button type="submit" class="btn btn-navy d-inline-flex align-items-center gap-2" id="download-btn">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Download Excel
                </button>
            </div>
        </section>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checks = Array.from(document.querySelectorAll('.field-check'));
    const selectAll = document.getElementById('select-all');
    const selectAllBtn = document.getElementById('select-all-btn');
    const clearAllBtn = document.getElementById('clear-all-btn');
    const search = document.getElementById('field-search');

    const countLabel = document.getElementById('field-count');
    const footerCount = document.getElementById('footer-field-count');
    const colCountLabel = document.getElementById('col-count');
    const previewEmpty = document.getElementById('preview-empty');
    const downloadBtn = document.getElementById('download-btn');

    function updateFieldCards() {
        checks.forEach(function (check) {
            const card = check.closest('.export-field-item');
            if (card) {
                card.classList.toggle('is-checked', check.checked);
            }
        });

        document.querySelectorAll('[data-field-group]').forEach(function (group) {
            const groupChecks = Array.from(group.querySelectorAll('.field-check'));
            const selected = groupChecks.filter(c => c.checked).length;
            const counter = group.querySelector('[data-group-count]');
            if (counter) counter.textContent = selected;
        });
    }

    function updatePreview() {
        const selected = checks.filter(c => c.checked);
        const count = selected.length;

        if (countLabel) countLabel.textContent = count;
        if (footerCount) footerCount.textContent = count;
        if (colCountLabel) colCountLabel.textContent = count;

        if (selectAll) {
            selectAll.checked = count === checks.length;
            selectAll.indeterminate = count > 0 && count < checks.length;
        }

        document.querySelectorAll('[data-field]').forEach(function (el) {
            const field = el.getAttribute('data-field');
            if (!field) return;

            const check = checks.find(c => c.value === field);
            el.classList.toggle('ep-hidden', !check || !check.checked);
        });

        if (previewEmpty) {
            previewEmpty.classList.toggle('is-visible', count === 0);
        }

        if (downloadBtn) {
            downloadBtn.disabled = count === 0;
            downloadBtn.style.opacity = count === 0 ? '.55' : '1';
            downloadBtn.style.pointerEvents = count === 0 ? 'none' : 'auto';
        }

        updateFieldCards();
    }

    function setAll(checked) {
        checks.forEach(function (check) {
            check.checked = checked;
        });
        updatePreview();
    }

    function applySearch() {
        const term = (search ? search.value : '').trim().toLowerCase();

        document.querySelectorAll('[data-field-item]').forEach(function (item) {
            const label = item.getAttribute('data-label') || '';
            item.classList.toggle('is-filtered', term !== '' && !label.includes(term));
        });

        document.querySelectorAll('[data-field-group]').forEach(function (group) {
            const visibleItems = group.querySelectorAll('[data-field-item]:not(.is-filtered)').length;
            group.style.display = visibleItems === 0 ? 'none' : '';
        });
    }

    checks.forEach(function (check) {
        check.addEventListener('change', updatePreview);
    });

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            setAll(selectAll.checked);
        });
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            setAll(true);
        });
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            setAll(false);
        });
    }

    if (search) {
        search.addEventListener('input', applySearch);
    }

    updatePreview();
    applySearch();
});
</script>
@endpush
