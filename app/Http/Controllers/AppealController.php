<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\AuditLog;
use App\Models\Disqualification;
use App\Notifications\AdminNotification;
use App\Notifications\ApplicationStatusChanged;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * Admin "Appeals" panel: all appeals with a status filter.
     */
    public function index(Request $request)
    {
        $status = in_array($request->input('status'), ['pending', 'approved', 'denied'], true)
            ? $request->input('status')
            : ($request->input('status') === 'all' ? 'all' : 'pending');
        $search = trim((string) $request->input('search', ''));

        $query = Appeal::with('disqualification.applicant.user')->latest('filed_at');

        if ($status !== 'all') {
            $query->where('result', $status);
        }

        if ($search !== '') {
            $query->whereHas('disqualification.applicant', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%");
            });
        }

        return view('admin.appeals.index', [
            'appeals' => $query->get(),
            'status' => $status,
            'search' => $search,
            'counts' => [
                'pending' => Appeal::where('result', 'pending')->count(),
                'approved' => Appeal::where('result', 'approved')->count(),
                'denied' => Appeal::where('result', 'denied')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'disqualification_id' => 'required|exists:disqualifications,id',
            'reconsideration_notes' => 'required|string',
        ]);

        $disqualification = Disqualification::findOrFail($validated['disqualification_id']);

        // A student may only appeal their own disqualification.
        abort_unless($disqualification->applicant?->user_id === Auth::id(), 403);

        // Don't allow a second appeal while one is pending or already approved.
        $existing = $disqualification->appeals()->whereIn('result', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->back()->with(
                'success',
                'An appeal for this decision has already been filed and is ' . $existing->result . '.'
            );
        }

        $appeal = Appeal::create([
            'disqualification_id' => $validated['disqualification_id'],
            'reconsideration_notes' => $validated['reconsideration_notes'],
            'result' => 'pending',
        ]);

        $disqualification->applicant?->user?->notify(
            new ApplicationStatusChanged('Appeal filed', 'We have received your appeal and it is now under review.')
        );

        $student = $disqualification->applicant;
        AdminNotification::sendToAdmins(new AdminNotification(
            title: 'New appeal filed',
            body: $student ? "{$student->first_name} {$student->last_name} filed an appeal against their disqualification." : 'A student filed an appeal.',
            url: $student ? route('applicants.show', $student) : null,
            studentName: $student ? "{$student->first_name} {$student->last_name}" : null,
        ));

        return redirect()->back()->with('success', 'Appeal filed successfully. Awaiting review.');
    }

    public function approve(Appeal $appeal)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if ($appeal->result !== 'pending') {
            return redirect()->back()->with('success', 'This appeal has already been resolved.');
        }

        $appeal->update(['result' => 'approved']);
        $applicant = $appeal->disqualification->applicant;

        $this->workflow->reinstateFromAppeal($appeal->disqualification);

        AuditLog::record(
            'appeal_approved',
            "Approved appeal for {$applicant->first_name} {$applicant->last_name} (disqualification stage: {$appeal->disqualification->stage}) — applicant reinstated",
            $applicant,
            $appeal
        );

        return redirect()->back()->with('success', 'Appeal approved. The applicant has been reinstated.');
    }

    public function reject(Appeal $appeal)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if ($appeal->result !== 'pending') {
            return redirect()->back()->with('success', 'This appeal has already been resolved.');
        }

        $appeal->update(['result' => 'denied']);

        $applicant = $appeal->disqualification->applicant;

        $applicant?->user?->notify(
            new ApplicationStatusChanged('Appeal denied', 'After review, the disqualification decision has been upheld.')
        );

        AuditLog::record(
            'appeal_denied',
            "Denied appeal for {$applicant->first_name} {$applicant->last_name} (disqualification stage: {$appeal->disqualification->stage})",
            $applicant,
            $appeal
        );

        return redirect()->back()->with('success', 'Appeal denied.');
    }
}