@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
        <p class="text-gray-500">Update informasi barang {{ $product->name }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang *</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                    <input type="text" name="barcode" value="{{ $product->barcode }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                    <input type="number" name="price" value="{{ $product->price }}" required min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                    <select name="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="pcs" {{ $product->unit == 'pcs' ? 'selected' : '' }}>pcs</option>
                        <option value="pack" {{ $product->unit == 'pack' ? 'selected' : '' }}>pack</option>
                        <option value="botol" {{ $product->unit == 'botol' ? 'selected' : '' }}>botol</option>
                        <option value="karung" {{ $product->unit == 'karung' ? 'selected' : '' }}>karung</option>
                        <option value="kaleng" {{ $product->unit == 'kaleng' ? 'selected' : '' }}>kaleng</option>
                        <option value="kotak" {{ $product->unit == 'kotak' ? 'selected' : '' }}>kotak</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('products.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                    Update Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

