<?php

namespace App\Http\Controllers;

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
        $requirements = Requirement::all();

        return view('dashboard', compact('applicant', 'requirements'));
    }

    public function store(StoreApplicantRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

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