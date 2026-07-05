<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Route tes: pastikan API hidup. Buka /api/ping → {"message":"pong"}
Route::get('/ping', function () {
    return ['message' => 'pong'];
});

// 5 route CRUD JSON: index, store, show, update, destroy (tanpa create/edit)
// ->names('api.transactions') = kasih nama "api.transactions.*" biar TIDAK
// tabrakan dengan route web yang namanya "transactions.*".
Route::apiResource('transactions', TransactionController::class)
    ->names('api.transactions');
