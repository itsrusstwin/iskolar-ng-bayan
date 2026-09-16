<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Requirement;
use App\Models\User;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
    public function create()
    {
        $requirements = Requirement::all();
        return view('applicants.create', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'school_id' => 'nullable|string|max:50',
            'grade_average' => 'required|numeric|min:75|max:100',
            'program_type' => 'required|in:current,aspiring',
        ]);

        $applicant = Applicant::create($validated);

        foreach (Requirement::all() as $requirement) {
            $applicant->requirements()->create([
                'requirement_id' => $requirement->id,
                'is_submitted' => false,
                'file_path' => null,
                'submitted_at' => null,
            ]);
        }

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Application submitted successfully!');
    }

    public function index(AdminDashboardService $dashboard, Request $request)
    {
        $data = $dashboard->getDashboardData();

        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $monthPayouts = collect();
        $monthTotal = 0;

        if (preg_match('/^\d{4}-\d{2}$/', (string) $selectedMonth)) {
            $monthPayouts = \App\Models\Payout::with('applicant')
                ->whereMonth('released_at', substr($selectedMonth, 5, 2))
                ->whereYear('released_at', substr($selectedMonth, 0, 4))
                ->latest('released_at')
                ->get();
            $monthTotal = $monthPayouts->sum('amount');
        }

        return view('admin.dashboard', [
            'stats' => $data['stats'],
            'progressChart' => $data['progressChart'],
            'programChart' => $data['programChart'],
            'recentApplicants' => $data['recentApplicants'],
            'recentActivity' => $data['recentActivity'],
            'applicants' => $data['applicantsByStatus'],
            'dashboard' => $dashboard,
            'monthPayouts' => $monthPayouts,
            'monthTotal' => $monthTotal,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    public function manage(Request $request, AdminDashboardService $dashboard)
    {
        $query = Applicant::query()
            ->with([
                'user',
                'verification',
                'mswdoAssessment',
                'examResults',
                'orientation',
                'wasteCompliance',
                'payouts',
                'disqualifications',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'scholars') {
                $query->whereIn('status', [
                    'exam_passed',
                    'oriented',
                    'compliance_pending',
                    'compliance_met',
                    'paid_out',
                ]);
            } else {
                $query->where('status', $request->input('status'));
            }
        }

        // KPI cards link here with a progress "group" that reuses the dashboard's
        // exact categorization, so the filtered list always matches the card count.
        $group = $request->input('group');
        if (in_array($group, ['in_progress', 'qualified', 'released', 'disqualified'], true)) {
            $statuses = Applicant::query()
                ->distinct()
                ->pluck('status')
                ->map(fn ($s) => (string) $s)
                ->filter(fn ($s) => $dashboard->categorizeProgress($s) === $group)
                ->values()
                ->all();

            if ($statuses === []) {
                $statuses = ['__none__'];
            }
            $query->whereIn('status', $statuses);
        }

        $applicants = $dashboard->onlineFirst($query->orderByDesc('created_at')->get());

        $statuses = array_merge(
            ['all' => 'All statuses', 'scholars' => 'Scholars (passed / active)'],
            AdminDashboardService::STATUS_LABELS
        );

        return view('admin.applicants.index', [
            'applicants' => $applicants,
            'statuses' => $statuses,
            'dashboard' => $dashboard,
            'activeGroup' => $group,
        ]);
    }

    public function destroy(Applicant $applicant)
    {
        $user = $applicant->user;

        DB::transaction(function () use ($applicant, $user) {
            AuditLog::record(
                'student_account_archived',
                "Archived student account for {$applicant->first_name} {$applicant->last_name}" .
                    ($user ? " ({$user->email})" : '') .
                    ' — account kept in the master list archive.',
                $applicant
            );

            $applicant->delete();

            if ($user && !$user->isAdmin()) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.applicants.index')
            ->with('success', 'Student account archived. It remains in the Master List archive.');
    }

    public function show(Applicant $applicant)
{
    $applicant->load([
        'user',
        'requirements.requirement',
        'verification',
        'mswdoAssessment',
        'examResults',
        'orientation',
        'wasteCompliance',
        'payouts',
        'disqualifications.appeals',
        'auditLogs.user',
    ]);

    /** @var User|null $user */
    $user = Auth::user();

    if ($user && $user->isAdmin()) {
        return view('admin.applicant-show', compact('applicant'));
    }

    return view('applicants.show', compact('applicant'));
}
}