<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EvolutionApiService;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Instancia;

class DisparoController extends Controller
{
    protected $evolutionApiService;

    public function __construct(EvolutionApiService $evolutionApiService)
    {
        $this->evolutionApiService = $evolutionApiService;
    }

    /**
     * Exibe a página do disparador inteligente
     */
    public function index()
    {
        $config = $this->evolutionApiService->getConfig(); // Safe config without API keys
        
        return view('disparo.index', compact('config'));
    }

    /**
     * Processa o webhook de disparo
     */
    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            
            // Validação básica
            if (!isset($data['numero']) || !isset($data['mensagens']) || !isset($data['instancia'])) {
                return response()->json([
                    'success' => false,
                    'mensagem' => 'Dados obrigatórios não fornecidos'
                ], 400);
            }

            $numero = $data['numero'];
            $mensagens = $data['mensagens'];
            $instancia = $data['instancia'];
            $tempoMin = $data['tempoMin'] ?? 2;
            $tempoMax = $data['tempoMax'] ?? 5;
            $usarIA = $data['usar_ia'] ?? false;
    

            // Log do disparo
            Log::info('Disparo iniciado', [
                'numero' => $numero,
                'instancia' => $instancia,
                'quantidade_mensagens' => count($mensagens)
            ]);

            $config = $this->evolutionApiService->getFullConfig(); // Full config needed for backend operations
            $baseUrl = $config['evolution_url'];
            $apiKey = $config['evolution_api_key'];

            $sucessos = [];
            $erros = [];

            foreach ($mensagens as $mensagem) {
                try {
                    $textoFinal = $mensagem['text'];
                    
                    // Se usar IA estiver habilitado e for mensagem de texto, variar o texto
                    if ($usarIA && $mensagem['type'] === 'texto' && !empty($textoFinal)) {
                        $textoVariado = $this->variarTextoComIA($textoFinal);
                        if ($textoVariado) {
                            $textoFinal = $textoVariado;
                        }
                    }
                    
                    $endpoint = "{$baseUrl}/message/sendText/{$instancia}";
                    $payload = [
                        'number' => $numero,
                        'text' => $textoFinal
                    ];

                    // Se for imagem, áudio ou vídeo
                    if ($mensagem['type'] !== 'texto') {
                        $endpoint = "{$baseUrl}/message/sendMedia/{$instancia}";
                        $payload = [
                            'number' => $numero,
                            'mediaMessage' => [
                                'mediatype' => $this->mapMediaType($mensagem['type']),
                                'media' => $mensagem['text']
                            ]
                        ];
                    }

                    $response = Http::withHeaders([
                        'apikey' => $apiKey,
                        'Content-Type' => 'application/json'
                    ])->post($endpoint, $payload);

                    if ($response->successful()) {
                        $sucessos[] = "Mensagem enviada com sucesso";
                    } else {
                        $erros[] = "Erro ao enviar mensagem: " . $response->body();
                    }

                    // Aguarda entre mensagens
                    if (count($mensagens) > 1) {
                        $tempo = rand($tempoMin, $tempoMax);
                        sleep($tempo);
                    }

                } catch (\Exception $e) {
                    $erros[] = "Erro ao processar mensagem: " . $e->getMessage();
                    Log::error('Erro no disparo', [
                        'numero' => $numero,
                        'erro' => $e->getMessage()
                    ]);
                }
            }

            $resultado = [
                'success' => count($sucessos) > 0,
                'mensagem' => count($sucessos) > 0 ? 
                    "Enviado com sucesso para {$numero}" : 
                    "Falha ao enviar para {$numero}",
                'sucessos' => $sucessos,
                'erros' => $erros
            ];

            return response()->json($resultado);

        } catch (\Exception $e) {
            Log::error('Erro no webhook de disparo', [
                'erro' => $e->getMessage(),
                'dados' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'mensagem' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Varia o texto usando IA através do n8n
     */
    private function variarTextoComIA($texto)
    {
        try {
            // URL do webhook do n8n para variação de texto
            $n8nWebhookUrl = env('N8N_WEBHOOK_URL_VARIAR_TEXTO');
            
            if (empty($n8nWebhookUrl)) {
                Log::warning('URL do webhook n8n não configurada para variação de texto');
                return null;
            }
            
            $response = Http::timeout(30)->post($n8nWebhookUrl, [
                'texto_original' => $texto,
                'prompt' => 'Reescreva esta mensagem mantendo o mesmo significado, mas com palavras diferentes para evitar detecção de spam. Mantenha o tom e a intenção originais.'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $textoVariado = $data['texto_variado'] ?? $data['resultado'] ?? null;
                
                if (!empty($textoVariado) && $textoVariado !== $texto) {
                    Log::info('Texto variado com IA', [
                        'original' => $texto,
                        'variado' => $textoVariado
                    ]);
                    return $textoVariado;
                }
            } else {
                Log::error('Erro na resposta do n8n para variação de texto', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao variar texto com IA', [
                'erro' => $e->getMessage(),
                'texto' => $texto
            ]);
        }
        
        return null;
    }

    /**
     * Mapeia o tipo de mídia para o formato da Evolution API
     */
    private function mapMediaType($type)
    {
        $mapping = [
            'imagem' => 'image',
            'audio' => 'audio',
            'video' => 'video'
        ];

        return $mapping[$type] ?? 'image';
    }

    /**
     * Retorna as instâncias disponíveis
     */
    public function getInstances()
    {
        try {
            // Usar o EvolutionApiService para buscar instâncias
            $instances = $this->evolutionApiService->fetchInstances();
            
            if (empty($instances)) {
                return response()->json([], 200);
            }
            
            // Filtra apenas instâncias conectadas e formata os dados
            $connectedInstances = array_filter($instances, function($instance) {
                return ($instance['connectionStatus'] ?? '') === 'open';
            });
            
            // Formatar dados para o frontend (similar ao Evolution Manager)
            $formattedInstances = array_map(function($instance) {
                return [
                    'id' => $instance['id'] ?? '',
                    'name' => $instance['name'] ?? $instance['instanceName'] ?? 'Nome não disponível',
                    'connectionStatus' => $instance['connectionStatus'] ?? 'closed',
                    'profileName' => $instance['profileName'] ?? null,
                    'profilePicUrl' => $instance['profilePicUrl'] ?? null,
                    'ownerJid' => $instance['ownerJid'] ?? null
                ];
            }, $connectedInstances);

            return response()->json(array_values($formattedInstances));

        } catch (\Exception $e) {
            Log::error('Erro ao buscar instâncias', ['erro' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }


}