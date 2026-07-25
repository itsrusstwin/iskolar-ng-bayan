<?php

namespace App\Http\Controllers;

use App\Models\ApplicantRequirement;
use Illuminate\Support\Facades\Auth;

class RequirementReviewController extends Controller
{
    public function approve(ApplicantRequirement $requirement)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $requirement->update(['approval_status' => 'approved']);

        return redirect()->back()->with('success', 'Requirement approved.');
    }

    public function reject(ApplicantRequirement $requirement)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $requirement->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Requirement marked as not approved.');
    }
}