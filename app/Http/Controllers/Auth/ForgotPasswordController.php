<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ForgotPasswordController extends Controller
{
    // Show the password reset request form
    public function showLinkRequestForm(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    // Handle sending of the reset link
    public function sendResetLinkEmail(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Attempt to send reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Return appropriate response
        return $status === Password::RESET_LINK_SENT
            ? Redirect::back()->with('status', __($status))
            : Redirect::back()->withErrors(['email' => __($status)]);
    }
}
