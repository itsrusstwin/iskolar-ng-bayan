@extends('layouts.app')
@section('title', 'Export Applicants to Excel')
@section('subtitle', 'Step 1 of 2 — choose which applicants to export, in the order you select them')

@section('header_actions')
    <a href="{{ route('admin.applicants.index') }}" class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to applicants
    </a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.export.fields') }}" id="export-form">
    @csrf
    <div id="order-fields"></div>

    <div class="admin-panel">
        <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
            <div>
                <h2 class="h6 fw-bold mb-0">Select Applicants ({{ $applicants->count() }})</h2>
                <p class="small text-muted-soft mb-0">
                    Click a row to add the applicant to the export — <strong>the first one you select is #1, the second is #2, and so on</strong>.
                    Click again to remove. Use the dropdowns below to narrow the list.
                </p>
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

        <div class="admin-panel__body border-bottom py-3">
            <div class="d-flex align-items-end gap-2 flex-wrap">
                <div class="d-flex flex-column gap-1">
                    <label class="form-label small fw-semibold text-muted-soft mb-0" for="filter-school"><i class="bi bi-mortarboard me-1"></i>School</label>
                    <div class="input-group input-group-sm" style="width: 240px;">
                        <span class="input-group-text border-end-0"><i class="bi bi-mortarboard"></i></span>
                        <select id="filter-school" class="form-select form-select-sm border-start-0">
                            <option value="">All schools</option>
                            @foreach ($schools as $school)
                                <option value="{{ strtolower($school) }}">{{ $school }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <label class="form-label small fw-semibold text-muted-soft mb-0" for="filter-course"><i class="bi bi-journal-code me-1"></i>Course</label>
                    <div class="input-group input-group-sm" style="width: 260px;">
                        <span class="input-group-text border-end-0"><i class="bi bi-journal-code"></i></span>
                        <select id="filter-course" class="form-select form-select-sm border-start-0">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ strtolower($course) }}">{{ $course }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <label class="form-label small fw-semibold text-muted-soft mb-0" for="filter-year"><i class="bi bi-layers me-1"></i>Year Level</label>
                    <div class="input-group input-group-sm" style="width: 170px;">
                        <span class="input-group-text border-end-0"><i class="bi bi-layers"></i></span>
                        <select id="filter-year" class="form-select form-select-sm border-start-0">
                            <option value="">All years</option>
                            @foreach ($yearLevels as $year)
                                <option value="{{ strtolower($year) }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="form-label small fw-semibold text-muted-soft mb-0">&nbsp;</span>
                    <button type="button" id="select-shown" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1" disabled title="Select all currently shown applicants — they are added after your existing selection, continuing the order">
                        <i class="bi bi-check-all"></i> <span id="select-shown-label">Select all shown</span>
                    </button>
                </div>
                <span class="small text-muted-soft ms-auto" id="showing-label"></span>
                <a href="#" class="small fw-semibold d-none" id="clear-filters"><i class="bi bi-x-circle me-1"></i>Clear filters</a>
            </div>
        </div>

        <div class="admin-panel__body admin-panel__body--flush" style="max-height: 520px; overflow-y: auto;">
            @if ($applicants->isEmpty())
                <div class="text-center text-muted-soft py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                    No applicants yet.
                </div>
            @else
                <table class="table admin-table admin-table--export mb-0" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 68px;">
                        <col style="width: 16%;">
                        <col style="width: 21%;">
                        <col style="width: 22%;">
                        <col style="width: 12%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 68px;" title="Click order — the order they appear in the export">
                                <i class="bi bi-sort-numeric-down"></i>
                                <span class="visually-hidden">Order</span>
                            </th>
                            <th style="width: 16%;">Name</th>
                            <th style="width: 21%;">Email</th>
                            <th style="width: 22%;">School</th>
                            <th style="width: 12%;">Program</th>
                            <th style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $applicant)
                            <tr class="applicant-row"
                                data-id="{{ $applicant->id }}"
                                role="button"
                                tabindex="0"
                                title="Click to select — order continues after your current selection"
                                data-school="{{ strtolower($applicant->school_name ?? '') }}"
                                data-course="{{ strtolower($applicant->course ?? '') }}"
                                data-year="{{ strtolower($applicant->year_level ?? '') }}">
                                <td class="text-center">
                                    <span class="export-rank d-none"></span>
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
                <span class="small text-muted-soft">
                    Selected: <span class="fw-semibold" id="selected-count">0</span> / {{ $applicants->count() }}
                </span>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted-soft" id="order-hint"><i class="bi bi-sort-numeric-down me-1"></i>Order: —</span>
                    <button type="submit" class="btn btn-navy d-inline-flex align-items-center gap-2">
                        Next: choose fields <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = Array.from(document.querySelectorAll('.applicant-row'));
        const selectAll = document.getElementById('select-all');
        const nextBtns = document.querySelectorAll('#next-btn, .admin-panel__body .btn-navy');
        const countLabel = document.getElementById('selected-count');
        const selectedLabel = document.getElementById('selected-label');
        const orderHint = document.getElementById('order-hint');

        const schoolFilter = document.getElementById('filter-school');
        const courseFilter = document.getElementById('filter-course');
        const yearFilter = document.getElementById('filter-year');
        const showingLabel = document.getElementById('showing-label');
        const clearFilters = document.getElementById('clear-filters');
        const selectShownBtn = document.getElementById('select-shown');
        const selectShownLabel = document.getElementById('select-shown-label');

        // order[0] = first one selected, order[1] = second, etc.
        const order = [];
        const selected = new Set();
        const isRowSelected = row => selected.has(row.dataset.id);

        function toggleRow(row) {
            const id = row.dataset.id;
            if (selected.has(id)) {
                selected.delete(id);
                order.splice(order.indexOf(id), 1);
            } else {
                selected.add(id);
                order.push(id);   // first click -> first in export
            }
        }

        function renderOrder() {
            rows.forEach(r => r.classList.remove('is-selected'));
            rows.forEach(r => r.querySelector('.export-rank').classList.add('d-none'));

            order.forEach((id, idx) => {
                const row = rows.find(r => r.dataset.id === id);
                if (!row) return;
                row.classList.add('is-selected');
                const rank = row.querySelector('.export-rank');
                rank.textContent = idx + 1;
                rank.classList.remove('d-none');
            });
        }

        function update() {
            const count = order.length;
            countLabel.textContent = count;
            selectedLabel.textContent = count + ' selected';
            orderHint.innerHTML = count
                ? '<i class="bi bi-sort-numeric-down me-1"></i>Order: ' + order.join(', ')
                : '<i class="bi bi-sort-numeric-down me-1"></i>Order: —';
            nextBtns.forEach(btn => { btn.disabled = count === 0; });
            if (selectAll) selectAll.checked = rows.length > 0 && count === rows.length;
            applyFilters();
        }

        function setAll(checked) {
            selected.clear();
            order.length = 0;
            if (checked) {
                rows.forEach(r => { selected.add(r.dataset.id); order.push(r.dataset.id); });
            }
            renderOrder();
            update();
        }

        function applyFilters() {
            const s = schoolFilter.value, c = courseFilter.value, y = yearFilter.value;
            let shown = 0;
            rows.forEach(r => {
                const ok = (!s || r.dataset.school === s)
                    && (!c || (r.dataset.course || '').indexOf(c) !== -1)
                    && (!y || r.dataset.year === y);
                r.classList.toggle('d-none', !ok);
                if (ok) shown++;
            });
            showingLabel.textContent = shown + ' of ' + rows.length + ' applicants';
            clearFilters.classList.toggle('d-none', !s && !c && !y);

            // How many visible applicants are not yet selected; the button
            // appends those after the current selection.
            const visibleUnselected = rows.filter(r =>
                !r.classList.contains('d-none') && !isRowSelected(r)
            ).length;
            selectShownBtn.disabled = visibleUnselected === 0;
            selectShownLabel.textContent = visibleUnselected > 0
                ? 'Select all shown (' + visibleUnselected + ')'
                : 'Select all shown';
        }

        function selectShown() {
            // Visible applicants, in list order, appended after existing selection
            rows.forEach(r => {
                if (r.classList.contains('d-none') || isRowSelected(r)) return;
                selected.add(r.dataset.id);
                order.push(r.dataset.id);
            });
            renderOrder();
            update();
        }

        rows.forEach(r => {
            r.addEventListener('click', function () {
                toggleRow(r);
                renderOrder();
                update();
            });
            r.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleRow(r);
                    renderOrder();
                    update();
                }
            });
        });
        if (selectAll) selectAll.addEventListener('change', function () { setAll(selectAll.checked); });

        [schoolFilter, courseFilter, yearFilter].forEach(f => f.addEventListener('change', applyFilters));
        if (clearFilters) clearFilters.addEventListener('click', function (e) {
            e.preventDefault();
            schoolFilter.value = courseFilter.value = yearFilter.value = '';
            applyFilters();
            clearFilters.classList.add('d-none');
        });
        if (selectShownBtn) selectShownBtn.addEventListener('click', selectShown);

        // Send the applicant ids in click order (first selected = first in file)
        document.getElementById('export-form').addEventListener('submit', function () {
            const container = document.getElementById('order-fields');
            order.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                container.appendChild(input);
            });
        });

        renderOrder();
        update();
        applyFilters();
    });
</script>
@endpush