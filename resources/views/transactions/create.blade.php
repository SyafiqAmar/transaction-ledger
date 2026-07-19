<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Transaksi</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Transaksi</h1>

    {{-- Kalau validasi gagal, semua pesan error dikumpulin di sini --}}
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST"
          class="bg-white rounded shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Sender</label>
            <input type="text" name="sender" value="{{ old('sender') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Receiver</label>
            <input type="text" name="receiver" value="{{ old('receiver') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <select name="ticker" class="w-full border rounded px-3 py-2">
                <label class="block text-sm font-medium mb-1">Ticker</label>
                    <option value="BTC/USDT">BTC/USDT</option>
                    <option value="ETH/USDT">ETH/USDT</option>
                    <option value="BNB/USDT">BNB/USDT</option>
                    <option value="HYPE/USDT">HYPE/USDT</option>
                    <option value="SOL/USDT">SOL/USDT</option>
                    <option value="TRX/USDT">TRX/USDT</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jumlah Crypto</label>
            <input type="number" step="0.00000001" name="crypto_amount" value="{{ old('crypto_amount') }}"
           class="w-full border rounded px-3 py-2" placeholder="misal: 0.005">
        </div>


        <div>
            <label class="block text-sm font-medium mb-1">Hash</label>
            <input type="text" name="hash" value="{{ old('hash') }}"
                   class="w-full border rounded px-3 py-2">
        </div>


        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="{{ route('transactions.index') }}"
               class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
    </div>
</x-app-layout>
