<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Transaction Ledger')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-gray-900 text-white px-6 py-4 shadow">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="{{ route('transactions.index') }}" class="text-lg font-bold">
                Transaction Ledger
            </a>
            <span class="text-sm text-gray-400">Ledger Transaction Demo</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto p-6">
        {{-- Flash message: muncul sekali setelah redirect (mis. "berhasil disimpan") --}}
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
