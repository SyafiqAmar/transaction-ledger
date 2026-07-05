<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // GET /api/transactions — daftar semua transaksi
    public function index()
    {
        $transactions = Transaction::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    // POST /api/transactions — bikin transaksi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'hash' => 'required|string|unique:transactions,hash',
            'status' => 'required|in:pending,confirmed,failed',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction,
        ], 201);
    }

    // GET /api/transactions/{transaction} — detail 1 transaksi
    public function show(Transaction $transaction)
    {
        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    // PUT/PATCH /api/transactions/{transaction} — update transaksi
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'hash' => 'required|string|unique:transactions,hash,' . $transaction->id,
            'status' => 'required|in:pending,confirmed,failed',
        ]);

        $transaction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaction,
        ]);
    }

    // DELETE /api/transactions/{transaction} — hapus transaksi
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus',
        ]);
    }
}
