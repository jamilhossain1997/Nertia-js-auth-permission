<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all()
        ]);
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }
}
