<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ClientController extends Controller
{
    /**
     * Exibe o dashboard do cliente
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Estatísticas básicas do usuário
        $stats = [
            'total_users' => \App\Models\User::where('id', $user->id)->count(),
            'total_roles' => $user->roles->count(),
            'total_permissions' => $user->getAllPermissions()->count(),
            'member_since' => $user->created_at->format('d/m/Y'),
            'last_access' => $user->updated_at->diffForHumans(),
            'my_roles' => $user->roles->pluck('name')->toArray(),
            'my_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];
        
        return view('client.dashboard', compact('user', 'stats'));
    }

    /**
     * Exibe o perfil do cliente
     */
    public function profile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }

    /**
     * Atualiza o perfil do cliente
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Atualizar dados básicos
        $user->name = $request->name;
        $user->email = $request->email;

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}