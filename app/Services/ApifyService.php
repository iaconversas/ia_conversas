<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ApifyService
{
    private $apiToken;
    private $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.apify.token');
        $this->baseUrl = 'https://api.apify.com/v2';
    }

    /**
     * Executa um ator da Apify para captura de leads do Google Maps
     */
    public function runGoogleMapsActor($searchQuery, $maxResults = 20, $language = 'pt-BR')
    {
        try {
            $actorId = config('services.apify.google_maps_actor_id', 'compass~crawler-google-places');
            
            $input = [
                'searchStringsArray' => [$searchQuery],
                'locationQuery' => 'Brasil',
                'maxCrawledPlacesPerSearch' => $maxResults,
                'language' => $language
            ];

            Log::info('Iniciando execução do ator Apify', [
                'actor_id' => $actorId,
                'search_query' => $searchQuery,
                'max_results' => $maxResults
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/acts/{$actorId}/runs", $input);

            if (!$response->successful()) {
                throw new Exception('Erro ao iniciar execução do ator: ' . $response->body());
            }

            $runData = $response->json();
            $runId = $runData['data']['id'];

            Log::info('Execução do ator iniciada', ['run_id' => $runId]);

            return [
                'success' => true,
                'run_id' => $runId,
                'status' => 'RUNNING',
                'message' => 'Execução iniciada com sucesso'
            ];

        } catch (Exception $e) {
            Log::error('Erro ao executar ator Apify', [
                'error' => $e->getMessage(),
                'search_query' => $searchQuery
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica o status de uma execução
     */
    public function getRunStatus($runId)
    {
        try {
            Log::info('Verificando status da execução', [
                'run_id' => $runId,
                'url' => "{$this->baseUrl}/actor-runs/{$runId}",
                'token_exists' => !empty($this->apiToken)
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken
            ])->get("{$this->baseUrl}/actor-runs/{$runId}");

            Log::info('Resposta da API Apify', [
                'status_code' => $response->status(),
                'successful' => $response->successful(),
                'run_id' => $runId
            ]);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Erro na resposta da API Apify', [
                    'status_code' => $response->status(),
                    'response_body' => $errorBody,
                    'run_id' => $runId
                ]);
                throw new Exception('Erro ao verificar status da execução: ' . $errorBody);
            }

            $runData = $response->json();
            $status = $runData['data']['status'];

            Log::info('Status obtido com sucesso', [
                'run_id' => $runId,
                'status' => $status
            ]);

            return [
                'success' => true,
                'status' => $status,
                'started_at' => $runData['data']['startedAt'] ?? null,
                'finished_at' => $runData['data']['finishedAt'] ?? null
            ];

        } catch (Exception $e) {
            Log::error('Erro ao verificar status da execução', [
                'error' => $e->getMessage(),
                'run_id' => $runId,
                'token_configured' => !empty($this->apiToken)
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtém os resultados de uma execução
     */
    public function getRunResults($runId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken
            ])->get("{$this->baseUrl}/actor-runs/{$runId}/dataset/items");

            if (!$response->successful()) {
                throw new Exception('Erro ao obter resultados da execução');
            }

            $results = $response->json();
            $processedResults = $this->processResults($results);

            Log::info('Resultados obtidos com sucesso', [
                'run_id' => $runId,
                'total_results' => count($processedResults)
            ]);

            return [
                'success' => true,
                'results' => $processedResults,
                'total' => count($processedResults)
            ];

        } catch (Exception $e) {
            Log::error('Erro ao obter resultados', [
                'error' => $e->getMessage(),
                'run_id' => $runId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Processa e normaliza os resultados da Apify
     */
    private function processResults($rawResults)
    {
        $processed = [];

        foreach ($rawResults as $item) {
            $phone = $this->extractPhone($item['phone'] ?? '');
            $email = $this->extractEmail($item['email'] ?? '');
            $website = $this->normalizeWebsite($item['website'] ?? '');

            $processed[] = [
                'nome' => $item['title'] ?? 'N/A',
                'telefone' => $phone,
                'email' => $email,
                'website' => $website,
                'endereco' => $item['address'] ?? '',
                'avaliacao' => $item['totalScore'] ?? null,
                'avaliacoes_count' => $item['reviewsCount'] ?? null,
                'categoria' => $item['categoryName'] ?? '',
                'horario_funcionamento' => $item['openingHours'] ?? null,
                'google_id' => $item['placeId'] ?? null,
                'latitude' => $item['location']['lat'] ?? null,
                'longitude' => $item['location']['lng'] ?? null
            ];
        }

        return $processed;
    }

    /**
     * Extrai e normaliza número de telefone
     */
    private function extractPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Remove todos os caracteres não numéricos
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Se não começar com 55 (código do Brasil), adiciona
        if (strlen($cleanPhone) >= 10 && !str_starts_with($cleanPhone, '55')) {
            $cleanPhone = '55' . $cleanPhone;
        }

        // Valida se tem pelo menos 12 dígitos (55 + DDD + número)
        if (strlen($cleanPhone) >= 12) {
            return $cleanPhone;
        }

        return null;
    }

    /**
     * Extrai e valida email
     */
    private function extractEmail($email)
    {
        if (empty($email)) {
            return null;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        return null;
    }

    /**
     * Normaliza URL do website
     */
    private function normalizeWebsite($website)
    {
        if (empty($website)) {
            return null;
        }

        // Adiciona http:// se não tiver protocolo
        if (!preg_match('/^https?:\/\//', $website)) {
            $website = 'http://' . $website;
        }

        return $website;
    }

    /**
     * Lista os atores disponíveis na conta
     */
    public function listActors()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken
            ])->get("{$this->baseUrl}/acts");

            if (!$response->successful()) {
                throw new Exception('Erro ao listar atores');
            }

            return [
                'success' => true,
                'actors' => $response->json()['data']['items'] ?? []
            ];

        } catch (Exception $e) {
            Log::error('Erro ao listar atores', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica se a API está configurada corretamente
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken
            ])->get("{$this->baseUrl}/users/me");

            if (!$response->successful()) {
                throw new Exception('Token inválido ou expirado');
            }

            return [
                'success' => true,
                'user' => $response->json()['data'] ?? null,
                'message' => 'Conexão com Apify estabelecida com sucesso'
            ];

        } catch (Exception $e) {
            Log::error('Erro ao testar conexão com Apify', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Obter informações do usuário da Apify
     */
    public function getUser()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken
            ])->get("{$this->baseUrl}/users/me");

            if (!$response->successful()) {
                throw new Exception('Erro ao obter informações do usuário');
            }

            return $response->json()['data'] ?? null;

        } catch (Exception $e) {
            Log::error('Erro ao obter informações do usuário Apify', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Definir token temporariamente (para testes)
     */
    public function setToken($token)
    {
        $this->apiToken = $token;
    }
}