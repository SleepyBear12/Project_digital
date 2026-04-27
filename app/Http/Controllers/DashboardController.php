<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'today_transactions' => Transaction::whereDate('created_at', $today)->count(),
            'today_revenue' => Transaction::whereDate('created_at', $today)->sum('total_amount'),
            'month_transactions' => Transaction::where('created_at', '>=', $thisMonth)->count(),
            'month_revenue' => Transaction::where('created_at', '>=', $thisMonth)->sum('total_amount'),
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock', '<=', 10)->count(),
        ];

        $recentTransactions = Transaction::with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentTransactions'));
    }
}

