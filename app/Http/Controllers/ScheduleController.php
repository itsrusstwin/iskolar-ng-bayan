<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    /**
     * Admin "Schedules" panel: every exam / orientation slot that has been set,
     * with type, time-window and name filters.
     */
    public function index(Request $request)
    {
        $type = in_array($request->input('type'), ['exam', 'orientation'], true) ? $request->input('type') : 'all';
        $when = in_array($request->input('when'), ['past', 'all'], true) ? $request->input('when') : 'upcoming';
        $search = trim((string) $request->input('search', ''));

        $applicants = Applicant::with('user')
            ->where(function ($q) {
                $q->whereNotNull('exam_scheduled_at')->orWhereNotNull('orientation_scheduled_at');
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('school_name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('application_id', 'like', "%{$search}%"));
                });
            })
            ->get();

        $slots = collect();
        foreach ($applicants as $applicant) {
            if ($applicant->exam_scheduled_at) {
                $slots->push(['applicant' => $applicant, 'type' => 'exam', 'at' => $applicant->exam_scheduled_at]);
            }
            if ($applicant->orientation_scheduled_at) {
                $slots->push(['applicant' => $applicant, 'type' => 'orientation', 'at' => $applicant->orientation_scheduled_at]);
            }
        }

        $now = now();
        $stats = [
            'upcoming_exams' => $slots->where('type', 'exam')->filter(fn ($s) => $s['at']->gte($now))->count(),
            'upcoming_orientations' => $slots->where('type', 'orientation')->filter(fn ($s) => $s['at']->gte($now))->count(),
            'today' => $slots->filter(fn ($s) => $s['at']->isToday())->count(),
            'past' => $slots->filter(fn ($s) => $s['at']->lt($now))->count(),
        ];

        if ($type !== 'all') {
            $slots = $slots->where('type', $type);
        }
        if ($when === 'upcoming') {
            $slots = $slots->filter(fn ($s) => $s['at']->gte($now))->sortBy('at');
        } elseif ($when === 'past') {
            $slots = $slots->filter(fn ($s) => $s['at']->lt($now))->sortByDesc('at');
        } else {
            $slots = $slots->sortByDesc('at');
        }

        return view('admin.schedules.index', [
            'slots' => $slots->values(),
            'type' => $type,
            'when' => $when,
            'search' => $search,
            'stats' => $stats,
        ]);
    }

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
