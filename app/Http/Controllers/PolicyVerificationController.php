<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class PolicyVerificationController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function verify(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'in_spes' => 'nullable|boolean',
            'in_4ps' => 'nullable|boolean',
            'one_scholar_per_family_ok' => 'nullable|boolean',
            'disqualification_reason' => 'nullable|string|max:1000',
        ]);

        $verification = $applicant->verification()->updateOrCreate([], [
            'in_spes' => $request->boolean('in_spes'),
            'in_4ps' => $request->boolean('in_4ps'),
            'one_scholar_per_family_ok' => $request->boolean('one_scholar_per_family_ok', true),
        ]);

        $this->workflow->verifyPolicy($applicant, $validated['disqualification_reason'] ?? null);

        AuditLog::record(
            'policy_verified',
            "Recorded policy verification for {$applicant->first_name} {$applicant->last_name} — resulting status: {$applicant->status}",
            $applicant,
            $verification
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Verification completed.');
    }
}