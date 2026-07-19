<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeCredential;

class ExchangeCredentialController extends Controller
{
    public function index()
{
    $credentials = auth()->user()->exchangeCredentials; // butuh relasi di model User, lihat poin 4
    return view('exchange-credentials.index', compact('credentials'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'exchange' => 'required|in:binance,bybit,indodax',
        'api_key' => 'required|string',
        'api_secret' => 'required|string',
    ]);

    ExchangeCredential::updateOrCreate(
        ['user_id' => auth()->id(), 'exchange' => $validated['exchange']],
        ['api_key' => $validated['api_key'], 'api_secret' => $validated['api_secret']]
    );

    return redirect()->back()->with('success', 'API key berhasil disimpan.');
}

}
