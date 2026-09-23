@extends('layouts.app')
@section('title', 'Master List')
@section('subtitle', 'Complete record of every applicant with their journey through the scholarship pipeline')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h5 fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:36px;height:36px;font-size:1rem;flex-shrink:0;">
                <i class="bi bi-box2-fill"></i>
            </span>
            Master List Archive
        </h2>
        <p class="small text-muted-soft mb-0">
            The permanent record of every applicant — including archived student accounts — with their full pipeline journey.
        </p>
    </div>
    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
        <a href="{{ route('admin.master-list.index') }}" class="btn btn-sm btn-ghost text-muted-soft d-inline-flex align-items-center gap-1 flex-shrink-0">
            <i class="bi bi-x-lg"></i> Clear filters
        </a>
    @endif
</div>

<!-- Filter bar -->
<div class="admin-panel mb-4">
    <div class="admin-panel__body">
        <form method="GET" action="{{ route('admin.master-list.index') }}" id="masterListForm" class="row g-3 align-items-end">
            <div class="col-12 col-md-6 col-xl-2">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-calendar-week me-1"></i>Account Created Date</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="created_date" value="{{ $filters['created_date'] ?? '' }}" class="form-control border-start-0">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-tag me-1"></i>Applicant Type &amp; Archive</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-funnel"></i></span>
                    <select name="program_type" class="form-select form-select-sm border-start-0">
                        <option value="" {{ !$filters['program_type'] && $filters['archive_status'] === 'all' ? 'selected' : '' }}>All</option>
                        <optgroup label="Program Type">
                            <option value="new" {{ $filters['program_type'] === 'new' ? 'selected' : '' }}>New Applicant</option>
                            <option value="renewal" {{ $filters['program_type'] === 'renewal' ? 'selected' : '' }}>Renewal</option>
                        </optgroup>
                        <optgroup label="Archive Status">
                            <option value="active" {{ $filters['archive_status'] === 'active' ? 'selected' : '' }}>Active accounts</option>
                            <option value="archived" {{ $filters['archive_status'] === 'archived' ? 'selected' : '' }}>Archived accounts</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-2">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-flag me-1"></i>Status</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-check-circle"></i></span>
                    <select name="status" class="form-select form-select-sm border-start-0" onchange="this.form.submit()">
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['status'] ?? 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label class="form-label small fw-semibold mb-1"><i class="bi bi-search me-1"></i>Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, school, email, contact..." class="form-control border-start-0">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-navy flex-fill d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-filter"></i> Apply
                </button>
                @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
                    <a href="{{ route('admin.master-list.index') }}" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #14213D, #1a2d50);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-box2-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['total']) }}</p>
                <p class="admin-kpi__label text-white-50">Total Records</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #2c65ac, #1f4d85);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-people-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['active']) }}</p>
                <p class="admin-kpi__label text-white-50">Active Accounts</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #E8A33D, #d18a1f);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.2); color:#fff;"><i class="bi bi-hourglass-split"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['in_progress']) }}</p>
                <p class="admin-kpi__label text-white-50">In Progress</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #1E6B3C, #15512d);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-cash-coin"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['released']) }}</p>
                <p class="admin-kpi__label text-white-50">Scholarship Released</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="admin-kpi admin-kpi--gradient" style="background: linear-gradient(135deg, #55606e, #3d4653);">
            <span class="admin-kpi__icon" style="background: rgba(255,255,255,.15); color:#fff;"><i class="bi bi-archive-fill"></i></span>
            <div>
                <p class="admin-kpi__value text-white">{{ number_format($summary['archived']) }}</p>
                <p class="admin-kpi__label text-white-50">Archived Accounts</p>
            </div>
        </div>
    </div>
</div>

