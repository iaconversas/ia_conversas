<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Services\EvolutionApiService;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas para gerenciamento de arquivos
Route::middleware('auth:sanctum')->prefix('files')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/list', [FileController::class, 'list']);
    Route::delete('/delete', [FileController::class, 'delete']);
});

// Rota para servir arquivos (sem middleware auth pois usa autenticação via token na query string)
Route::get('/files/serve/{path}', [FileController::class, 'serveFile'])->where('path', '.*');

Route::middleware('auth:sanctum')->prefix('storage')->group(function () {
    Route::post('/setup', [FileController::class, 'setupStorage']);
});

// Rotas da Evolution API
Route::middleware(['web', 'auth'])->prefix('evolution')->group(function () {
    Route::get('/config', function () {
        $service = new EvolutionApiService();
        return response()->json($service->getConfig());
    });
    

});