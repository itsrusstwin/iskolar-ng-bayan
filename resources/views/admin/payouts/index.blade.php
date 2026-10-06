@extends('layouts.app')
@section('title', 'Scholarship Payouts')
@section('subtitle', 'Disbursements released to scholars')

@section('content')

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="rounded-md p-4 h-100" style="background: linear-gradient(135deg, var(--ink-800), var(--ink-600));">
            <p class="small text-white-50 mb-1">Total disbursed (all time)</p>
            <p class="h3 fw-bold mb-0 text-white">₱{{ number_format($totalDisbursed, 2) }}</p>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="admin-kpi-card h-100">
            <p class="small text-muted-soft mb-1">This month</p>
            <p class="h3 fw-bold mb-0 kpi-value-blue">₱{{ number_format($thisMonthDisbursed, 2) }}</p>
            <p class="small text-muted-soft mb-0 mt-1">{{ now()->format('F Y') }}</p>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="admin-kpi-card h-100">
            <p class="small text-muted-soft mb-1">Shown in table</p>
            <p class="h3 fw-bold mb-0">₱{{ number_format($filteredTotal, 2) }}</p>
            <p class="small text-muted-soft mb-0 mt-1">{{ $payouts->count() }} release{{ $payouts->count() === 1 ? '' : 's' }}</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Release a payout -->
    <div class="col-lg-4">
        <div class="admin-panel h-100">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0">Release a payout</h2>
                <p class="small text-muted-soft mb-0">Scholars awaiting disbursement</p>
            </div>
            <div class="admin-panel__body">
                @if ($eligibleScholars->isEmpty())
                    <div class="text-center text-muted-soft py-4 small">
                        <i class="bi bi-wallet2 fs-2 d-block mb-2 opacity-50"></i>
                        No scholars are eligible for a payout right now.
                    </div>
                @else
                    <form method="POST" action="#" id="payoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="payoutScholar">Scholar</label>
                            <select id="payoutScholar" class="form-select form-select-sm" required
                                    onchange="document.getElementById('payoutForm').action = this.value;">
                                <option value="" disabled selected>Select a scholar…</option>
                                @foreach ($eligibleScholars as $scholar)
                                    <option value="{{ route('admin.payout', $scholar) }}">
                                        {{ $scholar->first_name }} {{ $scholar->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="payoutAmount">Amount (₱)</label>
                            <input type="number" step="0.01" min="0" id="payoutAmount" name="amount" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1" for="payoutRef">Reference no. <span class="text-muted-soft fw-normal">(optional)</span></label>
                            <input type="text" id="payoutRef" name="reference_no" class="form-control form-control-sm" maxlength="100">
                        </div>
                        <button type="submit" class="btn btn-navy btn-sm w-100"
                                onclick="return confirm('Release this payout? The scholar will be notified.');">
                            <i class="bi bi-send-check me-1"></i> Release payout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Payout history -->
    <div class="col-lg-8">
        <div class="admin-panel">
            <div class="admin-panel__header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 flex-wrap">
                <h2 class="h6 fw-bold mb-0">Payout history</h2>
                <form method="GET" action="{{ route('admin.payouts.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Scholar or reference…" class="form-control border-start-0" style="min-width:180px;">
                    </div>
                    <input type="month" name="month" value="{{ $selectedMonth !== 'all' ? $selectedMonth : '' }}" class="form-control form-control-sm" style="width:auto;" max="{{ now()->format('Y-m') }}">
                    <button class="btn btn-navy btn-sm" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('admin.payouts.index', ['month' => 'all']) }}" class="btn btn-outline-navy btn-sm">All time</a>
                </form>
            </div>
            <div class="admin-panel__body admin-panel__body--flush">
                @if ($payouts->isNotEmpty())
                    <div class="admin-table-scroll admin-table-scroll--y" style="max-height: 520px;">
                        <table class="table admin-table admin-table--compact mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Scholar</th>
                                    <th>Released</th>
                                    <th>Reference</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payouts as $payout)
                                    @php $a = $payout->applicant; @endphp
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
                                        <td class="text-muted-soft">{{ $payout->released_at?->format('M d, Y') ?? '—' }}</td>
                                        <td class="text-muted-soft">{{ $payout->reference_no ?? '—' }}</td>
                                        <td class="text-end fw-semibold">₱{{ number_format($payout->amount, 2) }}</td>
                                        <td class="text-end pe-3">
                                            <form method="POST" action="{{ route('admin.payout.destroy', $payout) }}" class="d-inline"
                                                  onsubmit="return confirm('Delete this payout of ₱{{ number_format($payout->amount, 2) }}? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" style="padding:.25rem .6rem; font-size:.75rem;" title="Delete payout">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted-soft py-5">
                        <i class="bi bi-wallet2 fs-2 d-block mb-2 opacity-50"></i>
                        No payouts match your filters.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
