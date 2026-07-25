<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applicant = $user->applicant;
        $requirements = Requirement::all();

        return view('dashboard', compact('applicant', 'requirements'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'last_name' => 'required|string|max:100',
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'contact_number' => 'required|string|max:20',
        'program_type' => 'required|in:new,renewal',
        'date_of_birth' => 'required|date',
        'sex' => 'required|in:Male,Female',
        'landmark' => 'nullable|string|max:150',
        'sitio' => 'nullable|string|max:100',
        'barangay' => 'required|string|max:100',
        'father_name' => 'nullable|string|max:150',
        'mother_maiden_name' => 'nullable|string|max:150',
        'school_name' => 'required|in:ACTS COMPUTER COLLEGE,AMA COLLEGE,
        LAGUNA STATE POLYTECHNIC UNIVERSITY,LAGUNA UNIVERSITY,STI COLLEGE,
        PHINMA UNION COLLEGE,SOUTHBAY MONTESSORI SCHOOL,PHILIPPINE WOMEN\'S UNIVERSITY',
        'year_level' => 'required|string|max:20',
        'course' => 'required|string|max:150',
    ]);

    $user = Auth::user();

    $applicant = Applicant::create([
        'user_id' => $user->id,
        'first_name' => $validated['first_name'],
        'middle_name' => $validated['middle_name'] ?? null,
        'last_name' => $validated['last_name'],
        'contact_number' => $validated['contact_number'],
        'program_type' => $validated['program_type'],
        'date_of_birth' => $validated['date_of_birth'],
        'sex' => $validated['sex'],
        'landmark' => $validated['landmark'] ?? null,
        'sitio' => $validated['sitio'] ?? null,
        'barangay' => $validated['barangay'],
        'father_name' => $validated['father_name'] ?? null,
        'mother_maiden_name' => $validated['mother_maiden_name'] ?? null,
        'school_name' => $validated['school_name'],
        'year_level' => $validated['year_level'],
        'course' => $validated['course'],
    ]);

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