<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class OrientationController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function complete(Request $request, Applicant $applicant)
    {
        $orientation = $applicant->orientation()->updateOrCreate([], [
            'attended' => true,
            'signed_acknowledgement' => true,
            'attended_at' => now(),
        ]);

        $this->workflow->completeOrientation($applicant);

        AuditLog::record(
            'orientation_completed',
            "Marked orientation complete for {$applicant->first_name} {$applicant->last_name}",
            $applicant,
            $orientation
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Orientation marked complete.');
    }
}