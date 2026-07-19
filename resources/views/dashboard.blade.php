<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-4">
            <a href="{{ route('transactions.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                + Tambah Transaksi
            </a>

            <a href="{{ route('wallet.index') }}"
               class="bg-white border hover:bg-gray-50 px-4 py-2 rounded flex items-center gap-2">
                💰 Wallet
            </a>
        </div>
    </div>
</x-app-layout>
