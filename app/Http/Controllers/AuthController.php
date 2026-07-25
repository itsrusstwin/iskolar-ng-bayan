<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Check if the email exists at all before checking the password
    $userExists = User::where('email', $credentials['email'])->exists();

    if (!$userExists) {
        return back()->withErrors([
            'email' => 'No account found with that email. Please contact your scholarship administrator to have an account created for you.',
        ])->withInput($request->only('email'));
    }

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
        }

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    return back()->withErrors([
        'email' => 'Incorrect password. Please try again.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}