@extends('layouts.student')
@section('title', 'My Benefits - Iskolar ng Bayan')

@section('content')
@php
    $totalKilos = $applicant->wasteCompliance->sum('kilos_submitted');
    $totalPayout = $applicant->payouts->sum('amount');
@endphp

<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">My Benefits</h1>
    <p class="text-muted-soft small mb-0">Your waste compliance and scholarship payout records.</p>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-elevated p-4 h-100">
            <div class="panel-head">
                <span class="panel-head__icon"><i class="bi bi-recycle"></i></span>
                <div>
                    <p class="panel-head__title">Waste Compliance</p>
                    <p class="panel-head__sub">Plastic waste submission per semester</p>
                </div>
            </div>

            @if ($applicant->wasteCompliance->count())
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-2">
                        <thead>
                            <tr class="small text-muted-soft">
                                <th>Semester</th>
                                <th class="text-end">Submitted</th>
                                <th class="text-end">Date recorded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicant->wasteCompliance->sortByDesc('semester') as $wc)
                                <tr>
                                    <td class="small">{{ $wc->semester }}</td>
                                    <td class="small text-end">{{ number_format($wc->kilos_submitted, 1) }} kg</td>
                                    <td class="small text-end text-muted-soft">{{ $wc->created_at?->format('M d, Y') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between small pt-2 border-top">
                    <span class="text-muted-soft">Total kilos submitted</span>
                    <span class="fw-semibold">{{ number_format($totalKilos, 1) }} kg</span>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
                    <p class="small text-muted-soft mb-0">No waste compliance records yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-elevated p-4 h-100">
            <div class="panel-head">
                <span class="panel-head__icon"><i class="bi bi-wallet2"></i></span>
                <div>
                    <p class="panel-head__title">Scholarship Payouts</p>
                    <p class="panel-head__sub">Financial assistance released</p>
                </div>
            </div>

            @if ($applicant->payouts->count())
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-2">
                        <thead>
                            <tr class="small text-muted-soft">
                                <th>Amount</th>
                                <th>Released</th>
                                <th class="text-end">Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicant->payouts->sortByDesc('released_at') as $payout)
                                <tr>
                                    <td class="small fw-semibold">₱{{ number_format($payout->amount, 2) }}</td>
                                    <td class="small">{{ $payout->released_at?->format('M d, Y') ?? '—' }}</td>
                                    <td class="small text-end text-muted-soft">{{ $payout->reference_no ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between small pt-2 border-top">
                    <span class="text-muted-soft">Total assistance received</span>
                    <span class="fw-semibold">₱{{ number_format($totalPayout, 2) }}</span>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
                    <p class="small text-muted-soft mb-0">No payouts released yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
