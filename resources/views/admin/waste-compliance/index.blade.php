@extends('layouts.app')
@section('title', 'Waste Compliance')
@section('subtitle', 'Plastic waste submissions per semester')

@section('content')

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Total Submitted</p>
                    <p class="h3 fw-bold mb-0">{{ number_format($totalKg, 1) }} <span class="fs-6 fw-semibold">kg</span></p>
                    <p class="small text-muted-soft mb-0 mt-1">All semesters</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-green"><i class="bi bi-recycle"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Submissions</p>
                    <p class="h3 fw-bold mb-0">{{ number_format($totalSubmissions) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Records on file</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-navy"><i class="bi bi-inbox-fill"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.waste-compliance.index', ['status' => 'compliant']) }}" class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Compliant</p>
                    <p class="h3 fw-bold mb-0 kpi-value-blue">{{ number_format($compliantCount) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">10 kg or more</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-blue"><i class="bi bi-patch-check-fill"></i></span>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.waste-compliance.index', ['status' => 'deficient']) }}" class="admin-kpi-card">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Deficient</p>
                    <p class="h3 fw-bold mb-0 text-danger">{{ number_format($deficientCount) }}</p>
                    <p class="small text-muted-soft mb-0 mt-1">Below 10 kg</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-red"><i class="bi bi-exclamation-octagon-fill"></i></span>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Record a submission -->
    <div class="col-lg-4">
        <div class="admin-panel h-100">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0">Record a submission</h2>
                <p class="small text-muted-soft mb-0">For scholars in orientation or compliance</p>
            </div>
            <div class="admin-panel__body">
                @if ($pendingScholars->isEmpty())
                    <div class="text-center text-muted-soft py-4 small">
                        <i class="bi bi-check2-circle fs-2 d-block mb-2 opacity-50"></i>
                        No scholars are waiting on waste compliance.
                    </div>
                @else
                    <form method="POST" action="#" id="wasteForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="wasteScholar">Scholar</label>
                            <select id="wasteScholar" class="form-select form-select-sm" required
                                    onchange="document.getElementById('wasteForm').action = this.value;">
                                <option value="" disabled selected>Select a scholar…</option>
                                @foreach ($pendingScholars as $scholar)
                                    <option value="{{ route('admin.waste-compliance', $scholar) }}">
                                        {{ $scholar->first_name }} {{ $scholar->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="wasteSemester">Semester</label>
                            <input type="text" id="wasteSemester" name="semester" class="form-control form-control-sm"
                                   placeholder="e.g. 2026-1st" maxlength="20" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="wasteKilos">Kilos submitted</label>
                            <input type="number" step="0.1" min="0" id="wasteKilos" name="kilos_submitted"
                                   class="form-control form-control-sm" required>
                            <div class="form-text">10 kg or more counts as compliant.</div>
                        </div>
                        <button type="submit" class="btn btn-navy btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i> Save record
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Records -->
    <div class="col-lg-8">
        <div class="admin-panel">
            <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
                <h2 class="h6 fw-bold mb-0">Submission records <span class="badge-soft-navy ms-1">{{ $records->count() }}</span></h2>
                <form method="GET" action="{{ route('admin.waste-compliance.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search scholar…" class="form-control border-start-0" style="min-width:180px;">
                    </div>
                    <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                        <option value="all" @selected($status === 'all')>All status</option>
                        <option value="compliant" @selected($status === 'compliant')>Compliant</option>
                        <option value="deficient" @selected($status === 'deficient')>Deficient</option>
                    </select>
                    <select name="semester" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                        <option value="all">All semesters</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem }}" @selected($semester === $sem)>{{ $sem }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="admin-panel__body admin-panel__body--flush">
                @if ($records->isNotEmpty())
                    <div class="admin-table-scroll admin-table-scroll--y" style="max-height: 520px;">
                        <table class="table admin-table admin-table--compact mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Scholar</th>
                                    <th>Semester</th>
                                    <th class="text-end">Kilos</th>
                                    <th>Status</th>
                                    <th class="pe-3">Recorded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $rec)
                                    @php $a = $rec->applicant; @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                                <span class="admin-avatar">{{ strtoupper(substr($a->first_name ?? '', 0, 1) . substr($a->last_name ?? '', 0, 1)) }}</span>
                                                @if ($a)
                                                    <a href="{{ route('applicants.show', $a) }}" class="fw-semibold admin-table__name text-decoration-none">{{ $a->first_name }} {{ $a->last_name }}</a>
                                                @else
                                                    <span class="text-muted-soft">Removed applicant</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $rec->semester }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($rec->kilos_submitted, 1) }} kg</td>
                                        <td>
                                            <span class="{{ $rec->is_compliant ? 'admin-badge-success' : 'admin-badge-danger' }}">
                                                {{ $rec->is_compliant ? 'Compliant' : 'Deficient' }}
                                            </span>
                                        </td>
                                        <td class="pe-3 text-muted-soft">{{ $rec->created_at?->format('M d, Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted-soft py-5">
                        <i class="bi bi-recycle fs-2 d-block mb-2 opacity-50"></i>
                        No waste compliance records match your filters.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
