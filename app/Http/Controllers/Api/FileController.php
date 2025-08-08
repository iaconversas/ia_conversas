<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\SupabaseService;

class FileController extends Controller
{
    private $supabaseService;
    
    public function __construct(SupabaseService $supabaseService)
    {
        $this->middleware('auth:sanctum');
        $this->supabaseService = $supabaseService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB
            'category' => 'nullable|string|in:image,video,audio'
        ]);

        try {
            $file = $request->file('file');
            $userId = Auth::id();
            
            // Determinar categoria baseada no tipo MIME se não fornecida
            $category = $request->category;
            if (!$category) {
                $mimeType = $file->getMimeType();
                if (str_starts_with($mimeType, 'image/')) {
                    $category = 'image';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $category = 'video';
                } elseif (str_starts_with($mimeType, 'audio/')) {
                    $category = 'audio';
                } else {
                    $category = 'other';
                }
            }

            // Gerar nome único para o arquivo
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            
            // Caminho do arquivo no storage
            $path = "user-media/{$userId}/{$category}/{$fileName}";
            
            // Verificar se Supabase está configurado
            if ($this->supabaseService->isConfigured()) {
                // Upload para Supabase
                $supabasePath = "user-media/{$userId}/{$category}/{$fileName}";
                
                // Ler o arquivo como binário para evitar problemas de encoding
                $fileContent = file_get_contents($file->getPathname());
                
                // Verificar se o conteúdo foi lido corretamente
                if ($fileContent === false) {
                    throw new \Exception('Não foi possível ler o conteúdo do arquivo');
                }
                
                $result = $this->supabaseService->uploadFile($supabasePath, $fileName, $fileContent);
                
                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'file' => [
                            'id' => $fileName,
                            'name' => $originalName,
                            'path' => $result['path'],
                            'url' => $result['url'],
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType(),
                            'category' => $category,
                            'uploaded_at' => now()->toISOString(),
                            'storage' => 'supabase'
                        ]
                    ]);
                } else {
                    // Fallback para storage local se Supabase falhar
                    \Log::warning('Supabase upload failed, using local storage', ['error' => $result['error']]);
                }
            }
            
            // Fazer upload para o storage local (padrão ou fallback)
            $uploaded = Storage::disk('public')->put($path, file_get_contents($file));
            
            if (!$uploaded) {
                throw new \Exception('Falha ao fazer upload do arquivo');
            }

            // Retornar informações do arquivo
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $fileName,
                    'name' => $originalName,
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'category' => $category,
                    'uploaded_at' => now()->toISOString(),
                    'storage' => 'local'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {
            $userId = Auth::id();
            $category = $request->get('category');
            $search = $request->get('search');
            
            $files = [];
            $stats = [
                'total' => 0,
                'images' => 0,
                'videos' => 0,
                'audios' => 0,
                'others' => 0,
                'total_size' => 0
            ];
            
            $useLocalStorage = true;
            
            // Verificar se Supabase está configurado e tentar listar arquivos
            if ($this->supabaseService->isConfigured()) {
                try {
                    // Buscar arquivos em todas as categorias ou categoria específica
                    $categories = $category ? [$category] : ['image', 'video', 'audio', 'other'];
                    
                    foreach ($categories as $cat) {
                        // Listar arquivos do Supabase por categoria
                        $prefix = "user-media/{$userId}/{$cat}/";
                        $supabaseFiles = $this->supabaseService->listFiles($prefix);
                        
                        // Se conseguiu listar (mesmo que vazio), processar arquivos
                        if ($supabaseFiles !== null) {
                            $useLocalStorage = false;
                            
                            foreach ($supabaseFiles as $file) {
                                if (!isset($file['name']) || empty($file['name'])) continue;
                                
                                $filePath = $prefix . $file['name'];
                                $fileName = $file['name'];
                                
                                // Filtrar por categoria se especificada
                                if ($category && $cat !== $category) continue;
                            
                            $fileInfo = [
                                'id' => $fileName,
                                'name' => $fileName,
                                'original_name' => $fileName,
                                'path' => $filePath,
                                'url' => '/api/files/serve/' . $filePath,
                                'direct_url' => $this->supabaseService->getPublicUrl($filePath),
                                'size' => $file['metadata']['size'] ?? 0,
                                'type' => $file['metadata']['mimetype'] ?? 'application/octet-stream',
                                'category' => $cat,
                                'modified_at' => $file['updated_at'] ?? $file['created_at'] ?? now()->toISOString(),
                                'storage' => 'supabase'
                            ];
                            
                            // Filtrar por busca se fornecida
                            if ($search && !str_contains(strtolower($fileInfo['original_name']), strtolower($search))) {
                                continue;
                            }
                            
                                $files[] = $fileInfo;
                                $stats['total']++;
                                $stats[$cat === 'image' ? 'images' : ($cat === 'video' ? 'videos' : ($cat === 'audio' ? 'audios' : 'others'))]++;
                                $stats['total_size'] += $fileInfo['size'];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Log do erro e usar armazenamento local como fallback
                    \Log::warning('Falha ao listar arquivos do Supabase, usando armazenamento local: ' . $e->getMessage());
                    $useLocalStorage = true;
                }
            }
            
            // Usar armazenamento local se Supabase não estiver configurado ou falhar
            if ($useLocalStorage) {
                // Listar arquivos do storage local
                $basePath = "user-media/{$userId}";
                
                // Verificar se o diretório existe
                if (!Storage::disk('public')->exists($basePath)) {
                    return response()->json([
                        'success' => true,
                        'files' => [],
                        'stats' => $stats
                    ]);
                }

                // Buscar arquivos em todas as categorias ou categoria específica
                $categories = $category ? [$category] : ['image', 'video', 'audio', 'other'];
                
                foreach ($categories as $cat) {
                    $categoryPath = "{$basePath}/{$cat}";
                    
                    if (Storage::disk('public')->exists($categoryPath)) {
                        $categoryFiles = Storage::disk('public')->files($categoryPath);
                        
                        foreach ($categoryFiles as $filePath) {
                            $fileName = basename($filePath);
                            $fileInfo = $this->getFileInfo($filePath, $cat);
                            
                            // Filtrar por busca se fornecida
                            if ($search && !str_contains(strtolower($fileInfo['original_name']), strtolower($search))) {
                                continue;
                            }
                            
                            $files[] = $fileInfo;
                            $stats['total']++;
                            $stats[$cat === 'image' ? 'images' : ($cat === 'video' ? 'videos' : ($cat === 'audio' ? 'audios' : 'others'))]++;
                            $stats['total_size'] += $fileInfo['size'];
                        }
                    }
                }
            }

            // Ordenar por data de modificação (mais recente primeiro)
            usort($files, function($a, $b) {
                return $b['modified_at'] <=> $a['modified_at'];
            });

            return response()->json([
                'success' => true,
                'files' => $files,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar arquivos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $userId = Auth::id();
            $filePath = $request->path;
            
            // Verificar se o arquivo pertence ao usuário
            if (!str_starts_with($filePath, "user-media/{$userId}/")) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            // Tentar deletar do Supabase primeiro, se configurado
            if ($this->supabaseService->isConfigured()) {
                try {
                    $deleted = $this->supabaseService->deleteFile($filePath);
                    
                    if ($deleted) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Arquivo deletado com sucesso'
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log do erro e continua para o armazenamento local
                    \Log::warning('Falha ao deletar arquivo do Supabase: ' . $e->getMessage());
                }
            }

            // Fallback para armazenamento local
            // Verificar se o arquivo existe
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo não encontrado'
                ], 404);
            }

            // Deletar o arquivo
            $deleted = Storage::disk('public')->delete($filePath);
            
            if (!$deleted) {
                throw new \Exception('Falha ao deletar o arquivo');
            }

            return response()->json([
                'success' => true,
                'message' => 'Arquivo deletado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setupStorage(Request $request)
    {
        try {
            $userId = Auth::id();
            $basePath = "user-media/{$userId}";
            
            // Criar diretórios se não existirem
            $directories = [
                $basePath,
                "{$basePath}/image",
                "{$basePath}/video", 
                "{$basePath}/audio",
                "{$basePath}/other"
            ];

            $storageType = 'local';
            
            // Verificar se Supabase está configurado
            if ($this->supabaseService->isConfigured()) {
                $storageType = 'supabase';
                // Para Supabase, os diretórios são criados automaticamente no upload
                \Log::info('Supabase configurado para usuário: ' . $userId);
            } else {
                // Criar diretórios no armazenamento local
                foreach ($directories as $dir) {
                    if (!Storage::disk('public')->exists($dir)) {
                        Storage::disk('public')->makeDirectory($dir);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Storage configurado com sucesso',
                'directories' => $directories,
                'storage_type' => $storageType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao configurar storage: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getFileInfo($filePath, $category)
    {
        $fileName = basename($filePath);
        $size = Storage::disk('public')->size($filePath);
        $lastModified = Storage::disk('public')->lastModified($filePath);
        
        // Tentar extrair nome original (assumindo formato UUID.extensão)
        $originalName = $fileName;
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.(.+)$/i', $fileName, $matches)) {
            $originalName = 'arquivo.' . $matches[1];
        }

        return [
            'id' => $fileName,
            'name' => $fileName,
            'original_name' => $originalName,
            'path' => $filePath,
            'url' => '/api/files/serve/' . $filePath,
            'direct_url' => Storage::disk('public')->url($filePath),
            'size' => $size,
            'size_formatted' => $this->formatBytes($size),
            'category' => $category,
            'modified_at' => $lastModified,
            'modified_at_formatted' => date('d/m/Y H:i', $lastModified),
            'storage' => 'local'
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function serveFile(Request $request, $path)
    {
        try {
            // Tentar autenticação via token na query string primeiro
            $token = $request->query('token');
            $userId = null;
            
            if ($token) {
                // Verificar se é um token de API
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($accessToken && $accessToken->tokenable) {
                    $userId = $accessToken->tokenable->id;
                } else {
                    // Fallback para verificação de CSRF token (se necessário)
                    // Por enquanto, vamos tentar autenticação normal
                    $userId = Auth::id();
                }
            } else {
                $userId = Auth::id();
            }
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autenticado'
                ], 401);
            }
            
            // Verificar se o arquivo pertence ao usuário
            if (!str_starts_with($path, "user-media/{$userId}/")) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado'
                ], 403);
            }

            $forceDownload = $request->query('download') === '1';
            
            // Tentar servir do Supabase primeiro, se configurado
            if ($this->supabaseService->isConfigured()) {
                $publicUrl = $this->supabaseService->getPublicUrl($path);
                
                if ($forceDownload) {
                    // Para download forçado, fazer proxy do arquivo
                    $fileResponse = \Illuminate\Support\Facades\Http::get($publicUrl);
                    if ($fileResponse->successful()) {
                        $fileName = basename($path);
                        return response($fileResponse->body())
                            ->header('Content-Type', $fileResponse->header('Content-Type') ?? 'application/octet-stream')
                            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                            ->header('Cache-Control', 'no-cache');
                    }
                } else {
                    return redirect($publicUrl);
                }
            }

            // Fallback para armazenamento local
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo não encontrado'
                ], 404);
            }

            $fileName = basename($path);
            
            if ($forceDownload) {
                $filePath = Storage::disk('public')->path($path);
                return response()->download($filePath, $fileName);
            } else {
                $file = Storage::disk('public')->get($path);
                $mimeType = Storage::disk('public')->mimeType($path);

                return response($file, 200, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                    'Cache-Control' => 'public, max-age=3600',
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao servir arquivo: ' . $e->getMessage()
            ], 500);
        }
    }
}