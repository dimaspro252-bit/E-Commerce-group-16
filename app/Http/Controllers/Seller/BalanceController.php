<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * Display store balance and history
     */
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        // Get or create store balance
        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Get balance history with pagination
        $query = StoreBalanceHistory::where('store_balance_id', $storeBalance->id);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $histories = $query->latest()->paginate(20);

        // Statistics
        $totalIncome = StoreBalanceHistory::where('store_balance_id', $storeBalance->id)
            ->where('type', 'income')
            ->sum('amount');

        $totalWithdraw = StoreBalanceHistory::where('store_balance_id', $storeBalance->id)
            ->where('type', 'withdraw')
            ->sum('amount');

        return view('seller.balance.index', compact(
            'storeBalance',
            'histories',
            'totalIncome',
            'totalWithdraw'
        ));
    }
}