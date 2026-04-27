<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function scan(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'product' => $product,
        ]);
    }
}

