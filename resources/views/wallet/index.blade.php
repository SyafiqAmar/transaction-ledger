<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wallet</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($balances as $item)
            <div class="bg-white rounded shadow p-4">
                <h3 class="font-semibold mb-2">{{ ucfirst($item['exchange']) }}</h3>

                @if ($item['error'])
                    <p class="text-red-600 text-sm">{{ $item['error'] }}</p>
                @else
                    <ul class="text-sm space-y-1">
                        @foreach ($item['balance'] as $coin => $amount)
                            @if ($amount > 0)
                                <li class="flex justify-between">
                                    <span>{{ $coin }}</span>
                                    <span>{{ $amount }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Belum ada exchange yang terhubung. <a href="{{ route('exchange-credentials.index') }}" class="text-blue-600 underline">Hubungkan sekarang</a>.</p>
        @endforelse
    </div>
</x-app-layout>
