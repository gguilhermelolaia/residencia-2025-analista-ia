<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConteudoController;

Route::post('/conteudos', [ConteudoController::class, 'store']);
Route::get('/conteudos', [ConteudoController::class, 'apiIndex']);

// NOVA ROTA: Permite o Python atualizar o status do pedido
Route::put('/conteudos/{id}', [ConteudoController::class, 'updateApi']);