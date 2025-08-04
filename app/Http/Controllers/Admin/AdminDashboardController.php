<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::role('admin')->count(),
            'total_clients' => User::role('client')->count(),
        ];

        $recent_users = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }

    public function users()
    {
        $users = User::with('roles')->get();
        $totalUsers = User::count();
        $adminUsers = User::role('admin')->count();
        $clientUsers = User::role('client')->count();
        
        return view('admin.users', compact('users', 'totalUsers', 'adminUsers', 'clientUsers'));
    }



    public function settings()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::with('roles')->get();
        
        $stats = [
            'total_roles' => $roles->count(),
            'total_permissions' => $permissions->count(),
            'users_with_roles' => User::has('roles')->count(),
            'permission_assignments' => $permissions->sum(function($permission) {
                return $permission->roles->count();
            })
        ];
        
        return view('admin.settings', compact('roles', 'permissions', 'stats'));
    }
}
