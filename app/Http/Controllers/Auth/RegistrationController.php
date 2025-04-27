<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    // Show the registration form using Inertia
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    // Handle the registration request
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Extract validated data
            $validated = $validator->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('Adopter');

            // Redirect with success message
            return redirect()->route('login')->with('success', "Registration successful. Please log in.");
        } catch (\Exception $e) {
            Log::error('User registration failed successfully: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Registration failed. Please try again later.')->withInput();
        }
    }
}
