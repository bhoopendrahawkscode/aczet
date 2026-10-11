<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Administrator privileges required.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back to Aczet CMS Admin!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our administrator records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out securely.');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Simulated secure password reset token generation
        $token = Str::random(60);
        session(['admin_reset_token_' . $request->email => $token]);

        return back()->with('success', "A password reset link has been dispatched to {$request->email}. Use the secure token below to reset your access.");
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        return view('admin.auth.reset_password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('admin.login')->with('success', 'Your administrator password has been reset successfully. Please log in.');
        }

        return back()->withErrors(['email' => 'Unable to reset password for this email address.']);
    }
}
