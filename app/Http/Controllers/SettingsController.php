<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        return Inertia::render('Settings/Index', [
            'user' => $user,
        ]);
    }
}
