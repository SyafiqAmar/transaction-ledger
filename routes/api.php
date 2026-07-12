<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XenditWebhookController;

Route::get('/test', function () {
    return ['message' => 'oke'];
});


Route::apiResource('transactions', TransactionController::class)
    ->names('api.transactions');


Route::post('/xendit/webhook', [XenditWebhookController::class, 'handle']);
