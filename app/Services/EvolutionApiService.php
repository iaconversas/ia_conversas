<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionApiService
{
    protected $apiUrl;
    protected $apiKey;
    protected $n8nUrl;

    public function __construct()
    {
        $settings = SystemSetting::getEvolutionSettings();
        $this->apiUrl = $settings['api_url'];
        $this->apiKey = $settings['api_key'];
        $this->n8nUrl = $settings['n8n_url'];
    }

    /**
     * Obter configurações para uso no frontend
     */
    public function getConfig()
    {
        return [
            'evolution_url' => $this->apiUrl,
            'evolution_api_key' => $this->apiKey,
            'n8n_url' => $this->n8nUrl,
        ];
    }

    /**
     * Fazer requisição para a Evolution API
     */
    public function makeRequest(string $endpoint, string $method = 'GET', array $data = [])
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->{strtolower($method)}($this->apiUrl . $endpoint, $data);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Evolution API Request Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Buscar instâncias
     */
    public function fetchInstances()
    {
        return $this->makeRequest('/instance/fetchInstances');
    }

    /**
     * Criar instância
     */
    public function createInstance(string $instanceName)
    {
        return $this->makeRequest('/instance/create', 'POST', [
            'instanceName' => $instanceName,
            'token' => $this->apiKey,
        ]);
    }

    /**
     * Conectar instância
     */
    public function connectInstance(string $instanceName)
    {
        return $this->makeRequest("/instance/connect/{$instanceName}", 'GET');
    }

    /**
     * Desconectar instância
     */
    public function logoutInstance(string $instanceName)
    {
        return $this->makeRequest("/instance/logout/{$instanceName}", 'DELETE');
    }

    /**
     * Deletar instância
     */
    public function deleteInstance(string $instanceName)
    {
        return $this->makeRequest("/instance/delete/{$instanceName}", 'DELETE');
    }

    /**
     * Atualizar webhook
     */
    public function updateWebhook(string $instanceName, string $webhookUrl)
    {
        // Codificar o nome da instância para lidar com espaços e caracteres especiais
        $encodedInstanceName = urlencode($instanceName);
        
        // Remover espaços extras e caracteres de acento grave da URL do webhook
        $webhookUrl = trim($webhookUrl);
        // Remover caracteres de acento grave que podem estar presentes
        $webhookUrl = str_replace('`', '', $webhookUrl);
        // Remover aspas extras que podem estar presentes
        $webhookUrl = str_replace(["'", '"'], '', $webhookUrl);
        
        return $this->makeRequest("/webhook/set/{$encodedInstanceName}", 'POST', [
            'webhook' => [
                'url' => $webhookUrl,
                'webhook_by_events' => false,
                'webhook_base64' => false,
                'enabled' => true,
                'events' => [
                    'APPLICATION_STARTUP',
                    'QRCODE_UPDATED',
                    'CONNECTION_UPDATE',
                    'MESSAGES_UPSERT',
                    'MESSAGES_UPDATE',
                    'SEND_MESSAGE'
                ]
            ]
        ]);
    }

    /**
     * Fazer requisição para o n8n
     */
    public function makeN8nRequest(string $endpoint, string $method = 'POST', array $data = [])
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->{strtolower($method)}($this->n8nUrl . $endpoint, $data);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('n8n Request Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}