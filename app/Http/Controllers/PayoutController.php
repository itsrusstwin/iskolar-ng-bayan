<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Payout;
use App\Services\AdminDashboardService;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayoutController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index(Request $request, AdminDashboardService $dashboard)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $search = trim((string) $request->input('search', ''));

        $query = Payout::with(['applicant.user']);

        if ($request->filled('month') && $selectedMonth !== 'all' && preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $year = substr($selectedMonth, 0, 4);
            $month = substr($selectedMonth, 5, 2);
            $query->whereYear('released_at', $year)->whereMonth('released_at', $month);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhereHas('applicant', function ($aq) use ($search) {
                        $aq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('school_name', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%")
                            ->orWhereHas('user', fn ($uq) => $uq->where(fn ($x) => $x->where('email', 'like', "%{$search}%")->orWhere('application_id', 'like', "%{$search}%")));
                    });
            });
        }

        $payouts = $query->latest('released_at')->get();

        $totalDisbursed = Payout::sum('amount');
        $currentMonth = now()->format('Y-m');
        $thisMonthDisbursed = Payout::whereYear('released_at', now()->year)
            ->whereMonth('released_at', now()->month)
            ->sum('amount');

        $filteredTotal = $payouts->sum('amount');

        // Scholars who have completed orientation or met compliance and are awaiting payout
        $eligibleScholars = Applicant::with('user')
            ->whereIn('status', ['compliance_met', 'oriented'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.payouts.index', [
            'payouts' => $payouts,
            'selectedMonth' => $selectedMonth,
            'search' => $search,
            'totalDisbursed' => $totalDisbursed,
            'thisMonthDisbursed' => $thisMonthDisbursed,
            'filteredTotal' => $filteredTotal,
            'eligibleScholars' => $eligibleScholars,
            'dashboard' => $dashboard,
        ]);
    }

    public function release(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'reference_no' => 'nullable|string|max:100',
        ]);

        $payout = $applicant->payouts()->create([
            'amount' => $validated['amount'],
            'reference_no' => $validated['reference_no'] ?? null,
            'released_at' => now(),
        ]);

        $this->workflow->releasePayout($applicant, (float) $validated['amount']);

        AuditLog::record(
            'payout_released',
            'Released payout of ₱' . number_format($validated['amount'], 2) . " to {$applicant->first_name} {$applicant->last_name}"
                . (isset($validated['reference_no']) ? " (ref: {$validated['reference_no']})" : ''),
            $applicant,
            $payout
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Payout released.');
    }

    public function destroy(Request $request, Payout $payout)
    {
        $applicant = $payout->applicant;

        $payout->delete();

        AuditLog::record(
            'payout_deleted',
            "Deleted payout of ₱" . number_format($payout->amount, 2)
                . " for {$applicant->first_name} {$applicant->last_name}",
            $applicant
        );

        return back()->with('success', 'Payout deleted.');
    }
}