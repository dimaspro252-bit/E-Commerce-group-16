<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Show store registration form
     */
    public function create()
    {
        // Check if user already has a store
        if (auth()->user()->store) {
            return redirect()->route('seller.dashboard')
                ->with('info', 'You already have a store');
        }
        return view('seller.store.create');
    }

    /**
     * Store new store registration
     */
    public function store(Request $request)
    {
        // Check if user already has a store
        if (auth()->user()->store) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You already have a store');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'about' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'address_id' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        // Upload logo
        $logoPath = $request->file('logo')->store('stores/logos', 'public');

        Store::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'logo' => $logoPath,
            'about' => $validated['about'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'address_id' => $validated['address_id'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'is_verified' => false,
        ]);
        return redirect()->route('dashboard')
            ->with('success', 'Store registered successfully! Waiting for admin verification.');
    }

    /**
     * Show store edit form
     */
    public function edit()
    {
        $store = auth()->user()->store;
        if (!$store) {
            return redirect()->route('seller.register');
        }
        return view('seller.store.edit', compact('store'));
    }

    /**
     * Update store information
     */
    public function update(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) {
            return redirect()->route('seller.register');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'about' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'address_id' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        // Upload new logo if provided
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $validated['logo'] = $request->file('logo')->store('stores/logos', 'public');
        }
        $store->update($validated);
        return back()->with('success', 'Store updated successfully!');
    }

    /**
     * Delete store
     */
    public function destroy()
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('dashboard');
        }

        // Delete logo
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        $store->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Store deleted successfully');
    }
}