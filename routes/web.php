<?php

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\WalletController;
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

Route::get('/', function () {return view('index');});

Route::group(['prefix' => 'dono', 'as' => 'owner.'], function () {
    Route::get('/', [OwnerController::class, 'index'])->name('list');
    Route::post('/', [OwnerController::class, 'store'])->name('store');
    Route::put('/', [OwnerController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'carteira', 'as' => 'wallet.'], function () {
    Route::get('/', [WalletController::class, 'index'])->name('list');
    Route::get('/novo', [WalletController::class, 'create'])->name('create');
    Route::post('/', [WalletController::class, 'store'])->name('store');
    Route::get('/{id}', [WalletController::class, 'edit'])->name('edit');
    Route::put('/', [WalletController::class, 'update'])->name('update');
    Route::delete('/', [WalletController::class, 'destroy'])->name('destroy');
});

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
    Route::get('/{id}', [TransactionTypeController::class, 'edit'])->name('edit');
    Route::put('/', [TransactionTypeController::class, 'update'])->name('update');
    Route::delete('/', [TransactionTypeController::class, 'destroy'])->name('destroy');
});
