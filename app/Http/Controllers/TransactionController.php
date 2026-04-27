<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function pos()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('transactions.pos', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:tunai,non_tunai',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $changeAmount = $validated['paid_amount'] - $totalAmount;

            if ($validated['payment_method'] === 'tunai' && $changeAmount < 0) {
                throw new \Exception('Jumlah bayar kurang dari total!');
            }

            $transaction = Transaction::create([
                'transaction_code' => 'TRX-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => max(0, $changeAmount),
            ]);

            foreach ($items as $item) {
                $item['transaction_id'] = $transaction->id;
                TransactionItem::create($item);

                // Kurangi stok
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction' => $transaction->load('items.product'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function index()
    {
        $transactions = Transaction::with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'transaction' => $transaction->load(['user', 'items.product']),
        ]);
    }
}

