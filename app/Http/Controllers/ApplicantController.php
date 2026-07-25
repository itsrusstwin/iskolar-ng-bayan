<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Requirement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function create()
    {
        $requirements = Requirement::all();
        return view('applicants.create', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'school_id' => 'nullable|string|max:50',
            'grade_average' => 'required|numeric|min:75|max:100',
            'program_type' => 'required|in:current,aspiring',
        ]);

        $applicant = Applicant::create($validated);

        foreach (Requirement::all() as $requirement) {
            $applicant->requirements()->create([
                'requirement_id' => $requirement->id,
                'is_submitted' => false,
                'file_path' => null,
                'submitted_at' => null,
            ]);
        }

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Application submitted successfully!');
    }

    public function index()
    {
        $applicants = Applicant::latest()->get()->groupBy('status');
        return view('admin.dashboard', compact('applicants'));
    }

    public function show(Applicant $applicant)
{
    $applicant->load([
        'requirements.requirement',
        'verification',
        'mswdoAssessment',
        'examResults',
        'orientation',
        'wasteCompliance',
        'payouts',
        'disqualifications',
    ]);

    /** @var User|null $user */
    $user = Auth::user();

    if ($user && $user->isAdmin()) {
        return view('admin.applicant-show', compact('applicant'));
    }

    return view('applicants.show', compact('applicant'));
}
}