<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\AuditLog;
use App\Models\Disqualification;
use App\Notifications\ApplicationStatusChanged;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'disqualification_id' => 'required|exists:disqualifications,id',
            'reconsideration_notes' => 'required|string',
        ]);

        $disqualification = Disqualification::findOrFail($validated['disqualification_id']);

        // Don't allow a second appeal while one is pending or already approved.
        $existing = $disqualification->appeals()->whereIn('result', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->back()->with(
                'success',
                'An appeal for this decision has already been filed and is ' . $existing->result . '.'
            );
        }

        $appeal = Appeal::create([
            'disqualification_id' => $validated['disqualification_id'],
            'reconsideration_notes' => $validated['reconsideration_notes'],
            'result' => 'pending',
        ]);

        $disqualification->applicant?->user?->notify(
            new ApplicationStatusChanged('Appeal filed', 'We have received your appeal and it is now under review.')
        );

        return redirect()->back()->with('success', 'Appeal filed successfully. Awaiting review.');
    }

    public function approve(Appeal $appeal)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $appeal->update(['result' => 'approved']);
        $applicant = $appeal->disqualification->applicant;

        $this->workflow->reinstateFromAppeal($appeal->disqualification);

        AuditLog::record(
            'appeal_approved',
            "Approved appeal for {$applicant->first_name} {$applicant->last_name} (disqualification stage: {$appeal->disqualification->stage}) — applicant reinstated",
            $applicant,
            $appeal
        );

        return redirect()->back()->with('success', 'Appeal approved. The applicant has been reinstated.');
    }

    public function reject(Appeal $appeal)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $appeal->update(['result' => 'denied']);

        $applicant = $appeal->disqualification->applicant;

        $applicant?->user?->notify(
            new ApplicationStatusChanged('Appeal denied', 'After review, the disqualification decision has been upheld.')
        );

        AuditLog::record(
            'appeal_denied',
            "Denied appeal for {$applicant->first_name} {$applicant->last_name} (disqualification stage: {$appeal->disqualification->stage})",
            $applicant,
            $appeal
        );

        return redirect()->back()->with('success', 'Appeal denied.');
    }
}