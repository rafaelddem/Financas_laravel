<?php

use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionTypeController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\OwnerController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\MovementTypeController;

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

Route::get('/', function () {return view('index');});

Route::group(['prefix' => 'metodo-de-pagamento', 'as' => 'payment-method.'], function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('list');
    Route::get('/novo', [PaymentMethodController::class, 'create'])->name('create');
    Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
    Route::put('/', [PaymentMethodController::class, 'update'])->name('update');
    Route::delete('/', [PaymentMethodController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'tipo-de-transacao', 'as' => 'transaction-type.'], function () {
    Route::get('/', [TransactionTypeController::class, 'index'])->name('list');
    Route::get('/novo', [TransactionTypeController::class, 'create'])->name('create');
    Route::post('/', [TransactionTypeController::class, 'store'])->name('store');
    Route::post('/atualizar', [TransactionTypeController::class, 'edit'])->name('edit');
    Route::put('/', [TransactionTypeController::class, 'update'])->name('update');
    Route::delete('/', [TransactionTypeController::class, 'destroy'])->name('destroy');
});







Route::group(['prefix' => 'carteira', 'as' => 'wallet.'], function () {
    Route::get('/', [WalletController::class, 'index'])->name('list');
    Route::post('/', [WalletController::class, 'store'])->name('create');
    Route::put('/', [WalletController::class, 'update'])->name('update');
    Route::delete('/', [WalletController::class, 'destroy'])->name('delete');
});

Route::group(['prefix' => 'dono', 'as' => 'owner.'], function () {
    Route::get('/', [OwnerController::class, 'index'])->name('list');
    Route::post('/', [OwnerController::class, 'store'])->name('create');
    Route::put('/', [OwnerController::class, 'update'])->name('update');
});





// Route::prefix('movimento')->group(function () {
//     Route::get('/', [MovementController::class, 'index'])->name('listMovements');
//     Route::get('/cadastro', [MovementController::class, 'create'])->name('createMovements');
//     // Route::get('/{id}', [MovimentoController::class, 'index'])->name('findMovement');
//     Route::post('/novo', [MovementController::class, 'store'])->name('storeMovement');
//     // Route::post('/{id}/atualizar', [MovimentoController::class, 'update'])->name('updateMovement');
//     // Route::post('/{id}/excluir', [MovimentoController::class, 'destroy'])->name('deleteMovement');
// });

// Route::prefix('pagamento')->group(function () {
//     Route::get('/', [InstallmentController::class, 'index'])->name('listInstallment');
//     Route::get('/{movement}-{installment_number}', [InstallmentController::class, 'index'])->name('findInstallment');
//     Route::get('/12', [InstallmentController::class, 'index'])->name('deleteInstallment');
// });
