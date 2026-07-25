<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAccountController extends Controller
{
    public function create()
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        return view('admin.students.create');
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

        User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'applicant',
        ]);

        // Note: we intentionally do NOT Auth::login() here —
        // the admin stays logged in as themselves.

        return redirect()
            ->route('admin.students.create')
            ->with('success', 'Student account created. Share the email and password with the student so they can log in and complete their profile.');
    }
}