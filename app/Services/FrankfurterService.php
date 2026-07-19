<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FrankfurterService
{
    public function convertUsdToIdr(float $usdAmount): float
    {
        $response = Http::get('https://api.frankfurter.dev/v1/latest', [
            'base' => 'USD',
            'symbols' => 'IDR',
        ])->throw();

        $rate = $response->json('rates.IDR');

        return $usdAmount * $rate;
    }
}
