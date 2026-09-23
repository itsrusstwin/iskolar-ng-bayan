<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class MasterListController extends Controller
{
    public function index(Request $request, AdminDashboardService $dashboard)
    {
        // The Applicant Type dropdown merges program type and archive status
        // into one control. Its value is either a program type ('new'/'renewal')
        // or an archive status ('active'/'archived').
        $rawType = $request->input('program_type');
        $programType = in_array($rawType, ['new', 'renewal'], true) ? $rawType : null;
        $archiveStatus = in_array($rawType, ['active', 'archived'], true)
            ? $rawType
            : (in_array($request->input('archive_status'), ['all', 'active', 'archived'], true)
                ? $request->input('archive_status')
                : 'all');

        $rawStatus = $request->input('status');
        $status = ($rawStatus && $rawStatus !== 'all') ? $rawStatus : null;

        $filters = [
            'created_date' => $request->input('created_date'),
            'program_type' => $programType,
            'search' => $request->input('search'),
            'archive_status' => $archiveStatus,
            'status' => $status,
        ];

        // The list view only needs what the table renders. The full record is
        // loaded on demand by show() when the admin clicks a row, so we no
        // longer eager-load nine relations for every applicant on the page.
        $query = Applicant::withTrashed()
            ->with([
                'user' => fn ($q) => $q->withTrashed(),
                'payouts',
            ]);

        if ($filters['archive_status'] === 'active') {
            $query->whereNull('applicants.deleted_at');
        } elseif ($filters['archive_status'] === 'archived') {
            $query->whereNotNull('applicants.deleted_at');
        }

        if ($request->filled('created_date')) {
            $date = $request->input('created_date');
            $query->whereHas('user', fn ($u) => $u->withTrashed()->whereDate('created_at', $date));
        }

        if ($filters['program_type']) {
            $query->where('program_type', $filters['program_type']);
        }

        if ($filters['status']) {
            if ($filters['status'] === 'scholars') {
                $query->whereIn('status', [
                    'exam_passed',
                    'oriented',
                    'compliance_pending',
                    'compliance_met',
                    'paid_out',
                ]);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->withTrashed()->where('email', 'like', "%{$search}%"));
            });
        }

        $applicants = $query->orderByDesc('created_at')->get();

        $activeApplicants = $applicants->whereNull('deleted_at');

        $summary = [
            'total' => $applicants->count(),
            'archived' => $applicants->whereNotNull('deleted_at')->count(),
            'active' => $activeApplicants->count(),
            'in_progress' => $activeApplicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'in_progress')->count(),
            'qualified' => $activeApplicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'qualified')->count(),
            'released' => $activeApplicants->where('status', 'paid_out')->count(),
            'disqualified' => $activeApplicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'disqualified')->count(),
        ];

        $statuses = array_merge(
            ['all' => 'All statuses', 'scholars' => 'Scholars (passed / active)'],
            AdminDashboardService::STATUS_LABELS
        );

        return view('admin.master-list.index', compact('applicants', 'filters', 'summary', 'dashboard', 'statuses'));
    }


    /**
     * Return the full record of ONE applicant as an HTML fragment.
     *
     * Requested over fetch() by the master list when a row is clicked, and
     * injected into the slide-out drawer. Archived (soft-deleted) applicants
     * are resolvable here too, which is why the binding is resolved by hand
     * instead of relying on implicit route-model binding.
     */
    public function show(int $applicant, AdminDashboardService $dashboard)
    {
        $applicant = Applicant::withTrashed()
            ->with([
                'user' => fn ($q) => $q->withTrashed(),
                'requirements.requirement',
                'verification',
                'mswdoAssessment',
                'examResults.exam',
                'orientation',
                'wasteCompliance',
                'payouts',
                'disqualifications.appeals',
                'auditLogs.user' => fn ($q) => $q->withTrashed(),
            ])
            ->findOrFail($applicant);

        return view('admin.master-list._record', compact('applicant', 'dashboard'));
    }
}