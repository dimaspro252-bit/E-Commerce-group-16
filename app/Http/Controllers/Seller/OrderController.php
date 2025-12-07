<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display list of orders for seller's store
     */
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = Transaction::where('store_id', $store->id)
            ->with(['buyer.user', 'transactionDetails.product']);

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Search by transaction code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(15);

        // Statistics
        $totalOrders = Transaction::where('store_id', $store->id)->count();
        $paidOrders = Transaction::where('store_id', $store->id)
            ->where('payment_status', 'paid')
            ->count();
        $unpaidOrders = Transaction::where('store_id', $store->id)
            ->where('payment_status', 'unpaid')
            ->count();

        return view('seller.orders.index', compact(
            'orders',
            'totalOrders',
            'paidOrders',
            'unpaidOrders'
        ));
    }

    /**
     * Display order detail
     */
    public function show(Transaction $transaction)
    {
        // Check ownership
        if ($transaction->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized action');
        }

        $transaction->load([
            'buyer.user',
            'transactionDetails.product.productImages',
            'store'
        ]);

        return view('seller.orders.show', compact('transaction'));
    }

    /**
     * Update order (tracking number, etc)
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Check ownership
        if ($transaction->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $transaction->update([
            'tracking_number' => $validated['tracking_number'],
        ]);

        return back()->with('success', 'Tracking number updated successfully!');
    }
}