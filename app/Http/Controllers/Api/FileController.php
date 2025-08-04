<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            
            // Fazer upload para o storage
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
                    'uploaded_at' => now()->toISOString()
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
            
            $basePath = "user-media/{$userId}";
            
            // Verificar se o diretório existe
            if (!Storage::disk('public')->exists($basePath)) {
                return response()->json([
                    'success' => true,
                    'files' => [],
                    'stats' => [
                        'total' => 0,
                        'images' => 0,
                        'videos' => 0,
                        'audios' => 0,
                        'others' => 0,
                        'total_size' => 0
                    ]
                ]);
            }

            $files = [];
            $stats = [
                'total' => 0,
                'images' => 0,
                'videos' => 0,
                'audios' => 0,
                'others' => 0,
                'total_size' => 0
            ];

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

            foreach ($directories as $dir) {
                if (!Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->makeDirectory($dir);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Storage configurado com sucesso',
                'directories' => $directories
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
            'url' => Storage::disk('public')->url($filePath),
            'size' => $size,
            'size_formatted' => $this->formatBytes($size),
            'category' => $category,
            'modified_at' => $lastModified,
            'modified_at_formatted' => date('d/m/Y H:i', $lastModified)
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
}