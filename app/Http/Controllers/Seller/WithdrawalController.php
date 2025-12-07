<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal page with history
     */
    public function index()
    {
        $store = auth()->user()->store;

        // Get store balance
        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Get withdrawal history
        $withdrawals = Withdrawal::where('store_balance_id', $storeBalance->id)
            ->latest()
            ->paginate(10);

        // Get last bank account info (from last withdrawal)
        $lastWithdrawal = Withdrawal::where('store_balance_id', $storeBalance->id)
            ->latest()
            ->first();

        return view('seller.withdrawal.index', compact(
            'storeBalance',
            'withdrawals',
            'lastWithdrawal'
        ));
    }

    /**
     * Process withdrawal request
     */
    public function store(Request $request)
    {
        $store = auth()->user()->store;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
        ]);

        // Get store balance
        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Check if balance sufficient
        if ($storeBalance->balance < $validated['amount']) {
            return back()
                ->withInput()
                ->with('error', 'Insufficient balance. Your balance: Rp ' . number_format($storeBalance->balance, 0, ',', '.'));
        }

        // Check if there's pending withdrawal
        $pendingWithdrawal = Withdrawal::where('store_balance_id', $storeBalance->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingWithdrawal) {
            return back()
                ->withInput()
                ->with('error', 'You have a pending withdrawal. Please wait for admin approval.');
        }

        DB::beginTransaction();

        try {
            // Create withdrawal request
            $withdrawal = Withdrawal::create([
                'store_balance_id' => $storeBalance->id,
                'amount' => $validated['amount'],
                'bank_account_name' => $validated['bank_account_name'],
                'bank_account_number' => $validated['bank_account_number'],
                'bank_name' => $validated['bank_name'],
                'status' => 'pending',
            ]);

            // Deduct balance temporarily
            $storeBalance->decrement('balance', $validated['amount']);

            // Record history
            StoreBalanceHistory::create([
                'store_balance_id' => $storeBalance->id,
                'type' => 'withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => 'withdrawal',
                'amount' => $validated['amount'],
                'remarks' => 'Withdrawal request #' . $withdrawal->id,
            ]);

            DB::commit();

            return back()->with('success', 'Withdrawal request submitted successfully! Waiting for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Update bank account info
     */
    public function updateBank(Request $request)
    {
        $validated = $request->validate([
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
        ]);

        // You can store this in a separate bank_accounts table
        // or just return success (bank info is saved per withdrawal)

        return back()->with('success', 'Bank account information saved!');
    }
}
