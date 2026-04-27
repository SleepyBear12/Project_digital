@extends('layouts.app')

@section('title', 'Kelola Barang')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Barang</h1>
            <p class="text-gray-500">Daftar semua barang di toko</p>
        </div>
        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
            + Tambah Barang
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Barcode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $product->barcode ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->stock <= 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->unit }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('products.edit', $product) }}" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada barang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

