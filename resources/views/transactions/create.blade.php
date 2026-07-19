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
          class="bg-white rounded shadow p-6 space-y-4" id="transaction-form">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Jenis Transaksi</label>
            <div class="flex gap-4">
                {{-- 'sell' sementara di-hide: belum ada alur Xendit untuk user menerima Rupiah --}}
                @foreach (['buy' => 'Buy Crypto', 'send' => 'Send Crypto'] as $value => $label)
                    <label class="flex items-center gap-2">
                        <input type="radio" name="type" value="{{ $value }}" class="type-option"
                               @checked(old('type', 'buy') === $value) required>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ticker</label>
            <select name="ticker" id="ticker" class="w-full border rounded px-3 py-2">
                <option value="BTC/USDT" @selected(old('ticker') === 'BTC/USDT')>BTC/USDT</option>
                <option value="ETH/USDT" @selected(old('ticker') === 'ETH/USDT')>ETH/USDT</option>
                <option value="BNB/USDT" @selected(old('ticker') === 'BNB/USDT')>BNB/USDT</option>
                <option value="SOL/USDT" @selected(old('ticker') === 'SOL/USDT')>SOL/USDT</option>
                <option value="TRX/USDT" @selected(old('ticker') === 'TRX/USDT')>TRX/USDT</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Price (IDR / unit)</label>
            <input type="text" id="price-display" class="w-full border rounded px-3 py-2 bg-gray-50" readonly
                   value="Memuat harga...">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Amount (jumlah crypto)</label>
            <input type="number" step="0.00000001" name="crypto_amount" id="crypto_amount"
                   value="{{ old('crypto_amount') }}"
                   class="w-full border rounded px-3 py-2" placeholder="misal: 0.005" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Total (IDR, estimasi)</label>
            <input type="text" id="total-display" class="w-full border rounded px-3 py-2 bg-gray-50" readonly
                   value="-">
            <p class="text-xs text-gray-500 mt-1">Total final dihitung ulang di server saat submit (harga bisa berubah sedikit).</p>
        </div>

        <div id="send-fields" class="space-y-4 hidden">
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
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Hash</label>
            <input type="text" name="hash" value="{{ old('hash') }}"
                   class="w-full border rounded px-3 py-2" required>
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

    <script>
        (function () {
            const tickerSelect = document.getElementById('ticker');
            const priceDisplay = document.getElementById('price-display');
            const totalDisplay = document.getElementById('total-display');
            const amountInput = document.getElementById('crypto_amount');
            const sendFields = document.getElementById('send-fields');
            const senderInput = sendFields.querySelector('[name="sender"]');
            const receiverInput = sendFields.querySelector('[name="receiver"]');
            const typeOptions = document.querySelectorAll('.type-option');

            let currentPriceIdr = 0;

            function formatIdr(value) {
                return 'Rp ' + Number(value).toLocaleString('id-ID', { maximumFractionDigits: 2 });
            }

            function recalculateTotal() {
                const amount = parseFloat(amountInput.value) || 0;
                totalDisplay.value = currentPriceIdr ? formatIdr(currentPriceIdr * amount) : '-';
            }

            function fetchPrice() {
                priceDisplay.value = 'Memuat harga...';
                fetch(`{{ route('transactions.price-preview') }}?ticker=${encodeURIComponent(tickerSelect.value)}`)
                    .then((res) => res.json())
                    .then((data) => {
                        currentPriceIdr = data.price_idr;
                        priceDisplay.value = formatIdr(currentPriceIdr);
                        recalculateTotal();
                    })
                    .catch(() => {
                        priceDisplay.value = 'Gagal memuat harga';
                        currentPriceIdr = 0;
                    });
            }

            function toggleSendFields() {
                const selectedType = document.querySelector('.type-option:checked')?.value;
                const isSend = selectedType === 'send';
                sendFields.classList.toggle('hidden', !isSend);
                senderInput.required = isSend;
                receiverInput.required = isSend;
            }

            tickerSelect.addEventListener('change', fetchPrice);
            amountInput.addEventListener('input', recalculateTotal);
            typeOptions.forEach((el) => el.addEventListener('change', toggleSendFields));

            fetchPrice();
            toggleSendFields();
        })();
    </script>
</x-app-layout>
