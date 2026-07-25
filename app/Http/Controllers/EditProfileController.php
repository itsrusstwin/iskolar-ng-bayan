<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditProfileController extends Controller
{
    public function edit()
    {
        $applicant = Auth::user()->applicant;
        return view('applicants.edit', compact('applicant'));
    }

    public function update(Request $request)
    {
        $applicant = Auth::user()->applicant;

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
            LAGUNA STATE POLYTECHNIC UNIVERSITY,LAGUNA UNIVERSITY,STI COLLEGE,PHINMA UNION COLLEGE,
            SOUTHBAY MONTESSORI SCHOOL,PHILIPPINE WOMEN\'S UNIVERSITY',
            'year_level' => 'required|string|max:20',
            'course' => 'required|string|max:150',
        ]);

        $applicant->update($validated);

        // Keep the User's name in sync with the Applicant's name
        $applicant->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile updated!');
    }
}