<!-- Master table -->
<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2 class="h6 fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-archive"></i> Archived Records
                <span class="badge-soft-navy ms-1">{{ number_format($applicants->count()) }}</span>
            </h2>
            <p class="small text-muted-soft mb-0 mt-1">Click any row to open the full applicant record in the side panel.</p>
        </div>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($applicants->isEmpty())
            <div class="empty-state py-5">
                <span class="empty-state__icon"><i class="bi bi-clipboard-x"></i></span>
                <h6>No applicants found</h6>
                <p>
                    @if (!empty($filters['created_date']) || !empty($filters['program_type']) || !empty($filters['status']) || !empty($filters['search']) || $filters['archive_status'] !== 'all')
                        No records match the current filters. Try a different date or clear the filters.
                    @else
                        The archive is empty — no applicant records yet.
                    @endif
                </p>
            </div>
        @else
        <div class="admin-table-scroll admin-table-scroll--y">
            <table class="table admin-table admin-table--compact admin-table--master mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 col-ml-applicant">Applicant</th>
                        <th class="col-ml-type">Type</th>
                        <th class="col-ml-school">School &amp; Program</th>
                        <th class="col-ml-created">Account Created</th>
                        <th class="col-ml-payout">Payouts</th>
                        <th class="col-ml-status">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                    @php
                        $type = $applicant->program_type === 'renewal' ? 'renewal' : 'new';
                        $payoutTotal = $applicant->payouts->sum('amount');
                        $accountCreated = $applicant->user?->created_at ?? $applicant->created_at;
                        $archived = (bool) $applicant->deleted_at;
                    @endphp
                    <tr class="applicant-row {{ $archived ? 'ml-archived' : '' }}"
                        role="button"
                        tabindex="0"
                        data-applicant-id="{{ $applicant->id }}"
                        title="Click to view full record">
                        <td class="ps-3 col-ml-applicant">
                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                <span class="avatar-wrap flex-shrink-0">
                                    <span class="admin-avatar">
                                        {{ strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1)) }}
                                    </span>
                                    @if ($applicant->user?->isOnline())
                                        <span class="online-dot" title="Online now"></span>
                                    @endif
                                </span>
                                <div style="min-width:0;">
                                    <div class="applicant-name fw-semibold admin-table__name">{{ $applicant->first_name }} {{ $applicant->last_name }}</div>
                                    <span class="text-muted-soft admin-table__meta" style="font-size:.72rem;">
                                        {{ $applicant->user?->email ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="col-ml-type">
                            <span class="badge {{ $type === 'renewal' ? 'badge-soft-gold' : 'badge-soft-navy' }}">
                                {{ $type === 'renewal' ? 'Renewal' : 'New' }}
                            </span>
                        </td>
                        <td class="col-ml-school">
                            <div class="admin-table__meta text-truncate" style="max-width: 100%;">{{ $applicant->school_name ?? '—' }}</div>
                            <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">
                                {{ $applicant->course ?? 'N/A' }}@if ($applicant->year_level)· {{ $applicant->year_level }}@endif
                            </div>
                        </td>
                        <td class="col-ml-created">
                            <div class="small fw-semibold">{{ $accountCreated?->format('M d, Y') ?? '—' }}</div>
                            <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">{{ $accountCreated?->format('h:i A') ?? '' }}</div>
                        </td>
                        <td class="col-ml-payout">
                            @if ($applicant->payouts->count())
                                <div class="fw-semibold small text-success-emphasis">₱{{ number_format((float) $payoutTotal, 2) }}</div>
                                <div class="text-muted-soft admin-table__meta" style="font-size:.72rem;">{{ $applicant->payouts->count() }} release{{ $applicant->payouts->count() > 1 ? 's' : '' }}</div>
                            @else
                                <span class="text-muted-soft small">—</span>
                            @endif
                        </td>
                        <td class="col-ml-status">
                            @if ($archived)
                                <span class="admin-badge-archived">Archived</span>
                            @else
                                <span class="admin-badge {{ $dashboard->statusBadgeClass($applicant->status) }}">{{ $dashboard->statusDisplayLabel($applicant->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SLIDE-OUT DRAWER — contains the detail of ONE applicant.
     Content is injected via JS when the user clicks a row.
     ══════════════════════════════════════════════════════════ --}}
<div class="ml-drawer-backdrop" id="mlDrawerBackdrop"></div>
<div class="ml-drawer" id="mlDrawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Applicant record">
    <div id="mlDrawerContent">
        {{-- Populated over fetch() from admin.master-list.show --}}
    </div>
</div>

{{-- Skeleton shown while a record is being fetched --}}
<template id="mlDrawerLoading">
    <div class="ml-drawer__header">
        <button type="button" class="ml-drawer__close" data-ml-close title="Close panel">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="ml-drawer__identity">
            <div class="ml-skeleton ml-skeleton--avatar"></div>
            <div style="flex:1; min-width:0;">
                <div class="ml-skeleton ml-skeleton--line" style="width:60%;"></div>
                <div class="ml-skeleton ml-skeleton--line" style="width:35%; height:.7rem;"></div>
            </div>
        </div>
    </div>
    <div class="ml-drawer__body">
        <div class="ml-skeleton ml-skeleton--block"></div>
        <div class="ml-skeleton ml-skeleton--block"></div>
        <div class="ml-skeleton ml-skeleton--block" style="height:120px;"></div>
        <p class="ml-card2__empty mt-3"><i class="bi bi-hourglass-split"></i> Loading record...</p>
    </div>
</template>

{{-- The error state is built in JS so it can never itself fail to render --}}


@push('styles')

<style>
    /*
     * Master List drawer fixes
     * ------------------------
     * The applicant record is loaded dynamically, so these rules are scoped
     * to the drawer and only override the styles needed for the detail view.
     */
    /* Prevent the drawer's flex column from shrinking detail cards into thin lines. */
    #mlDrawer .ml-drawer__body > *,
    #mlDrawer .ml-info-section,
    #mlDrawer .ml-drawer-pipeline,
    #mlDrawer .ml-section-block {
        flex: 0 0 auto !important;
        flex-shrink: 0 !important;
        min-width: 0;
    }

    #mlDrawer .ml-info-section {
        min-height: 80px;
    }

    #mlDrawer .ml-section-block {
        min-height: 56px;
    }

    #mlDrawer .ml-info-grid {
        display: grid;
        width: 100%;
        height: auto;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    #mlDrawer .ml-info-grid__item {
        display: block;
        width: 100%;
        height: auto;
        min-height: 58px;
    }

    #mlDrawer .ml-info-grid__label {
        display: block;
        height: auto;
        margin-bottom: .3rem;
        line-height: 1.2;
    }

    #mlDrawer .ml-info-grid__value {
        display: block !important;
        position: relative;
        width: 100%;
        height: auto !important;
        min-height: 18px;
        margin: 0;
        padding: 0;
        line-height: 1.4 !important;
        font-size: .88rem;
        font-weight: 600;
        color: var(--text-900) !important;
        visibility: visible !important;
        opacity: 1 !important;
        overflow: visible !important;
        white-space: normal !important;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    #mlDrawer .ml-info-grid__item--full {
        grid-column: 1 / -1;
    }

    #mlDrawer .ml-section-block {
        width: 100%;
        height: auto;
        min-height: 0;
        overflow: hidden;
    }

    #mlDrawer .ml-section-block__toggle {
        position: relative;
        z-index: 2;
        width: 100%;
        min-height: 56px;
        height: auto;
        cursor: pointer;
    }

    #mlDrawer .ml-section-block__content {
        width: 100%;
        height: auto;
        min-height: 0;
        padding: 1rem 1.15rem;
        overflow: visible;
    }

    #mlDrawer .ml-section-block .collapse:not(.show) {
        display: none;
    }

    #mlDrawer .ml-section-block .collapse.show {
        display: block !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    /* Ensure a toggled-off dynamically loaded panel stays hidden. */
    #mlDrawer .ml-drawer-collapse[hidden] {
        display: none !important;
        height: 0 !important;
        max-height: 0 !important;
        overflow: hidden !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }

    #mlDrawer .ml-section-block__toggle[aria-expanded="true"] .ml-section-block__chevron {
        transform: rotate(180deg);
    }

    #mlDrawer .ml-section-block .ml-card2__list {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: auto;
        margin: 0;
        padding: 0;
    }

    #mlDrawer .ml-section-block .ml-card2__list > div {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        min-height: 30px;
        height: auto;
        gap: 1rem;
    }

    #mlDrawer .ml-section-block .ml-card2__list dt,
    #mlDrawer .ml-section-block .ml-card2__list dd {
        display: block;
        height: auto;
        min-height: 18px;
        line-height: 1.4;
        visibility: visible;
        opacity: 1;
    }

    #mlDrawer .ml-section-block .ml-card2__list dt {
        flex: 1;
    }

    #mlDrawer .ml-section-block .ml-card2__list dd {
        flex: 1;
        margin: 0;
        text-align: right;
        color: var(--text-900) !important;
    }

    #mlDrawer .ml-card2__empty {
        display: flex;
        align-items: center;
        gap: .5rem;
        width: 100%;
        min-height: 35px;
    }

    @media (max-width: 700px) {
        #mlDrawer .ml-info-grid {
            grid-template-columns: 1fr;
        }

        #mlDrawer .ml-info-grid__item--full {
            grid-column: auto;
        }

        #mlDrawer .ml-section-block .ml-card2__list > div {
            flex-direction: column;
            gap: .25rem;
        }

        #mlDrawer .ml-section-block .ml-card2__list dd {
            text-align: left;
        }
    }
