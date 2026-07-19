<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Transaksi</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.update', $transaction) }}" method="POST"
          class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Sender</label>
            <input type="text" name="sender" value="{{ old('sender', $transaction->sender) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Receiver</label>
            <input type="text" name="receiver" value="{{ old('receiver', $transaction->receiver) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Hash</label>
            <input type="text" name="hash" value="{{ old('hash', $transaction->hash) }}"
                   class="w-full border rounded px-3 py-2">
        </div>


        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="{{ route('transactions.index') }}"
               class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
    </div>
</x-app-layout>
