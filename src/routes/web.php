<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConteudoController;
use App\Http\Controllers\LoginController;

// Rota Pública
Route::get('/', [ConteudoController::class, 'index'])->name('home');

// Rotas de Autenticação
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.do');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas Protegidas (Só entra se estiver logado)
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/', [ConteudoController::class, 'admin'])->name('admin.dashboard');
    
    // Ações de Gerenciamento
    Route::put('/{id}/aprovar', [ConteudoController::class, 'aprovar'])->name('conteudos.aprovar');
    Route::put('/{id}/update', [ConteudoController::class, 'update'])->name('conteudos.update');
    Route::delete('/{id}/reprovar', [ConteudoController::class, 'reprovar'])->name('conteudos.reprovar');
    
    // NOVA ROTA: Solicitar Análise via Dashboard
    Route::post('/solicitar', [ConteudoController::class, 'solicitar'])->name('conteudos.solicitar');
});