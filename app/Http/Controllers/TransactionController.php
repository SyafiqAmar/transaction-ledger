<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\XenditService;
use Illuminate\Http\Request;
use App\Services\CryptoService;
use App\Services\FrankfurterService;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request, XenditService $xendit, CryptoService $crypto, FrankfurterService $frankfurter)
{
    $validated = $request->validate([
        'sender' => 'required|string|max:255',
        'receiver' => 'required|string|max:255',
        'ticker' => 'required|in:BTC/USDT,SOL/USDT,TRX/USDT',
        'crypto_amount' => 'required|numeric|min:0.00000001',
        'hash' => 'required|string|unique:transactions,hash',
    ]);

    $symbol = str_replace('/', '-', $validated['ticker']);
    $priceUsd = $crypto->getPrice('binance', $symbol);
    $usdAmount = $validated['crypto_amount'] * $priceUsd;
    $idrAmount = $frankfurter->convertUsdToIdr($usdAmount);

    $validated['amount'] = $idrAmount;
    $validated['user_id'] = auth()->id();
    $validated['status'] = 'pending';

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
