<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exchange API Key</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4">Hubungkan Akun Exchange</h1>

    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('exchange-credentials.store') }}" method="POST"
          class="bg-white rounded shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Exchange</label>
            <select name="exchange" class="border rounded w-full p-2" required>
                <option value="binance">Binance</option>
                <option value="bybit">Bybit</option>
                <option value="indodax">Indodax</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">API Key</label>
            <input type="text" name="api_key" class="border rounded w-full p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">API Secret</label>
            <input type="password" name="api_secret" class="border rounded w-full p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>

    <h2 class="text-xl font-semibold mt-8 mb-2">Akun Terhubung</h2>
    <ul class="space-y-1">
        @forelse ($credentials as $cred)
            <li class="bg-white rounded shadow p-3">{{ ucfirst($cred->exchange) }} — terhubung</li>
        @empty
            <li class="text-gray-500">Belum ada exchange yang terhubung.</li>
        @endforelse
    </ul>
    </div>
</x-app-layout>
