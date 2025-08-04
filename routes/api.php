<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas para gerenciamento de arquivos
Route::middleware('auth:sanctum')->prefix('files')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/list', [FileController::class, 'list']);
    Route::delete('/delete', [FileController::class, 'delete']);
});

Route::middleware('auth:sanctum')->prefix('storage')->group(function () {
    Route::post('/setup', [FileController::class, 'setupStorage']);
});