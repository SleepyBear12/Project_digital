@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500">Ringkasan aktivitas toko hari ini</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-xl">📊</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-xl">💰</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-xl">📈</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center text-xl">📦</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">{{ $stats['low_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center text-xl">⚠️</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['month_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-xl">📋</div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions with Detail -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Transaksi Terbaru</h2>
            <p class="text-sm text-gray-500">Klik tombol detail untuk melihat rincian transaksi</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $trx->transaction_code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $trx->payment_method === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $trx->payment_method === 'tunai' ? 'Tunai' : 'Non-Tunai' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <button onclick="showDetail({{ $trx->id }})" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Detail →
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Detail Transaksi</h3>
            <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <div id="detailContent" class="p-6">
            <!-- Content loaded via JS -->
        </div>
    </div>
</div>

<script>
async function showDetail(id) {
    try {
        const response = await fetch(`/transactions/${id}`);
        const data = await response.json();
        const t = data.transaction;

        let itemsHtml = '';
        t.items.forEach(item => {
            itemsHtml += `
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <div>
                        <p class="font-medium text-gray-900">${item.product.name}</p>
                        <p class="text-sm text-gray-500">${item.quantity} × Rp ${Number(item.price).toLocaleString('id-ID')}</p>
                    </div>
                    <p class="font-medium text-gray-900">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</p>
                </div>
            `;
        });

        document.getElementById('detailContent').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Transaksi</p>
                        <p class="font-medium">${t.transaction_code}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kasir</p>
                        <p class="font-medium">${t.user.name}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu</p>
                        <p class="font-medium">${new Date(t.created_at).toLocaleString('id-ID')}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                        <span class="px-2 py-1 text-xs rounded-full ${t.payment_method === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'}">
                            ${t.payment_method === 'tunai' ? 'Tunai' : 'Non-Tunai'}
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Item Pembelian</h4>
                    ${itemsHtml}
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total</span>
                        <span class="font-bold text-lg">Rp ${Number(t.total_amount).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bayar</span>
                        <span>Rp ${Number(t.paid_amount).toLocaleString('id-ID')}</span>
                    </div>
                    ${t.payment_method === 'tunai' ? `
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kembali</span>
                        <span class="text-emerald-600">Rp ${Number(t.change_amount).toLocaleString('id-ID')}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('detailModal').classList.remove('hidden');
        document.getElementById('detailModal').classList.add('flex');
    } catch (err) {
        alert('Gagal memuat detail transaksi');
    }
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>
@endsection

