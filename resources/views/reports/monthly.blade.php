@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Bulanan</h1>
            <p class="text-gray-500">Ringkasan transaksi per bulan</p>
        </div>
        <form action="{{ route('reports.monthly') }}" method="GET" class="flex gap-2">
            <input type="month" name="month" value="{{ $month }}"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
            <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $summary['total_transactions'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pembayaran Tunai</p>
            <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pembayaran Non-Tunai</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($summary['total_non_tunai'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Rincian Harian</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah Transaksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dailyData as $day => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $day }} {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ $data['count'] }} transaksi</td>
                        <td class="px-4 py-3 text-sm font-semibold text-emerald-600">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada data untuk bulan ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Transactions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Semua Transaksi - {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-mono">{{ $trx->transaction_code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-emerald-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $trx->payment_method === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $trx->payment_method === 'tunai' ? 'Tunai' : 'Non-Tunai' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada transaksi untuk bulan ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

