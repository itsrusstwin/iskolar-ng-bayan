<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class MasterListController extends Controller
{
    public function index(Request $request, AdminDashboardService $dashboard)
    {
        $filters = [
            'created_date' => $request->input('created_date'),
            'program_type' => $request->input('program_type'),
            'search' => $request->input('search'),
        ];

        $query = Applicant::query()
            ->with([
                'user',
                'requirements.requirement',
                'verification',
                'mswdoAssessment',
                'examResults',
                'orientation',
                'wasteCompliance',
                'payouts',
                'disqualifications.appeals',
            ]);

        if ($request->filled('created_date')) {
            $date = $request->input('created_date');
            $query->whereHas('user', fn ($u) => $u->whereDate('created_at', $date));
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->input('program_type'));
        }

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

        $applicants = $query->orderByDesc('created_at')->get();

        $summary = [
            'total' => $applicants->count(),
            'in_progress' => $applicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'in_progress')->count(),
            'qualified' => $applicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'qualified')->count(),
            'released' => $applicants->where('status', 'paid_out')->count(),
            'disqualified' => $applicants->filter(fn (Applicant $a) => $dashboard->categorizeProgress($a->status) === 'disqualified')->count(),
        ];

        return view('admin.master-list.index', compact('applicants', 'filters', 'summary', 'dashboard'));
    }
}