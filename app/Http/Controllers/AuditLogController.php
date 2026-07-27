<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with(['user', 'applicant'])->latest()->paginate(30);

        return view('admin.audit-log.index', compact('logs'));
    }
}