<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'applicant']);

        if ($request->filled('date')) {
            try {
                $date = \Illuminate\Support\Carbon::parse($request->input('date'));
                $query->whereDate('created_at', $date);
            } catch (\Throwable $e) {
                // ignore invalid date input
            }
        }

        $logs = $query->latest()->paginate(30)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No audit log entries selected.');
        }

        AuditLog::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' audit log ' . Str::plural('entry', count($ids)) . ' deleted.');
    }
}