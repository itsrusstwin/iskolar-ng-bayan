<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with(['user', 'applicant'])->latest()->paginate(30);

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