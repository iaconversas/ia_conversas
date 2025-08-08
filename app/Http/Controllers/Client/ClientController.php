<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Password;

class ClientController extends Controller
{
    protected $evolutionApiService;

    public function __construct(EvolutionApiService $evolutionApiService)
    {
        $this->evolutionApiService = $evolutionApiService;
    }

    /**
     * Exibe o dashboard do cliente
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Buscar instâncias ativas
        $activeInstances = 0;
        try {
            $instances = $this->evolutionApiService->fetchInstances();
            if (is_array($instances)) {
                $activeInstances = count(array_filter($instances, function($instance) {
                    return isset($instance['connectionStatus']) && $instance['connectionStatus'] === 'open';
                }));
            }
        } catch (\Exception $e) {
            // Em caso de erro, manter 0 instâncias ativas
            $activeInstances = 0;
        }
        
        // Contar disparos realizados através dos logs
        $totalDisparos = $this->countUserDispatches();
        
        // Calcular taxa de desempenho (assumindo 98,5% como exemplo)
        $performanceRate = $totalDisparos > 0 ? '98,5%' : '0%';
        
        // Estatísticas básicas do usuário
        $stats = [
            'active_instances' => $activeInstances,
            'total_dispatches' => $totalDisparos,
            'leads_found' => 0, // Placeholder para leads encontrados
            'performance_rate' => $performanceRate,
            'member_since' => $user->created_at->format('d/m/Y'),
            'last_access' => $user->updated_at->diffForHumans(),
            'my_roles' => $user->roles->pluck('name')->toArray(),
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

        // Atualizar dados do usuário
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password
        ]);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
    
    /**
     * Conta o número de disparos realizados pelo usuário através dos logs
     */
    private function countUserDispatches()
    {
        try {
            $userId = Auth::id();
            $logPath = storage_path('logs/laravel.log');
            
            if (!File::exists($logPath)) {
                return 0;
            }
            
            $logContent = File::get($logPath);
            
            // Contar ocorrências de "Disparo iniciado" para o usuário específico
            $pattern = '/Disparo iniciado.*user_id:' . $userId . '/i';
            preg_match_all($pattern, $logContent, $matches);
            
            return count($matches[0]);
        } catch (\Exception $e) {
            // Em caso de erro, retornar 0
            return 0;
        }
    }
}