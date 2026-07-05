@extends('layouts.app')

@section('title', 'Selamat Datang — Transaction Ledger')

@section('content')
    <div class="bg-white rounded-lg shadow p-10 text-center">
        <h1 class="text-3xl font-bold mb-3">Transaction Ledger</h1>

        <p class="text-gray-600 max-w-xl mx-auto mb-2">
            Demo <strong>Transaksi pada ledger</strong> 
        </p>
        <p class="text-gray-500 text-sm max-w-xl mx-auto mb-8">
            Ini dibuat sebagai contoh bagaimana sebuah traksaksi yang masih <strong>Centralized</strong> dengan menyimpan data di satu dabatase.
             Di blockhain, seluruh traksansi disimpan dan divalidasi secara <strong>Decentralize</strong> dibanyak <em>node</em>
             yang tersebar tanpa adanya satu server pusat.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('transactions.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-medium">
                Lihat Transaksi →
            </a>
            <a href="{{ route('transactions.create') }}"
               class="bg-gray-200 hover:bg-gray-300 px-6 py-3 rounded font-medium">
                + Tambah Transaksi
            </a>
        </div>
</div>
@endsection
