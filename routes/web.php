<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\WhatsAppController;
use App\Http\Controllers\DisparoController;
use App\Http\Controllers\FigmaExampleController;

// Rota principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rota de exemplo do Figma
Route::get('/figma-example', function () {
    return view('figma-dashboard');
})->middleware(['auth'])->name('figma-example');

// Rota de redirecionamento baseada no role do usuário
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::check() && Auth::user()->hasRole('client')) {
        return redirect()->route('client.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas de configurações gerais
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    // Rota para gerar token de API
    Route::post('/api/generate-token', function () {
        $user = Auth::user();
        $token = $user->createToken('file-manager-token')->plainTextToken;
        return response()->json(['token' => $token]);
    })->name('api.generate-token');
});

// Rotas do Disparador Inteligente (webhooks públicos)
Route::post('/webhook/disparar', [DisparoController::class, 'webhook'])->name('webhook.disparar');
Route::get('/disparo/instances', [DisparoController::class, 'getInstances'])->middleware(['auth', 'client']);

// Rotas do Administrador
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
        Route::get('/evolution-api', [AdminDashboardController::class, 'evolutionApi'])->name('evolution-api');
        Route::put('/evolution-settings', [AdminDashboardController::class, 'updateEvolutionSettings'])->name('evolution-settings.update');
    });

// Rotas do Cliente
Route::prefix('client')
    ->name('client.')
    ->middleware(['auth', 'client'])
    ->group(function () {
        // Dashboard e Perfil
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
        Route::patch('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
        
        // Evolution Manager e API
        Route::get('/evolution-manager', [WhatsAppController::class, 'evolutionManager'])->name('evolution-manager');
        Route::get('/api/instances', [WhatsAppController::class, 'fetchInstances'])->name('api.instances');
        Route::post('/api/instances/{instanceName}/connect', [WhatsAppController::class, 'connectInstance'])->name('api.instances.connect');
        Route::post('/api/instances/{instanceName}/logout', [WhatsAppController::class, 'logoutInstance'])->name('api.instances.logout');
        Route::delete('/api/instances/{instanceName}', [WhatsAppController::class, 'deleteInstance'])->name('api.instances.delete');
        Route::post('/api/instances/{instanceName}/webhook', [WhatsAppController::class, 'setWebhook'])->name('api.instances.webhook');
        
        // Lead Hunter e Gerenciamento de Arquivos
        Route::get('/lead-hunter', [WhatsAppController::class, 'leadHunter'])->name('lead-hunter');
        Route::get('/gerenciar-arquivos', [WhatsAppController::class, 'gerenciarArquivos'])->name('gerenciar-arquivos');
        
        // Integração Apify
        Route::prefix('apify')->name('apify.')->group(function () {
            Route::post('/start-capture', [WhatsAppController::class, 'startApifyLeadCapture'])->name('start-capture');
            Route::get('/run-status/{runId}', [WhatsAppController::class, 'checkApifyRunStatus'])->name('run-status');
            Route::get('/run-results/{runId}', [WhatsAppController::class, 'getApifyRunResults'])->name('run-results');
            Route::get('/test-connection', [WhatsAppController::class, 'testApifyConnection'])->name('test-connection');
            Route::get('/list-actors', [WhatsAppController::class, 'listApifyActors'])->name('list-actors');
            Route::get('/credits', [WhatsAppController::class, 'checkApifyCredits'])->name('credits');
            Route::post('/update-token', [WhatsAppController::class, 'updateApifyToken'])->name('update-token');
        });
        
        // Aquecimento de Chip
        Route::get('/aquecimento-chip', [WhatsAppController::class, 'aquecimentoChip'])->name('aquecimento-chip');
        Route::post('/aquecimento-chip/executar', [WhatsAppController::class, 'executarAquecimento'])->name('aquecimento-chip.executar');
        
        // Disparador Inteligente
        Route::get('/disparo-inteligente', [DisparoController::class, 'index'])->name('disparo-inteligente');
    });

// Rotas de Autenticação
require __DIR__.'/auth.php';