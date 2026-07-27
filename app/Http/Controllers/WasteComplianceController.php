<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class WasteComplianceController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
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