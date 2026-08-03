<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function scheduleExam(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'exam_scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $date = $validated['exam_scheduled_at'];

        $applicant->update(['exam_scheduled_at' => $date]);

        AuditLog::record(
            'exam_scheduled',
            "Scheduled the qualifying exam for {$applicant->first_name} {$applicant->last_name} on " . Carbon::parse($date)->format('M d, Y g:ia'),
            $applicant
        );

        $applicant->user?->notify(new ApplicationStatusChanged(
            'Exam scheduled',
            'Your qualifying exam is scheduled for ' . Carbon::parse($date)->format('F j, Y \a\t g:ia') . '.'
        ));

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Exam schedule saved and applicant notified.');
    }

    public function scheduleOrientation(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'orientation_scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $date = $validated['orientation_scheduled_at'];

        $applicant->update(['orientation_scheduled_at' => $date]);

        AuditLog::record(
            'orientation_scheduled',
            "Scheduled the orientation for {$applicant->first_name} {$applicant->last_name} on " . Carbon::parse($date)->format('M d, Y g:ia'),
            $applicant
        );

        $applicant->user?->notify(new ApplicationStatusChanged(
            'Orientation scheduled',
            'Your orientation is scheduled for ' . Carbon::parse($date)->format('F j, Y \a\t g:ia') . '.'
        ));

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Orientation schedule saved and applicant notified.');
    }

    public function scheduleBulk(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:exam,orientation'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'applicant_ids' => ['required', 'array', 'min:1'],
            'applicant_ids.*' => ['integer', 'exists:applicants,id'],
        ]);

        $date = Carbon::parse($validated['scheduled_at']);
        $applicants = Applicant::whereIn('id', $validated['applicant_ids'])->get();

        $action = $validated['type'] === 'exam' ? 'exam_scheduled' : 'orientation_scheduled';
        $noun = $validated['type'] === 'exam' ? 'qualifying exam' : 'orientation';

        foreach ($applicants as $applicant) {
            $applicant->update([$validated['type'] === 'exam' ? 'exam_scheduled_at' : 'orientation_scheduled_at' => $date]);

            AuditLog::record(
                $action,
                "Scheduled the {$noun} for {$applicant->first_name} {$applicant->last_name} on " . $date->format('M d, Y g:ia'),
                $applicant
            );

            $applicant->user?->notify(new ApplicationStatusChanged(
                ucfirst($noun) . ' scheduled',
                'Your ' . $noun . ' is scheduled for ' . $date->format('F j, Y \a\t g:ia') . '.'
            ));
        }

        return redirect()
            ->route('admin.applicants.index')
            ->with('success', count($applicants) . ' applicant(s) scheduled for the ' . $noun . ' on ' . $date->format('M d, Y g:ia') . '.');
    }
}
