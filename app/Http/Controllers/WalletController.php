<?php

namespace App\Http\Controllers;

use App\Services\CryptoService;

class WalletController extends Controller
{
    public function index(CryptoService $crypto)
    {
        $credentials = auth()->user()->exchangeCredentials;

        $balances = $credentials->map(function ($cred) use ($crypto) {
            try {
                return [
                    'exchange' => $cred->exchange,
                    'balance' => $crypto->getBalance($cred->exchange, $cred->api_key, $cred->api_secret),
                    'error' => null,
                ];
            } catch (\Throwable $e) {
                return [
                    'exchange' => $cred->exchange,
                    'balance' => null,
                    'error' => 'Gagal mengambil saldo',
                ];
            }
        });

        return view('wallet.index', compact('balances'));
    }
}
