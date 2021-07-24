<?php

use App\Http\Controllers\CarteiraController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\TipoMovimentoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {return view('index');});

Route::prefix('carteira')->group(function () {
    Route::get('/', [CarteiraController::class, 'index']);
    Route::get('/{id}', [CarteiraController::class, 'index']);
    Route::post('/novo', [CarteiraController::class, 'store']);
    Route::post('/{id}/atualizar', [CarteiraController::class, 'update']);
    Route::post('/{id}/excluir', [CarteiraController::class, 'destroy']);
});

Route::prefix('forma')->group(function () {
    Route::get('/', [FormaPagamentoController::class, 'index']);
    Route::get('/{id}', [FormaPagamentoController::class, 'index']);
    Route::post('/novo', [FormaPagamentoController::class, 'store']);
    Route::post('/{id}/atualizar', [FormaPagamentoController::class, 'update']);
    Route::post('/{id}/excluir', [FormaPagamentoController::class, 'destroy']);
});

Route::prefix('movimento')->group(function () {
    Route::get('/', [PessoaController::class, 'index']);
    Route::get('/{id}', [PessoaController::class, 'index']);
    Route::post('/novo', [PessoaController::class, 'store']);
    Route::post('/{id}/atualizar', [PessoaController::class, 'update']);
    Route::post('/{id}/excluir', [PessoaController::class, 'destroy']);
});

Route::prefix('pessoa')->group(function () {
    Route::get('/', [PessoaController::class, 'index']);
    Route::get('/{id}', [PessoaController::class, 'index']);
    Route::post('/novo', [PessoaController::class, 'store']);
    Route::post('/{id}/atualizar', [PessoaController::class, 'update']);
    Route::post('/{id}/excluir', [PessoaController::class, 'destroy']);
});

Route::prefix('tipo')->group(function () {
    Route::get('/', [TipoMovimentoController::class, 'index']);
    Route::get('/{id}', [TipoMovimentoController::class, 'index']);
    Route::post('/novo', [TipoMovimentoController::class, 'store']);
    Route::post('/{id}/atualizar', [TipoMovimentoController::class, 'update']);
    Route::post('/{id}/excluir', [TipoMovimentoController::class, 'destroy']);
});