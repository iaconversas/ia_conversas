<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ClientDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'client']);
    }

    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_users' => User::count(),
            'my_permissions' => $user->getAllPermissions()->pluck('name'),
            'my_roles' => $user->getRoleNames(),
        ];

        return view('client.dashboard', compact('stats', 'user'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('client.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only(['name', 'email']));

        return redirect()->route('client.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function users()
    {
        $user = auth()->user();
        $users = User::with('roles')->get();
            
        $stats = [
            'total_users' => $users->count(),
            'client_users' => $users->filter(function($u) { return $u->hasRole('client'); })->count(),
            'admin_users' => $users->filter(function($u) { return $u->hasRole('admin'); })->count(),
        ];

        return view('client.users', compact('users', 'stats', 'user'));
    }
}
