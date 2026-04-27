<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        $transactions = Transaction::with(['user', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'total_tunai' => $transactions->where('payment_method', 'tunai')->sum('total_amount'),
            'total_non_tunai' => $transactions->where('payment_method', 'non_tunai')->sum('total_amount'),
            'average_transaction' => $transactions->avg('total_amount'),
        ];

        $dailyData = $transactions->groupBy(function ($t) {
            return $t->created_at->format('d');
        })->map(function ($items) {
            return [
                'count' => $items->count(),
                'revenue' => $items->sum('total_amount'),
            ];
        });

        return view('reports.monthly', compact('transactions', 'summary', 'dailyData', 'month'));
    }
}

