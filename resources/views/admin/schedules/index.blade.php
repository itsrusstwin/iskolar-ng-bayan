@extends('layouts.app')
@section('title', 'Upcoming Schedules')
@section('subtitle', 'Qualifying exams and orientations')

@section('header_actions')
    <a href="{{ route('admin.applicants.index') }}" class="btn btn-navy btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-calendar-plus"></i> Set schedule
    </a>
@endsection

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Upcoming exams</p>
                    <p class="h3 fw-bold mb-0">{{ $stats['upcoming_exams'] }}</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-navy"><i class="bi bi-pencil-square"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Upcoming orientations</p>
                    <p class="h3 fw-bold mb-0">{{ $stats['upcoming_orientations'] }}</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-gold"><i class="bi bi-mortarboard"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Today</p>
                    <p class="h3 fw-bold mb-0 kpi-value-blue">{{ $stats['today'] }}</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--grad-blue"><i class="bi bi-calendar-check"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="admin-kpi-card h-100">
            <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                    <p class="small text-muted-soft mb-1">Already held</p>
                    <p class="h3 fw-bold mb-0">{{ $stats['past'] }}</p>
                </div>
                <span class="admin-kpi-icon admin-kpi-icon--muted"><i class="bi bi-clock-history"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="admin-panel">
    <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
        <h2 class="h6 fw-bold mb-0">Schedule <span class="badge-soft-navy ms-1">{{ $slots->count() }}</span></h2>
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="input-group input-group-sm">
                <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search applicant…" class="form-control border-start-0" style="min-width:180px;">
            </div>
            <select name="type" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                <option value="all" @selected($type === 'all')>Exams &amp; orientations</option>
                <option value="exam" @selected($type === 'exam')>Exams only</option>
                <option value="orientation" @selected($type === 'orientation')>Orientations only</option>
            </select>
            <select name="when" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                <option value="upcoming" @selected($when === 'upcoming')>Upcoming</option>
                <option value="past" @selected($when === 'past')>Past</option>
                <option value="all" @selected($when === 'all')>All dates</option>
            </select>
        </form>
    </div>
    <div class="admin-panel__body admin-panel__body--flush">
        @if ($slots->isNotEmpty())
            <div class="admin-table-scroll">
                <table class="table admin-table admin-table--compact mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Applicant</th>
                            <th>Event</th>
                            <th>Date &amp; time</th>
                            <th class="pe-3">When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slots as $slot)
                            @php $a = $slot['applicant']; @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                        <span class="admin-avatar">{{ strtoupper(substr($a->first_name, 0, 1) . substr($a->last_name, 0, 1)) }}</span>
                                        <a href="{{ route('applicants.show', $a) }}" class="fw-semibold admin-table__name text-decoration-none">{{ $a->first_name }} {{ $a->last_name }}</a>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $slot['type'] === 'exam' ? 'admin-badge-exam' : 'admin-badge-released' }}">
                                        {{ $slot['type'] === 'exam' ? 'Qualifying exam' : 'Orientation' }}
                                    </span>
                                </td>
                                <td>{{ $slot['at']->format('M d, Y · g:ia') }}</td>
                                <td class="pe-3 text-muted-soft">{{ $slot['at']->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted-soft py-5">
                <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                Nothing scheduled for this view.
            </div>
        @endif
    </div>
</div>
@endsection
