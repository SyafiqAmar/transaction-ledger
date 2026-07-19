<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Transaksi</h2>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Daftar Transaksi</h1>
        <a href="{{ route('transactions.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Tambah Transaksi
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Sender</th>
                    <th class="px-4 py-3">Receiver</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $transaction->sender }}</td>
                        <td class="px-4 py-3">{{ $transaction->receiver }}</td>
                        <td class="px-4 py-3">{{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if ($transaction->status === 'confirmed') bg-green-100 text-green-700
                                @elseif ($transaction->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('transactions.show', $transaction) }}"
                               class="text-gray-600 hover:underline">Lihat</a>
                            <a href="{{ route('transactions.edit', $transaction) }}"
                               class="text-blue-600 hover:underline ml-2">Edit</a>
                            <form action="{{ route('transactions.destroy', $transaction) }}"
                                  method="POST" class="inline ml-2"
                                  onsubmit="return confirm('Yakin mau hapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            Belum ada transaksi. Klik "Tambah Transaksi" untuk mulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</x-app-layout>
