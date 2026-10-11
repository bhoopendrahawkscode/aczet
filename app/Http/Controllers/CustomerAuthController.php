<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dealer;
use App\Models\Lead;

class CustomerAuthController extends Controller
{
    /**
     * Display the Customer Login and Sign Up portal.
     */
    public function showAuth(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }

        if (Auth::guard('dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }

        $activeTab = $request->query('tab', 'login');
        if (!in_array($activeTab, ['login', 'register', 'dealer'])) {
            $activeTab = 'login';
        }

        return view('frontend.customer.auth', compact('activeTab'));
    }

    /**
     * Handle Customer Sign In.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        // 1. Try standard customer login
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->intended(route('customer.dashboard'))->with('success', "Welcome back, {$user->name}!");
        }

        // 2. Seamless fallback: Check if email belongs to an authorized distributor
        $dealer = Dealer::where('email', $credentials['email'])->first();
        if ($dealer && Hash::check($credentials['password'], $dealer->password)) {
            if ($dealer->status !== 'Approved') {
                return back()->withErrors(['email' => 'Your authorized distributor application is currently under review by Channel Sales.'])->withInput();
            }

            Auth::guard('dealer')->login($dealer, $remember);
            return redirect()->route('dealer.dashboard')->with('success', "Welcome back, {$dealer->contact_person}! Authorized Distributor Session Active.");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle Customer Sign Up (Registration).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:191',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', "Welcome to Aczet! Your customer account has been created successfully.");
    }

    /**
     * Customer Dashboard / Account Profile.
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('customer.login');
        }

        // Retrieve user's inquiries and RFQs
        $recentInquiries = Lead::where('email', $user->email)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('frontend.customer.dashboard', compact('user', 'recentInquiries'));
    }

    /**
     * Handle Customer Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')->with('success', 'You have been logged out securely.');
    }
}

