<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('index');});

Route::group(['prefix' => 'dono', 'as' => 'owner.'], function () {
    Route::get('/', [OwnerController::class, 'index'])->name('list');
    Route::post('/', [OwnerController::class, 'store'])->name('store');
    Route::put('/', [OwnerController::class, 'update'])->name('update');

    Route::group(['prefix' => '{owner_id}/carteira', 'as' => 'wallet.'], function () {
        Route::get('/', [WalletController::class, 'index'])->name('list');
        Route::get('/novo', [WalletController::class, 'create'])->name('create');
        Route::post('/', [WalletController::class, 'store'])->name('store');
        Route::get('/{id}', [WalletController::class, 'edit'])->name('edit');
        Route::put('/', [WalletController::class, 'update'])->name('update');
        Route::delete('/', [WalletController::class, 'destroy'])->name('destroy');

        Route::group(['prefix' => '{wallet_id}/cartao', 'as' => 'card.'], function () {
            Route::get('/', [CardController::class, 'index'])->name('list');
            Route::get('/novo', [CardController::class, 'create'])->name('create');
            Route::post('/', [CardController::class, 'store'])->name('store');
            Route::get('/{id}', [CardController::class, 'edit'])->name('edit');
            Route::put('/', [CardController::class, 'update'])->name('update');
        });
    });
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
