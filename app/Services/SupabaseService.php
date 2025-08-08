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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'application/octet-stream',
            ])->put(
                $this->url . '/storage/v1/object/' . $this->bucket . '/' . $filePath,
                $fileContent
            );

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $filePath,
                    'url' => $this->getPublicUrl($filePath)
                ];
            }

            Log::error('Supabase upload error', ['response' => $response->body()]);
            return ['success' => false, 'error' => $response->body()];

        } catch (\Exception $e) {
            Log::error('Supabase upload exception', ['error' => $e->getMessage()]);
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->get(
                $this->url . '/storage/v1/object/list/' . $this->bucket,
                ['prefix' => $prefix]
            );

            if ($response->successful()) {
                return $response->json();
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Supabase list exception', ['error' => $e->getMessage()]);
            return [];
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
     * Check if Supabase is configured
     */
    public function isConfigured()
    {
        return !empty($this->url) && !empty($this->key);
    }
}