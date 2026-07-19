<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeCredentialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])
        ->name('transactions.pay');
    Route::resource('transactions', TransactionController::class);
    Route::get('/exchange-credentials', [ExchangeCredentialController::class, 'index'])
        ->name('exchange-credentials.index');
    Route::post('/exchange-credentials', [ExchangeCredentialController::class, 'store'])
        ->name('exchange-credentials.store');
    Route::get('/wallet', [WalletController::class, 'index'])
        ->name('wallet.index');
});

require __DIR__.'/auth.php';
