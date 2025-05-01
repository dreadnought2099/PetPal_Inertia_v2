<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LoginController extends Controller
{
    // Show the login form using Inertia
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (!Auth::attempt($validated)) {
            return back()->withErrors([
                'email' => 'Invalid email or password',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        // Optional: send welcome email
        Mail::to($user->email)->queue(new WelcomeMail($user));

        // Return Inertia-compatible redirect
        return redirect()->route('adopt.log')->with('success', "Adoption request with ID {$adoption->id} updated successfully.");

    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return Inertia-friendly redirect
        return redirect()->route('home');
    }
}
