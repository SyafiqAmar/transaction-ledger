@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Detail Transaksi #{{ $transaction->id }}</h1>
        <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">← Kembali</a>
    </div>

    <div class="bg-white rounded shadow p-6 space-y-3">
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-500">Sender</span>
            <span class="font-medium">{{ $transaction->sender }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-500">Receiver</span>
            <span class="font-medium">{{ $transaction->receiver }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-500">Amount</span>
            <span class="font-medium">{{ number_format($transaction->amount, 2) }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-500">Status</span>
            <span class="font-medium">{{ $transaction->status }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="text-gray-500">Hash</span>
            <span class="font-mono text-xs break-all">{{ $transaction->hash }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Dibuat</span>
            <span class="text-sm">{{ $transaction->created_at }}</span>
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <form action="{{ route('transactions.pay', $transaction) }}" method="POST">
            @csrf
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                💳 Bayar via Xendit
            </button>
</form>
        <a href="{{ route('transactions.edit', $transaction) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Edit</a>
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
              onsubmit="return confirm('Yakin mau hapus transaksi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Hapus
            </button>
        </form>
    </div>
@endsection
