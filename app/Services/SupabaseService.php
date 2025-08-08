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

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'application/octet-stream',
            ])->withBody($fileContent, 'application/octet-stream')
            ->put($this->url . '/storage/v1/object/' . $this->bucket . '/' . $filePath);

            if ($response->successful()) {
                Log::info('Arquivo enviado com sucesso para Supabase', [
                    'path' => $filePath,
                    'size' => strlen($fileContent)
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
                'path' => $filePath
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