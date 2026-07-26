<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditProfileController extends Controller
{
    public function edit()
    {
        $applicant = Auth::user()->applicant;
        return view('applicants.edit', compact('applicant'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $applicant = Auth::user()->applicant;
        $applicant->update($request->validated());

        $applicant->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profile updated!');
    }
}