</style>
@endpush

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var drawer = document.getElementById('mlDrawer');
        var backdrop = document.getElementById('mlDrawerBackdrop');
        var content = document.getElementById('mlDrawerContent');
        var loadingTpl = document.getElementById('mlDrawerLoading');

        if (!drawer || !backdrop || !content) {
            console.error('[master-list] drawer markup missing; aborting.');
            return;
        }

        var cache = {};
        var requestToken = 0;
        var inFlight = null;

        var activeApplicantId = null;
        var activeRow = null;
        var lastFocused = null;

        var recordUrlTemplate = @json(route('admin.master-list.show', ['applicant' => 0]));
        // route() renders the id as a real segment; swap the trailing 0 for the
        // applicant we actually want.
        recordUrlTemplate = recordUrlTemplate.replace(/0$/, '');

        function recordUrl(id) {
            return recordUrlTemplate + encodeURIComponent(id);
        }

        function wire() {
            content.querySelectorAll('[data-ml-close]').forEach(function (btn) {
                btn.addEventListener('click', closeDrawer);
            });

            var retry = content.querySelector('[data-ml-retry]');
            if (retry) {
                retry.addEventListener('click', function () {
                    if (activeApplicantId !== null) load(activeApplicantId, true);
                });
            }

            // The record HTML is injected dynamically, so Bootstrap's collapse
            // data API may not be wired to these buttons. Handle the sections
            // directly so they work every time a record is loaded.
            content.querySelectorAll('.ml-section-block__toggle').forEach(function (button) {
                var targetSelector = button.getAttribute('data-bs-target') || button.getAttribute('data-target');
                if (!targetSelector) return;

                var target = null;
                try {
                    target = content.querySelector(targetSelector);
                } catch (e) {
                    console.warn('[master-list] Invalid collapse target:', targetSelector);
                }

                if (!target) {
                    console.warn('[master-list] Collapse target not found:', targetSelector);
                    return;
                }

                var initiallyOpen = target.classList.contains('show');
                setSectionState(button, target, initiallyOpen);

                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var isOpen = target.classList.contains('show');
                    setSectionState(button, target, !isOpen);
                });
            });
        }

        function setSectionState(button, target, open) {
            if (open) {
                target.classList.add('show');
                target.removeAttribute('hidden');
                target.style.display = 'block';
                target.style.height = 'auto';
                target.style.maxHeight = 'none';
                target.style.overflow = 'visible';

                button.setAttribute('aria-expanded', 'true');

                var chevron = button.querySelector('.ml-section-block__chevron');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                target.classList.remove('show');
                target.setAttribute('hidden', '');
                target.style.display = 'none';
                target.style.height = '0px';
                target.style.maxHeight = '0px';
                target.style.overflow = 'hidden';

                button.setAttribute('aria-expanded', 'false');

                var chevron = button.querySelector('.ml-section-block__chevron');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        function render(html) {
            content.innerHTML = html;
            wire();
            var body = content.querySelector('.ml-drawer__body');
            if (body) body.scrollTop = 0;
        }

        function showLoading() {
            if (loadingTpl && loadingTpl.content) {
                content.innerHTML = '';
                content.appendChild(loadingTpl.content.cloneNode(true));
                wire();
            } else {
                content.innerHTML = '<div class="ml-drawer__body"><p class="ml-card2__empty">Loading record...</p></div>';
            }
        }

        // Built with plain DOM rather than a <template> so that the error state
        // can never itself be the thing that fails.
        function showError(message, detail) {
            console.error('[master-list] ' + message, detail || '');

            content.innerHTML = '';

            var header = document.createElement('div');
            header.className = 'ml-drawer__header';
            var close = document.createElement('button');
            close.type = 'button';
            close.className = 'ml-drawer__close';
            close.title = 'Close panel';
            close.innerHTML = '<i class="bi bi-x-lg"></i>';
            close.addEventListener('click', closeDrawer);
            header.appendChild(close);

            var identity = document.createElement('div');
            identity.className = 'ml-drawer__identity';
            var avatar = document.createElement('div');
            avatar.className = 'ml-drawer__avatar';
            avatar.style.background = '#fdecec';
            avatar.style.color = '#c0392b';
            avatar.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
            var nameWrap = document.createElement('div');
            var name = document.createElement('h6');
            name.className = 'ml-drawer__name';
            name.textContent = 'Could not load record';
            nameWrap.appendChild(name);
            identity.appendChild(avatar);
            identity.appendChild(nameWrap);
            header.appendChild(identity);

            var body = document.createElement('div');
            body.className = 'ml-drawer__body';

            var notice = document.createElement('div');
            notice.className = 'ml-record-notice ml-record-notice--error';
            var noticeText = document.createElement('span');
            noticeText.textContent = message;
            notice.appendChild(noticeText);
            body.appendChild(notice);

            if (detail) {
                var pre = document.createElement('p');
                pre.className = 'ml-card2__note';
                pre.textContent = detail;
                body.appendChild(pre);
            }

            var retry = document.createElement('button');
            retry.type = 'button';
            retry.className = 'btn btn-sm btn-navy d-inline-flex align-items-center gap-1';
            retry.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Try again';
            retry.addEventListener('click', function () {
                if (activeApplicantId !== null) load(activeApplicantId, true);
            });
            body.appendChild(retry);

            content.appendChild(header);
            content.appendChild(body);
        }

        function load(applicantId, force) {
            if (!force && cache[applicantId]) {
                render(cache[applicantId]);
                return;
            }

            var token = ++requestToken;
            showLoading();

            // Abort rather than hang indefinitely on a stalled request.
            var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
            if (inFlight) inFlight.abort && inFlight.abort();
            inFlight = controller;

            var timedOut = false;
            var timer = setTimeout(function () {
                timedOut = true;
                if (controller) controller.abort();
            }, 15000);

            var url = recordUrl(applicantId);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                credentials: 'same-origin',
                redirect: 'follow',
                signal: controller ? controller.signal : undefined
            })
                .then(function (response) {
                    clearTimeout(timer);

                    // A redirect to the login page comes back as a 200 full
                    // HTML document, so check where we actually landed.
                    if (response.redirected && /\/login/.test(response.url)) {
                        throw new Error('Your session has expired. Refresh the page and sign in again.');
                    }
                    if (response.status === 401 || response.status === 419) {
                        throw new Error('Your session has expired. Refresh the page and sign in again.');
                    }
                    if (response.status === 403) {
                        throw new Error('You do not have permission to view this record.');
                    }
                    if (response.status === 404) {
                        throw new Error('Record not found. The route may not be registered — try: php artisan route:clear');
                    }
                    if (!response.ok) {
                        return response.text().then(function (body) {
                            var e = new Error('The server returned an error (' + response.status + ').');
                            e.detail = body.slice(0, 400);
                            throw e;
                        });
                    }
                    return response.text();
                })
                .then(function (html) {
                    if (token !== requestToken) return;
                    if (!html || !html.trim()) {
                        throw new Error('The server returned an empty record.');
                    }
                    cache[applicantId] = html;
                    render(html);
                })
                .catch(function (err) {
                    clearTimeout(timer);
                    if (token !== requestToken) return;
                    if (err && err.name === 'AbortError' && !timedOut) return; // superseded

                    var msg = timedOut
                        ? 'The request timed out after 15 seconds.'
                        : (err && err.message ? err.message : 'Something went wrong loading this record.');

                    try {
                        showError(msg, (err && err.detail) ? err.detail : ('Request: ' + url));
                    } catch (renderErr) {
                        // Absolute last resort — never leave the skeleton up.
                        console.error('[master-list] error view failed', renderErr);
                        content.innerHTML = '<div class="ml-drawer__body"><p class="ml-card2__empty">'
                            + 'Could not load this record. See the browser console for details.</p></div>';
                    }
                });
        }

        function openDrawer(applicantId, row) {
            if (activeApplicantId === applicantId && drawer.classList.contains('is-open')) {
                closeDrawer();
                return;
            }

            lastFocused = row || document.activeElement;
            activeApplicantId = applicantId;

            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            backdrop.classList.add('is-open');
            document.body.classList.add('ml-drawer-open');

            if (activeRow) activeRow.classList.remove('is-active');
            var target = row || document.querySelector('[data-applicant-id="' + applicantId + '"]');
            if (target) {
                target.classList.add('is-active');
                activeRow = target;
            }

            load(applicantId, false);
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            backdrop.classList.remove('is-open');
            document.body.classList.remove('ml-drawer-open');

            if (activeRow) {
                activeRow.classList.remove('is-active');
                activeRow = null;
            }
            activeApplicantId = null;
            requestToken++;
            if (inFlight && inFlight.abort) inFlight.abort();
            inFlight = null;

            if (lastFocused && typeof lastFocused.focus === 'function') {
                lastFocused.focus();
                lastFocused = null;
            }
        }

        document.querySelectorAll('.applicant-row[data-applicant-id]').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('a, button, input, select')) return;
                openDrawer(this.dataset.applicantId, this);
            });
            row.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openDrawer(this.dataset.applicantId, this);
                }
            });
        });

        backdrop.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });
    });
</script>
@endpush