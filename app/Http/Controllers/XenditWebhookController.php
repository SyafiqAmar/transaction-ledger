<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller {
    public function handle(Request $request)
    {
        $token = $request->header('x-callback-token');
        if ($token !== config('services.xendit.callback_token')) {
            abort(401, 'Token webhook tidak valid');
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');

        $id = (int) str_replace('trx-', '', (string) $externalId);
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($status == 'PAID') {
            $transaction->update(['status' => 'confirmed']);
        } elseif ($status === 'EXPIRED') {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'ok']);
    }
}