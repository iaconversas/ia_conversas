<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\EvolutionApiService;
use App\Services\ApifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    protected $evolutionApiService;
    protected $apifyService;

    public function __construct(EvolutionApiService $evolutionApiService, ApifyService $apifyService)
    {
        $this->evolutionApiService = $evolutionApiService;
        $this->apifyService = $apifyService;
    }

    public function evolutionManager()
    {
        $config = $this->evolutionApiService->getConfig();
        
        return view('client.evolution-manager', compact('config'));
    }



    public function leadHunter()
    {
        return view('client.lead-hunter');
    }

    public function gerenciarArquivos()
    {
        return view('client.gerenciar-arquivos');
    }

    /**
     * API endpoint seguro para buscar instâncias
     */
    public function fetchInstances()
    {
        try {
            $instances = $this->evolutionApiService->fetchInstances();
            return response()->json([
                'success' => true,
                'instances' => $instances
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar instâncias: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint seguro para conectar instância
     */
    public function connectInstance(Request $request, $instanceName)
    {
        try {
            $result = $this->evolutionApiService->connectInstance($instanceName);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao conectar instância'], 500);
        }
    }

    /**
     * API endpoint seguro para desconectar instância
     */
    public function logoutInstance(Request $request, $instanceName)
    {
        try {
            $result = $this->evolutionApiService->logoutInstance($instanceName);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao desconectar instância'], 500);
        }
    }

    /**
     * API endpoint seguro para deletar instância
     */
    public function deleteInstance(Request $request, $instanceName)
    {
        try {
            $result = $this->evolutionApiService->deleteInstance($instanceName);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar instância'], 500);
        }
    }

    /**
     * API endpoint seguro para configurar webhook
     */
    public function setWebhook(Request $request, $instanceName)
    {
        try {
            $request->validate([
                'webhook_url' => 'required|url'
            ]);

            $result = $this->evolutionApiService->setWebhook($instanceName, $request->webhook_url);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao configurar webhook'], 500);
        }
    }

    /**
     * Inicia captura de leads via Apify
     */
    public function startApifyLeadCapture(Request $request)
    {
        try {
            $request->validate([
                'search_query' => 'required|string|max:500',
                'max_results' => 'integer|min:1|max:100'
            ]);

            $searchQuery = $request->input('search_query');
            $maxResults = $request->input('max_results', 20);

            $result = $this->apifyService->runGoogleMapsActor($searchQuery, $maxResults);
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao iniciar captura de leads: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica status da execução Apify
     */
    public function checkApifyRunStatus($runId)
    {
        try {
            $result = $this->apifyService->getRunStatus($runId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao verificar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém resultados da execução Apify
     */
    public function getApifyRunResults($runId)
    {
        try {
            $result = $this->apifyService->getRunResults($runId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao obter resultados: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Testa conexão com Apify
     */
    public function testApifyConnection()
    {
        try {
            $result = $this->apifyService->testConnection();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao testar conexão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista atores disponíveis na Apify
     */
    public function listApifyActors()
    {
        try {
            $result = $this->apifyService->listActors();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao listar atores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar créditos disponíveis na conta Apify
     */
    public function checkApifyCredits()
    {
        try {
            $user = $this->apifyService->getUser();
            
            if ($user && isset($user['usage']['monthlyUsageUsd'])) {
                $monthlyLimit = $user['plan']['monthlyUsageUsd'] ?? 0;
                $usedCredits = $user['usage']['monthlyUsageUsd'] ?? 0;
                $availableCredits = $monthlyLimit - $usedCredits;
                
                return response()->json([
                    'success' => true,
                    'credits' => max(0, $availableCredits),
                    'used' => $usedCredits,
                    'limit' => $monthlyLimit,
                    'plan' => $user['plan']['name'] ?? 'Unknown'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível obter informações de créditos'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar créditos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualizar token Apify
     */
    public function updateApifyToken(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string|min:10'
            ]);
            
            $token = $request->input('token');
            
            // Testar o token antes de salvar
            $tempService = new \App\Services\ApifyService();
            $tempService->setToken($token);
            
            $testResult = $tempService->testConnection();
            
            if (!$testResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido: ' . $testResult['message']
                ]);
            }
            
            // Salvar o token no arquivo .env
            $this->updateEnvFile('APIFY_TOKEN', $token);
            
            // Limpar cache de configuração
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Token atualizado com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar token: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Atualizar arquivo .env
     */
    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            throw new \Exception('Arquivo .env não encontrado');
        }
        
        $envContent = file_get_contents($envFile);
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";
        
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$replacement}";
        }
        
        file_put_contents($envFile, $envContent);
    }
    
    /**
     * Página de aquecimento de chip
     */
    public function aquecimentoChip()
    {
        return view('client.aquecimento-chip');
    }
    
    /**
     * Executar aquecimento de chip
     */
    public function executarAquecimento(Request $request)
    {
        // Aumentar o tempo limite de execução para 300 segundos (5 minutos)
        set_time_limit(300);
        
        try {
            $request->validate([
                'instances' => 'required|array',
                'interactions' => 'required|integer|min:1|max:100'
            ]);
            
            $webhookUrl = env('N8N_WEBHOOK_AQUECEDOR');
            
            if (!$webhookUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Webhook do n8n não configurado. Configure N8N_WEBHOOK_AQUECEDOR no arquivo .env'
                ], 500);
            }
            
            $data = [
                'instancias' => $request->instances,
                'interactions' => $request->interactions,
                'apikey_global' => env('EVOLUTION_API_KEY'),
                'url_evolution' => env('EVOLUTION_BASE_URL')
            ];
            
            $response = Http::timeout(120)->post($webhookUrl, $data);
            
            if ($response->successful()) {
                // Tentar fazer parse do JSON, se falhar, retornar o body como string
                try {
                    $responseData = $response->json();
                } catch (\Exception $e) {
                    $responseData = $response->body();
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Aquecimento iniciado com sucesso!',
                    'data' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao iniciar aquecimento: ' . $response->body()
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
}