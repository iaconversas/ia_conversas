<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    private $url;
    private $key;
    private $bucket;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->key = config('services.supabase.key');
        $this->bucket = config('services.supabase.bucket', 'user-files');
    }

    /**
     * Upload file to Supabase Storage
     */
    public function uploadFile($filePath, $fileName, $fileContent)
    {
        try {
            // Verificar se o conteúdo do arquivo é válido
            if (empty($fileContent)) {
                throw new \Exception('Conteúdo do arquivo está vazio');
            }

            // Detectar o tipo MIME correto baseado na extensão do arquivo
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $contentType = $this->getMimeType($extension);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => $contentType,
            ])->withBody($fileContent, $contentType)
            ->put($this->url . '/storage/v1/object/' . $this->bucket . '/' . $filePath);

            if ($response->successful()) {
                Log::info('Arquivo enviado com sucesso para Supabase', [
                    'path' => $filePath,
                    'size' => strlen($fileContent),
                    'content_type' => $contentType
                ]);
                
                return [
                    'success' => true,
                    'path' => $filePath,
                    'url' => $this->getPublicUrl($filePath)
                ];
            }

            $errorMessage = $response->body();
            Log::error('Supabase upload error', [
                'status' => $response->status(),
                'response' => $errorMessage,
                'path' => $filePath,
                'content_type' => $contentType
            ]);
            
            return ['success' => false, 'error' => $errorMessage];

        } catch (\Exception $e) {
            Log::error('Supabase upload exception', [
                'error' => $e->getMessage(),
                'path' => $filePath,
                'file' => $fileName
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($extension)
    {
        $mimeTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'json' => 'application/json',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Delete file from Supabase Storage
     */
    public function deleteFile($filePath)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->delete(
                $this->url . '/storage/v1/object/' . $this->bucket . '/' . $filePath
            );

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Supabase delete exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * List files from Supabase Storage
     */
    public function listFiles($prefix = '')
    {
        try {
            $url = $this->url . '/storage/v1/object/list/' . $this->bucket;
            
            // Se há um prefixo, usar POST com o prefixo no body
            // Se não há prefixo, usar GET (mas isso pode falhar para alguns buckets)
            if (!empty($prefix)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->key,
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'prefix' => $prefix
                ]);
            } else {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->key,
                ])->get($url);
            }

            Log::info('Supabase list files request', [
                'prefix' => $prefix,
                'method' => !empty($prefix) ? 'POST' : 'GET',
                'status' => $response->status(),
                'url' => $url
            ]);

            if ($response->successful()) {
                $files = $response->json();
                Log::info('Supabase list files success', [
                    'prefix' => $prefix,
                    'count' => is_array($files) ? count($files) : 'not-array',
                    'files' => is_array($files) ? array_slice($files, 0, 3) : $files
                ]);
                return $files;
            }

            Log::warning('Supabase list files failed', [
                'prefix' => $prefix,
                'method' => !empty($prefix) ? 'POST' : 'GET',
                'status' => $response->status(),
                'error' => $response->body()
            ]);

            // Retornar null para indicar falha (diferente de array vazio)
            return null;

        } catch (\Exception $e) {
            Log::error('Supabase list exception', [
                'prefix' => $prefix,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get public URL for a file
     */
    public function getPublicUrl($filePath)
    {
        return $this->url . '/storage/v1/object/public/' . $this->bucket . '/' . $filePath;
    }

    /**
     * Get signed URL for private files
     */
    public function getSignedUrl($filePath, $expiresIn = 3600)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'application/json',
            ])->post(
                $this->url . '/storage/v1/object/sign/' . $this->bucket . '/' . $filePath,
                ['expiresIn' => $expiresIn]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $this->url . $data['signedURL'];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Supabase signed URL exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Download file content from Supabase Storage
     * Retorna o conteúdo binário do arquivo sem corrupção
     */
    public function downloadFile($filePath)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->timeout(30)->get(
                $this->url . '/storage/v1/object/' . $this->bucket . '/' . $filePath
            );

            if ($response->successful()) {
                Log::info('Arquivo baixado com sucesso do Supabase', [
                    'path' => $filePath,
                    'size' => strlen($response->body())
                ]);
                
                return [
                    'success' => true,
                    'content' => $response->body(),
                    'content_type' => $response->header('Content-Type'),
                    'size' => strlen($response->body())
                ];
            }

            Log::error('Supabase download error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'path' => $filePath
            ]);
            
            return ['success' => false, 'error' => $response->body()];

        } catch (\Exception $e) {
            Log::error('Supabase download exception', [
                'error' => $e->getMessage(),
                'path' => $filePath
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Serve file content from Supabase Storage with proper headers
     * Usado para servir imagens e outros arquivos sem corrupção
     */
    public function serveFile($filePath)
    {
        try {
            // Primeiro tentar a URL pública (mais eficiente)
            $publicUrl = $this->getPublicUrl($filePath);
            
            Log::info('Tentando servir arquivo via URL pública', [
                'path' => $filePath,
                'url' => $publicUrl
            ]);
            
            // Verificar se o arquivo existe fazendo uma requisição HEAD
            $headResponse = Http::timeout(10)->head($publicUrl);
            
            if ($headResponse->successful()) {
                // Verificar se o Content-Type retornado pelo Supabase está correto
                $supabaseContentType = $headResponse->header('Content-Type');
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $expectedContentType = $this->getMimeType($extension);
                
                // Se o Content-Type do Supabase não está correto, fazer download direto
                if ($supabaseContentType === 'application/octet-stream' && $expectedContentType !== 'application/octet-stream') {
                    // Continuar para download direto (não retornar aqui)
                } else {
                    Log::info('Arquivo servido via URL pública do Supabase', [
                        'path' => $filePath,
                        'url' => $publicUrl,
                        'content_type' => $supabaseContentType
                    ]);
                    
                    return [
                        'success' => true,
                        'type' => 'redirect',
                        'url' => $publicUrl
                    ];
                }
            }
            
            // Se a URL pública não funcionar, baixar o arquivo diretamente
            Log::warning('URL pública falhou, baixando arquivo diretamente', [
                'path' => $filePath,
                'head_status' => $headResponse->status(),
                'head_body' => $headResponse->body()
            ]);
            
            $downloadResult = $this->downloadFile($filePath);
            
            if ($downloadResult['success']) {
                // Detectar o Content-Type correto baseado na extensão
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $correctContentType = $this->getMimeType($extension);
                
                // Sobrescrever o Content-Type se necessário
                $downloadResult['content_type'] = $correctContentType;
                $downloadResult['type'] = 'direct'; // Adicionar tipo para download direto
                
                Log::info('Arquivo baixado diretamente com sucesso', [
                    'path' => $filePath,
                    'size' => $downloadResult['size'],
                    'content_type' => $correctContentType
                ]);
            }
            
            return $downloadResult;
            
        } catch (\Exception $e) {
            Log::error('Supabase serve file exception', [
                'error' => $e->getMessage(),
                'path' => $filePath,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback para download direto
            $fallbackResult = $this->downloadFile($filePath);
            if ($fallbackResult['success']) {
                $fallbackResult['type'] = 'direct';
            }
            return $fallbackResult;
        }
    }

    /**
     * Check if Supabase is configured and bucket is accessible
     */
    public function isConfigured()
    {
        if (empty($this->url) || empty($this->key)) {
            return false;
        }

        // Cache da verificação do bucket por 5 minutos
        $cacheKey = 'supabase_bucket_check_' . $this->bucket;
        $cached = cache($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }

        // Testar se o bucket está acessível fazendo um upload de teste
        try {
            $testContent = 'test-' . time();
            $testPath = 'test-connectivity/' . time() . '.txt';
            
            $response = Http::timeout(5)->withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'text/plain',
            ])->withBody($testContent, 'text/plain')
            ->put($this->url . '/storage/v1/object/' . $this->bucket . '/' . $testPath);

            $isAccessible = $response->successful();
            
            // Se o upload funcionou, tentar deletar o arquivo de teste
            if ($isAccessible) {
                try {
                    Http::timeout(3)->withHeaders([
                        'Authorization' => 'Bearer ' . $this->key,
                    ])->delete($this->url . '/storage/v1/object/' . $this->bucket . '/' . $testPath);
                } catch (\Exception $e) {
                    // Ignorar erro de deleção, o importante é que o upload funcionou
                }
            }
            
            // Cache o resultado por 5 minutos
            cache([$cacheKey => $isAccessible], now()->addMinutes(5));
            
            if (!$isAccessible) {
                Log::warning('Supabase bucket not accessible via upload test, using local storage', [
                    'bucket' => $this->bucket,
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
            } else {
                Log::info('Supabase bucket is accessible and ready for uploads', [
                    'bucket' => $this->bucket
                ]);
            }
            
            return $isAccessible;
            
        } catch (\Exception $e) {
            Log::warning('Supabase bucket connectivity test failed, using local storage', [
                'bucket' => $this->bucket,
                'error' => $e->getMessage()
            ]);
            
            // Cache resultado negativo por 1 minuto apenas
            cache([$cacheKey => false], now()->addMinute());
            return false;
        }
    }
}