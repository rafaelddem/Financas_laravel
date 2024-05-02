<?php

use App\Models\Owner;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\MovementTypeController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentMethodController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {return view('index');});

Route::prefix('carteira')->group(function () {
    Route::get('/', [WalletController::class, 'index'])->name('listWallet');
    Route::post('/', [WalletController::class, 'store'])->name('createWallet');
    Route::put('/', [WalletController::class, 'update'])->name('updateWallet');
    Route::delete('/', [WalletController::class, 'destroy'])->name('deleteWallet');
});

Route::prefix('pessoa')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('listOwner');
    Route::post('/', [OwnerController::class, 'store'])->name('createOwner');
    Route::put('/', [OwnerController::class, 'update'])->name('updateOwner');
});



Route::prefix('forma')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('listPaymentMethod');
    Route::get('/{id}', [PaymentMethodController::class, 'index'])->name('findPaymentMethod');
    Route::post('/novo', [PaymentMethodController::class, 'store'])->name('createPaymentMethod');
    Route::post('/{id}/atualizar', [PaymentMethodController::class, 'update'])->name('updatePaymentMethod');
    Route::post('/{id}/excluir', [PaymentMethodController::class, 'destroy'])->name('deletePaymentMethod');
});

Route::prefix('movimento')->group(function () {
    Route::get('/', [MovementController::class, 'index'])->name('listMovements');
    Route::get('/cadastro', [MovementController::class, 'create'])->name('createMovements');
    // Route::get('/{id}', [MovimentoController::class, 'index'])->name('findMovement');
    Route::post('/novo', [MovementController::class, 'store'])->name('storeMovement');
    // Route::post('/{id}/atualizar', [MovimentoController::class, 'update'])->name('updateMovement');
    // Route::post('/{id}/excluir', [MovimentoController::class, 'destroy'])->name('deleteMovement');
});

Route::prefix('pagamento')->group(function () {
    Route::get('/', [InstallmentController::class, 'index'])->name('listInstallment');
    Route::get('/{movement}-{installment_number}', [InstallmentController::class, 'index'])->name('findInstallment');
    Route::get('/12', [InstallmentController::class, 'index'])->name('deleteInstallment');
});

Route::prefix('tipo')->group(function () {
    Route::get('/', [MovementTypeController::class, 'index'])->name('listMovementType');
    Route::get('/{id}', [MovementTypeController::class, 'index'])->name('findMovementType');
    Route::post('/novo', [MovementTypeController::class, 'store'])->name('createMovementType');
    Route::post('/{id}/atualizar', [MovementTypeController::class, 'update'])->name('updateMovementType');
    Route::post('/{id}/excluir', [MovementTypeController::class, 'destroy'])->name('deleteMovementType');
});
