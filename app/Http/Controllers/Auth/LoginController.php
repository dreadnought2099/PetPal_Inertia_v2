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
            'password' => 'required'
        ]);

        // Attempt login
        if (!Auth::attempt($validated)) {
            return Redirect::back()->with('error', 'Invalid email or password');
        }
        
        $user = Auth::user();
        Mail::to($user->email)->queue(new WelcomeMail($user));
        
        return redirect()->route('pets.index')->with('success', "Login successful! Welcome, {$user->name}.");
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
