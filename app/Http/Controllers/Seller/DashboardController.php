<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('seller.register');
        }

        // Get statistics
        $totalProducts = $store->products()->count();
        $totalOrders = $store->transactions()->count();
        $pendingOrders = $store->transactions()->where('payment_status', 'unpaid')->count();
        $totalRevenue = $store->transactions()->where('payment_status', 'paid')->sum('grand_total');

        // Recent orders
        $recentOrders = $store->transactions()
            ->with(['buyer.user', 'transactionDetails'])
            ->latest()
            ->take(5)
            ->get();

        // Low stock products
        $lowStockProducts = $store->products()
            ->where('stock', '<=', 5)
            ->with('productImages')
            ->get();

        return view('seller.dashboard', compact(
            'store',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
