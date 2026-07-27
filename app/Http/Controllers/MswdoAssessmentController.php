<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class MswdoAssessmentController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function assess(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'referral_slip_no' => 'nullable|string|max:50',
            'is_qualified' => 'required|boolean',
            'social_case_study_report' => 'nullable|file|mimes:pdf|max:5120', // 5MB, PDF only
            'disqualification_reason' => 'nullable|string|max:1000',
        ], [
            'social_case_study_report.mimes' => 'Only PDF files are allowed for the social case study report.',
        ]);

        $filePath = null;
        if ($request->hasFile('social_case_study_report')) {
            $filePath = $request->file('social_case_study_report')->store('mswdo_reports', 'public');
        }

        $assessment = $applicant->mswdoAssessment()->updateOrCreate([], [
            'referral_slip_no' => $validated['referral_slip_no'] ?? null,
            'social_case_study_report_path' => $filePath,
            'is_qualified' => $validated['is_qualified'],
            'assessed_at' => now(),
        ]);

        $this->workflow->assessPoverty(
            $applicant,
            (bool) $validated['is_qualified'],
            $validated['disqualification_reason'] ?? null
        );

        AuditLog::record(
            'mswdo_assessed',
            "Recorded MSWDO assessment for {$applicant->first_name} {$applicant->last_name} — resulting status: {$applicant->status}",
            $applicant,
            $assessment
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'MSWDO assessment recorded.');
    }
}