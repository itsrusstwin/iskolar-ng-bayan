<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Applicant;
use App\Models\Requirement;
use App\Http\Requests\StoreApplicantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applicant = $user->applicant;
        if ($applicant) {
            $applicant->load(['requirements.requirement', 'disqualifications.appeals', 'wasteCompliance', 'payouts']);
        }
        $requirements = Requirement::all();
        $announcements = Announcement::where('is_published', true)->latest()->take(4)->get();

        return view('dashboard', compact('applicant', 'requirements', 'announcements'));
    }

    public function store(StoreApplicantRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $validated['school_name'] = $validated['school_name'] === '__other__'
            ? strtoupper($validated['school_name_other'])
            : strtoupper($validated['school_name']);
        $validated['course'] = $validated['course'] === '__other__'
            ? strtoupper($validated['course_other'] ?? '')
            : strtoupper($validated['course']);

        foreach (['last_name', 'first_name', 'middle_name', 'father_name', 'mother_maiden_name'] as $field) {
            if (!empty($validated[$field])) {
                $validated[$field] = strtoupper($validated[$field]);
            }
        }

        $applicant = Applicant::create(array_merge($validated, [
            'user_id' => $user->id,
        ]));

        foreach (Requirement::all() as $requirement) {
            $applicant->requirements()->create([
                'requirement_id' => $requirement->id,
                'is_submitted' => false,
                'file_path' => null,
                'submitted_at' => null,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Profile completed!');
    }
}