<?php

namespace App\Http\Controllers;

use App\Models\AdminCreatedAccount;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAccountController extends Controller
{
    public function create()
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $createdAccounts = AdminCreatedAccount::with('creator')
            ->latest()
            ->get();

        return view('admin.students.create', compact('createdAccounts'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $name = strtoupper($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'applicant',
        ]);

        // Note: we intentionally do NOT Auth::login() here —
        // the admin stays logged in as themselves.

        AdminCreatedAccount::create([
            'name' => $name,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'user_id' => $user->id,
            'created_by' => Auth::id(),
        ]);

        AuditLog::record(
            'student_account_created',
            "Created student account for {$user->name} ({$user->email})",
            null,
            $user
        );

        return redirect()
            ->route('admin.students.create')
            ->with('success', 'Student account created. Share the email and password with the student so they can log in and complete their profile.');
    }

    /**
     * Remove an account from the "Created accounts" list only.
     * The underlying User account stays intact.
     */
    public function destroy(Request $request, AdminCreatedAccount $createdAccount)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $createdAccount->delete();

        return redirect()
            ->route('admin.students.create')
            ->with('success', 'Account removed from the list. The student account itself was not deleted.');
    }
}
