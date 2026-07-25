<?php

namespace App\Http\Controllers;

use App\Models\ApplicantRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequirementUploadController extends Controller
{
    public function store(Request $request, ApplicantRequirement $requirement)
{
    abort_unless(
        $requirement->applicant_id === Auth::user()->applicant?->id,
        403
    );

    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf|max:5120', // 5MB, PDF only
    ], [
        'file.mimes' => 'Only PDF files are allowed. Please upload your document as a PDF.',
    ]);

    if ($requirement->file_path) {
        Storage::disk('public')->delete($requirement->file_path);
    }

    $path = $request->file('file')->store('requirements', 'public');

    $requirement->update([
        'file_path' => $path,
        'is_submitted' => true,
        'submitted_at' => now(),
        'approval_status' => 'pending',
    ]);

    return redirect()->route('dashboard')->with('success', 'Requirement uploaded successfully.');
}

    public function destroy(ApplicantRequirement $requirement)
    {
        abort_unless(
            $requirement->applicant_id === Auth::user()->applicant?->id,
            403
        );

        if ($requirement->file_path) {
            Storage::disk('public')->delete($requirement->file_path);
        }

        $requirement->update([
            'file_path' => null,
            'is_submitted' => false,
            'submitted_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Requirement removed.');
    }
}