<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class XenditService
{
    // Alamat dasar API Xendit. Semua endpoint nempel di belakang ini.
    private const BASE_URL = 'https://api.xendit.co';

    /**
     * Bikin invoice pembayaran di Xendit untuk sebuah transaksi.
     * Balikin array data invoice dari Xendit (termasuk 'invoice_url').
     */
    public function createInvoice(Transaction $transaction): array
    {
        $response = Http::withBasicAuth(config('services.xendit.secret_key'), '')
            ->acceptJson()
            ->post(self::BASE_URL . '/v2/invoices', [
                // external_id = ID unik dari SISI KITA, biar bisa dicocokin nanti.
                'external_id' => 'trx-' . $transaction->id,
                'amount' => (float) $transaction->amount,
                'description' => "Pembayaran transaksi #{$transaction->id}: "
                    . "{$transaction->sender} → {$transaction->receiver}",
                // Setelah bayar (atau gagal), user dibalikin ke halaman detail transaksi.
                'success_redirect_url' => route('transactions.show', $transaction),
                'failure_redirect_url' => route('transactions.show', $transaction),
            ]);

        // Kalau Xendit balas error (4xx/5xx), lempar exception biar ketahuan.
        $response->throw();

        return $response->json();
    }
}
