<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Adoption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return Inertia::render('Admin/AdminDashboard', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        Log::info("User with ID {$user->id} ({$user->name}) was deleted.");
        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }

    public function changeRole(Request $request, $id)
    {
        if (!Auth::user()->can('change role')) {
            abort(403, 'You are not authorized to change roles');
        }

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $role = Role::where('name', $request->role)->first();

        $user->syncRoles([$role->id]);

        Log::info("User with ID {$user->id} ({$user->name}) role changed to {$request->role}.");

        if ($user->id === Auth::id()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('info', 'Your role has been updated. Please log in again.');
        }

        return back()->with('success', 'User role updated');
    }
}
