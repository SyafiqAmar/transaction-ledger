<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])
    ->name('transactions.pay');
    
Route::resource('transactions', TransactionController::class);
