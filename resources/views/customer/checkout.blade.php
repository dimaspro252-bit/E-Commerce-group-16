@extends('layouts.app')

@section('title', 'Checkout - DrizStuff')

@push('styles')
<style>
.checkout-container {
    padding: var(--spacing-2xl) 0;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-xl);
}

.checkout-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.section-title {
    font-size: 20px;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.shipping-options {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.shipping-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all 0.2s;
}

.shipping-option:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.shipping-option input[type="radio"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.shipping-option.selected {
    border-color: var(--primary);
    background: var(--primary-light);
}

.shipping-info {
    flex: 1;
}

.shipping-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.shipping-desc {
    font-size: 12px;
    color: var(--gray);
}

.shipping-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary);
}

/* Order Summary */
.order-summary {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    position: sticky;
    top: 100px;
}

.summary-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: var(--spacing-lg);
}

.summary-item {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--border);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: var(--light-gray);
}

.summary-item-info {
    flex: 1;
}

.summary-item-name {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}

.summary-item-qty {
    font-size: 12px;
    color: var(--gray);
}

.summary-item-price {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
    text-align: right;
}

.summary-divider {
    border-top: 2px solid var(--border);
    margin: var(--spacing-lg) 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    font-size: 14px;
}

.summary-row.total {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
}

@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .order-summary {
        position: static;
        order: -1;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="container">
        <h1 class="mb-xl">🚀 Checkout</h1>

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <div class="checkout-grid">
                <!-- Main Form -->
                <div>
                    <!-- Shipping Address -->
                    <div class="checkout-section">
                        <h2 class="section-title">
                            📍 Shipping Address
                        </h2>

                        <div class="form-group">
                            <label for="address" class="form-label">Full Address *</label>
                            <textarea 
                                id="address" 
                                name="address" 
                                required
                                rows="3"
                                class="form-control @error('address') error @enderror"
                                placeholder="Enter your complete shipping address">{{ old('address', $buyer->address ?? '') }}</textarea>
                            @error('address')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city" class="form-label">City *</label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    required
                                    value="{{ old('city') }}"
                                    class="form-control @error('city') error @enderror"
                                    placeholder="e.g. Jakarta">
                                @error('city')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="form-label">Postal Code *</label>
                                <input 
                                    type="text" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    required
                                    value="{{ old('postal_code') }}"
                                    class="form-control @error('postal_code') error @enderror"
                                    placeholder="e.g. 12345">
                                @error('postal_code')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address_id" class="form-label">Address ID / Notes</label>
                            <input 
                                type="text" 
                                id="address_id" 
                                name="address_id" 
                                required
                                value="{{ old('address_id') }}"
                                class="form-control"
                                placeholder="e.g. Home, Office">
                        </div>

                        <div class="form-group">
                            <label for="phone_number" class="form-label">Phone Number *</label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                name="phone_number" 
                                required
                                value="{{ old('phone_number', $buyer->phone_number ?? '') }}"
                                class="form-control @error('phone_number') error @enderror"
                                placeholder="e.g. 081234567890">
                            @error('phone_number')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="checkout-section">
                        <h2 class="section-title">
                            🚚 Shipping Method
                        </h2>

                        <div class="shipping-options">
                            @foreach($shippingOptions as $option)
                                <label class="shipping-option" onclick="selectShipping('{{ $option['type'] }}', {{ $option['cost'] }})">
                                    <input 
                                        type="radio" 
                                        name="shipping_type" 
                                        value="{{ $option['type'] }}"
                                        {{ $loop->first ? 'checked' : '' }}
                                        onchange="calculateTotal()">
                                    
                                    <div class="shipping-info">
                                        <div class="shipping-name">{{ $option['name'] }}</div>
                                        <div class="shipping-desc">
                                            @if($option['type'] === 'regular')
                                                Estimated 5-7 days
                                            @elseif($option['type'] === 'express')
                                                Estimated 2-3 days
                                            @else
                                                Delivered today
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="shipping-price">
                                        Rp {{ number_format($option['cost'], 0, ',', '.') }}
                                    </div>
                                </label>
                                <input type="hidden" id="shipping_cost_{{ $option['type'] }}" value="{{ $option['cost'] }}">
                            @endforeach
                        </div>

                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ $shippingOptions[0]['cost'] }}">
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-section">
                        <h2 class="section-title">
                            💳 Payment Method
                        </h2>

                        <div class="alert alert-info">
                            ℹ️ Payment will be simulated after order is placed. 
                            You can pay from the transaction detail page.
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h2 class="section-title">📋 Order Summary</h2>

                    <!-- Items -->
                    <div class="summary-items">
                        @foreach($items as $item)
                            <div class="summary-item">
                                <img 
                                    src="{{ $item['product']->productImages->first() ? asset('storage/' . $item['product']->productImages->first()->image) : asset('images/default-product.png') }}" 
                                    alt="{{ $item['product']->name }}"
                                    class="summary-item-image">
                                
                                <div class="summary-item-info">
                                    <div class="summary-item-name">{{ $item['product']->name }}</div>
                                    <div class="summary-item-qty">Qty: {{ $item['quantity'] }}</div>
                                </div>

                                <div class="summary-item-price">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-divider"></div>

                    <!-- Calculation -->
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Tax (10%)</span>
                        <span id="tax-display">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span id="shipping-display">Rp {{ number_format($shippingOptions[0]['cost'], 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="total-display">Rp {{ number_format($subtotal + $tax + $shippingOptions[0]['cost'], 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: var(--spacing-lg);">
                        🛒 Place Order
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline" style="width: 100%; margin-top: var(--spacing-md);">
                        ← Back to Cart
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const subtotal = {{ $subtotal }};
const tax = {{ $tax }};

function selectShipping(type, cost) {
    // Update hidden input
    document.getElementById('shipping_cost').value = cost;
    
    // Update display
    calculateTotal();
}

function calculateTotal() {
    const selectedShipping = document.querySelector('input[name="shipping_type"]:checked');
    const shippingType = selectedShipping.value;
    const shippingCost = parseFloat(document.getElementById('shipping_cost_' + shippingType).value);
    
    document.getElementById('shipping_cost').value = shippingCost;
    
    const total = subtotal + tax + shippingCost;
    
    document.getElementById('shipping-display').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection