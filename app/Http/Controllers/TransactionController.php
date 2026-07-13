<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\XenditService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request, XenditService $xendit)
    {
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'amount' => 'required|numeric|min:10000',
            'hash' => 'required|string|unique:transactions,hash',
            'status' => 'required|in:pending,confirmed,failed',
        ]);

        $transaction = Transaction::create($validated);

        $invoice = $xendit->createInvoice($transaction);

        return redirect()->away($invoice['invoice_url']);
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function pay(Transaction $transaction, XenditService $xendit)
    {
        $invoice = $xendit->createInvoice($transaction);

        return redirect()->away($invoice['invoice_url']);
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'sender' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
            'amount' => 'required|numeric|min:10000',
            'hash' => 'required|string|unique:transactions,hash,' . $transaction->id,
            'status' => 'required|in:pending,confirmed,failed',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi berhasil diupdate!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaksi berhasil dihapus!');
    }
}
