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