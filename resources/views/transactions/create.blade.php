@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
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
            <label class="block text-sm font-medium mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Hash</label>
            <input type="text" name="hash" value="{{ old('hash') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="pending"   @selected(old('status') === 'pending')>pending</option>
                <option value="confirmed" @selected(old('status') === 'confirmed')>confirmed</option>
                <option value="failed"    @selected(old('status') === 'failed')>failed</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="{{ route('transactions.index') }}"
               class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
@endsection
