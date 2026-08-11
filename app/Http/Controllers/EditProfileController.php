<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditProfileController extends Controller
{
    public function show()
    {
        $applicant = Auth::user()->applicant;
        return view('applicants.profile', compact('applicant'));
    }

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

        AdminNotification::sendToAdmins(new AdminNotification(
            title: 'Profile updated',
            body: "{$applicant->first_name} {$applicant->last_name} updated their application profile.",
            url: route('applicants.show', $applicant),
            studentName: "{$applicant->first_name} {$applicant->last_name}",
        ));

        return redirect()->route('profile.show')->with('success', 'Profile updated!');
    }
}