<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all(),
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


    public function index1()
    {
        return Inertia::render('Users/Index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $user = User::create($request->only('name', 'email') + [
            'password' => bcrypt('password') // Default password
        ]);

        $user->assignRole($request->role);

        return redirect()->back();
    }

    public function updateRole(Request $request, User $user)
    {
        $user->syncRoles([$request->role]);
        return redirect()->back();
    }


    public function showRolePermissions()
    {
        $roles = Role::with('permissions')->get();

        return Inertia::render('Roles/RolePermissions', [
            'roles' => $roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                    'can_view_users' => $role->hasPermissionTo('view users'),
                    'can_assign_roles' => $role->hasPermissionTo('assign roles'),
                ];
            }),
        ]);
    }
}
