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

    public function index(AdminDashboardService $dashboard)
    {
        $data = $dashboard->getDashboardData();

        return view('admin.dashboard', [
            'stats' => $data['stats'],
            'progressChart' => $data['progressChart'],
            'programChart' => $data['programChart'],
            'recentApplicants' => $data['recentApplicants'],
            'recentActivity' => $data['recentActivity'],
            'applicants' => $data['applicantsByStatus'],
            'dashboard' => $dashboard,
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
            $query->where('status', $request->input('status'));
        }

        $applicants = $query->orderByDesc('created_at')->get();

        $statuses = array_merge(['all' => 'All statuses'], AdminDashboardService::STATUS_LABELS);

        return view('admin.applicants.index', [
            'applicants' => $applicants,
            'statuses' => $statuses,
            'dashboard' => $dashboard,
        ]);
    }

    public function destroy(Applicant $applicant)
    {
        $user = $applicant->user;

        DB::transaction(function () use ($applicant, $user) {
            AuditLog::record(
                'student_account_deleted',
                "Deleted student account for {$applicant->first_name} {$applicant->last_name}" .
                    ($user ? " ({$user->email})" : '') .
                    ' — all application records removed.',
                $applicant
            );

            $applicant->delete();

            if ($user && !$user->isAdmin()) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.applicants.index')
            ->with('success', 'Student account deleted.');
    }

    public function show(Applicant $applicant)
{
    $applicant->load([
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