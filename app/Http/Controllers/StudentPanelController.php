<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * Separate student panels that used to be stacked on the dashboard:
 * benefits (waste compliance + payouts), schedules and appeals.
 */
class StudentPanelController extends Controller
{
    protected function applicant()
    {
        $applicant = Auth::user()->applicant;

        if ($applicant) {
            $applicant->load(['disqualifications.appeals', 'wasteCompliance', 'payouts', 'orientation']);
        }

        return $applicant;
    }

    public function benefits()
    {
        $applicant = $this->applicant();
        if (! $applicant) {
            return redirect()->route('dashboard');
        }

        return view('student.benefits', compact('applicant'));
    }

    public function schedules()
    {
        $applicant = $this->applicant();
        if (! $applicant) {
            return redirect()->route('dashboard');
        }

        return view('student.schedules', compact('applicant'));
    }

    public function appeals()
    {
        $applicant = $this->applicant();
        if (! $applicant) {
            return redirect()->route('dashboard');
        }

        $isDisqualified = str_starts_with($applicant->status, 'disqualified');
        $latestDisqualification = $applicant->disqualifications->last();
        $existingAppeal = $latestDisqualification ? $latestDisqualification->appeals->last() : null;

        return view('student.appeals', compact('applicant', 'isDisqualified', 'latestDisqualification', 'existingAppeal'));
    }
}
