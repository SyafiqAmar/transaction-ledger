<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CryptoService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.crypto.url');
    }

    public function getPrice(string $exchange, string $symbol): float
    {
        $response = Http::get("{$this->baseUrl}/ticker/{$exchange}/{$symbol}")
            ->throw();

        return $response->json('price');
    }

    public function getBalance(string $exchange, string $apiKey, string $apiSecret): array
    {
        $response = Http::post("{$this->baseUrl}/balance/{$exchange}", [
            'apiKey' => $apiKey,
            'apiSecret' => $apiSecret,
        ])->throw();

        return $response->json();
    }
}
