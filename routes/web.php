<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\WhatsAppController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rota de redirecionamento baseada no role do usuário
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->hasRole('client')) {
        return redirect()->route('client.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Rotas do Administrador
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('/evolution-api', [AdminDashboardController::class, 'evolutionApi'])->name('evolution-api');
    Route::put('/evolution-settings', [AdminDashboardController::class, 'updateEvolutionSettings'])->name('evolution-settings.update');
});

// Rotas do Cliente
Route::prefix('client')->name('client.')->middleware(['auth', 'client'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
    Route::get('/evolution-manager', [WhatsAppController::class, 'evolutionManager'])->name('evolution-manager');
    Route::get('/disparo-inteligente', [WhatsAppController::class, 'disparoInteligente'])->name('disparo-inteligente');
    Route::get('/lead-hunter', [WhatsAppController::class, 'leadHunter'])->name('lead-hunter');
    Route::get('/sim-warming', [WhatsAppController::class, 'simWarming'])->name('sim-warming');
    Route::get('/gerenciar-arquivos', [WhatsAppController::class, 'gerenciarArquivos'])->name('gerenciar-arquivos');
});

require __DIR__.'/auth.php';
