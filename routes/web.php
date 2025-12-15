<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExtractImportController;
use App\Http\Controllers\ExtractModuleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionBaseController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/sign_in', [LoginController::class, 'signIn'])->name('sign_in');
Route::get('/sign_out', [LoginController::class, 'signOut'])->name('sign_out');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('home');
    
    Route::group(['prefix' => 'relatorios', 'as' => 'reports.'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/emprestimos', [ReportController::class, 'loans'])->name('loans');
    });
    
    Route::group(['prefix' => 'faturas', 'as' => 'invoice.'], function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('list');
        Route::get('/detalhes/{invoice_id}', [InvoiceController::class, 'details'])->name('details');
        Route::post('/', [InvoiceController::class, 'pay'])->name('pay');
    });
    
    Route::group(['prefix' => 'transacoes', 'as' => 'transaction.'], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('list');
        Route::get('/novo', [TransactionController::class, 'create'])->name('create');
        Route::get('/novo/{base}', [TransactionController::class, 'create'])->name('createByBase');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
    });
    
    Route::group(['prefix' => 'base-transacao', 'as' => 'transaction-base.'], function () {
        Route::get('/', [TransactionBaseController::class, 'index'])->name('list');
        Route::get('/novo', [TransactionBaseController::class, 'create'])->name('create');
        Route::post('/', [TransactionBaseController::class, 'store'])->name('store');
        Route::delete('/', [TransactionBaseController::class, 'destroy'])->name('destroy');
    });
    
    Route::group(['prefix' => 'modulo-extrato', 'as' => 'extract-module.'], function () {
        Route::get('/', [ExtractModuleController::class, 'index'])->name('index');
        Route::post('/', [ExtractModuleController::class, 'store'])->name('store');
        Route::delete('/', [ExtractModuleController::class, 'destroy'])->name('destroy');
    });
    
    Route::group(['prefix' => 'importacao-extrato', 'as' => 'extract-import.'], function () {
        Route::get('/', [ExtractImportController::class, 'index'])->name('index');
        Route::post('/extrair', [ExtractImportController::class, 'extract'])->name('extract');
        Route::post('/', [ExtractImportController::class, 'ready'])->name('ready');
        Route::delete('/', [ExtractImportController::class, 'destroy'])->name('destroy');
        Route::post('/importar', [ExtractImportController::class, 'import'])->name('import');
    });
    
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
                Route::get('/debito', [CardController::class, 'listDebit'])->name('listDebit');
                Route::get('/credito', [CardController::class, 'listCredit'])->name('listCredit');
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
    
    Route::group(['prefix' => 'categoria', 'as' => 'category.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('list');
        Route::get('/novo', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/', [CategoryController::class, 'update'])->name('update');
        Route::delete('/', [CategoryController::class, 'destroy'])->name('destroy');
    });
});
