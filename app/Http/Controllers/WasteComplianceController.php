<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\WasteCompliance;
use App\Services\AdminDashboardService;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class WasteComplianceController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function index(Request $request, AdminDashboardService $dashboard)
    {
        $status = $request->input('status', 'all');
        $semester = $request->input('semester', 'all');
        $search = trim((string) $request->input('search', ''));

        $query = WasteCompliance::with(['applicant.user'])->latest();

        if ($status === 'compliant') {
            $query->where('is_compliant', true);
        } elseif ($status === 'deficient') {
            $query->where('is_compliant', false);
        }

        if ($semester !== 'all' && $request->filled('semester')) {
            $query->where('semester', $semester);
        }

        if ($search !== '') {
            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where(fn ($x) => $x->where('email', 'like', "%{$search}%")->orWhere('application_id', 'like', "%{$search}%")));
            });
        }

        $records = $query->get();

        $totalKg = WasteCompliance::sum('kilos_submitted');
        $totalSubmissions = WasteCompliance::count();
        $compliantCount = WasteCompliance::where('is_compliant', true)->count();
        $deficientCount = WasteCompliance::where('is_compliant', false)->count();

        // Unique semesters for filtering
        $semesters = WasteCompliance::distinct()->pluck('semester')->filter()->values();

        // Scholars currently in orientation or compliance stage who need eco-brick submissions
        $pendingScholars = Applicant::with('user')
            ->whereIn('status', ['oriented', 'compliance_pending'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.waste-compliance.index', [
            'records' => $records,
            'status' => $status,
            'semester' => $semester,
            'semesters' => $semesters,
            'search' => $search,
            'totalKg' => $totalKg,
            'totalSubmissions' => $totalSubmissions,
            'compliantCount' => $compliantCount,
            'deficientCount' => $deficientCount,
            'pendingScholars' => $pendingScholars,
            'dashboard' => $dashboard,
        ]);
    }

    public function store(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'semester' => 'required|string|max:20',
            'kilos_submitted' => 'required|numeric|min:0',
        ]);

        $isCompliant = $validated['kilos_submitted'] >= 10;

        $wasteCompliance = $applicant->wasteCompliance()->create([
            'semester' => $validated['semester'],
            'kilos_submitted' => $validated['kilos_submitted'],
            'is_compliant' => $isCompliant,
        ]);

        $this->workflow->recordWasteCompliance($applicant, (float) $validated['kilos_submitted']);

        AuditLog::record(
            'waste_compliance_recorded',
            "Recorded waste compliance ({$validated['kilos_submitted']}kg, {$validated['semester']}) for {$applicant->first_name} {$applicant->last_name} — resulting status: {$applicant->status}",
            $applicant,
            $wasteCompliance
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Waste compliance recorded.');
    }
}