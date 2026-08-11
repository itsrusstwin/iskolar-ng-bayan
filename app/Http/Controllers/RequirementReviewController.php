<?php

namespace App\Http\Controllers;

use App\Models\ApplicantRequirement;
use App\Models\AuditLog;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Support\Facades\Auth;

class RequirementReviewController extends Controller
{
    public function approve(ApplicantRequirement $requirement)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $requirement->update(['approval_status' => 'approved']);

        AuditLog::record(
            'requirement_approved',
            "Approved requirement \"{$requirement->requirement->name}\" for {$requirement->applicant->first_name} {$requirement->applicant->last_name}",
            $requirement->applicant,
            $requirement
        );

        $requirement->applicant?->user?->notify(new ApplicationStatusChanged(
            'Requirement approved',
            "Your \"{$requirement->requirement->name}\" has been approved."
        ));

        return redirect()->back()->with('success', 'Requirement approved.');
    }

    public function reject(ApplicantRequirement $requirement)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $requirement->update(['approval_status' => 'rejected']);

        AuditLog::record(
            'requirement_rejected',
            "Marked requirement \"{$requirement->requirement->name}\" as not approved for {$requirement->applicant->first_name} {$requirement->applicant->last_name}",
            $requirement->applicant,
            $requirement
        );

        $requirement->applicant?->user?->notify(new ApplicationStatusChanged(
            'Requirement not approved',
            "Your \"{$requirement->requirement->name}\" was not approved. Please re-upload a valid document."
        ));

        return redirect()->back()->with('success', 'Requirement marked as not approved.');
    }
}