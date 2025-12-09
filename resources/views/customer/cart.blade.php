@extends('layouts.app')

@section('title', 'Shopping Cart - DrizStuff')

@push('styles')
<style>
.cart-container {
    padding: var(--spacing-2xl) 0;
    min-height: 60vh;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-xl);
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.cart-item {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    display: grid;
    grid-template-columns: 120px 1fr auto;
    gap: var(--spacing-lg);
    align-items: center;
}

.cart-item-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: var(--light-gray);
}

.cart-item-info h3 {
    font-size: 18px;
    margin-bottom: var(--spacing-xs);
}

.cart-item-store {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: var(--spacing-sm);
}

.cart-item-price {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: var(--spacing-md);
}

.cart-item-stock {
    font-size: 12px;
    color: var(--success);
    margin-bottom: var(--spacing-md);
}

.cart-item-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.qty-controls {
    display: flex;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--light-gray);
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

.qty-btn:hover {
    background: var(--border);
}

.qty-display {
    width: 50px;
    text-align: center;
    font-weight: 600;
    border-left: 1px solid var(--border);
    border-right: 1px solid var(--border);
}

.remove-btn {
    color: var(--danger);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 24px;
    padding: 8px;
    transition: transform 0.2s;
}

.remove-btn:hover {
    transform: scale(1.2);
}

/* Summary Card */
.cart-summary {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 20px;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    font-size: 14px;
}

.summary-row.total {
    font-size: 18px;
    font-weight: 700;
    padding-top: var(--spacing-md);
    border-top: 2px solid var(--border);
    margin-bottom: var(--spacing-lg);
}

.summary-total-price {
    font-size: 24px;
    color: var(--primary);
}

/* Empty Cart */
.empty-cart {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
}

.empty-cart-icon {
    font-size: 80px;
    margin-bottom: var(--spacing-lg);
}

.empty-cart h2 {
    margin-bottom: var(--spacing-sm);
}

.empty-cart p {
    color: var(--gray);
    margin-bottom: var(--spacing-xl);
}

@media (max-width: 768px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }
    
    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: var(--spacing-md);
    }
    
    .cart-item-image {
        width: 80px;
        height: 80px;
    }
    
    .cart-item-actions {
        grid-column: 1 / -1;
        justify-content: space-between;
    }
    
    .cart-summary {
        position: static;
    }
}
</style>
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <h1 class="mb-xl">🛒 Shopping Cart</h1>

        @if(empty($items))
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    🛍️ Start Shopping
                </a>
            </div>
        @else
            <div class="cart-grid">
                <!-- Cart Items -->
                <div class="cart-items">
                    @foreach($items as $item)
                        <div class="cart-item">
                            <!-- Image -->
                            <img 
                                src="{{ $item['product']->productImages->first() ? asset('storage/' . $item['product']->productImages->first()->image) : asset('images/default-product.png') }}" 
                                alt="{{ $item['product']->name }}"
                                class="cart-item-image">

                            <!-- Info -->
                            <div class="cart-item-info">
                                <h3>{{ $item['product']->name }}</h3>
                                <div class="cart-item-store">
                                    🏪 {{ $item['product']->store->name }}
                                </div>
                                <div class="cart-item-price">
                                    Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                                </div>
                                <div class="cart-item-stock">
                                    ✓ Stock: {{ $item['product']->stock }} available
                                </div>

                                <!-- Quantity Controls -->
                                <div class="cart-item-actions">
                                    <form method="POST" action="{{ route('cart.update', $item['product']) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="qty-controls">
                                            <button 
                                                type="button" 
                                                class="qty-btn"
                                                onclick="updateQuantity(this, -1, {{ $item['product']->id }})">
                                                −
                                            </button>
                                            <div class="qty-display" id="qty-{{ $item['product']->id }}">
                                                {{ $item['quantity'] }}
                                            </div>
                                            <input type="hidden" name="quantity" id="qty-input-{{ $item['product']->id }}" value="{{ $item['quantity'] }}">
                                            <button 
                                                type="button" 
                                                class="qty-btn"
                                                onclick="updateQuantity(this, 1, {{ $item['product']->id }}, {{ $item['product']->stock }})">
                                                +
                                            </button>
                                        </div>
                                    </form>

                                    <span style="color: var(--gray); font-size: 14px;">
                                        Subtotal: <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" title="Remove from cart">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    @endforeach

                    <!-- Clear Cart -->
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline" style="width: 100%;">
                            🗑️ Clear All Cart
                        </button>
                    </form>
                </div>

                <!-- Summary -->
                <div class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>

                    <div class="summary-row">
                        <span>Subtotal ({{ count($items) }} items)</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span style="color: var(--gray);">Calculated at checkout</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="summary-total-price">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width: 100%;">
                        🚀 Proceed to Checkout
                    </a>

                    <a href="{{ route('home') }}" class="btn btn-outline" style="width: 100%; margin-top: var(--spacing-md);">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(btn, change, productId, maxStock = 999) {
    const qtyDisplay = document.getElementById(`qty-${productId}`);
    const qtyInput = document.getElementById(`qty-input-${productId}`);
    let currentQty = parseInt(qtyDisplay.textContent);
    let newQty = currentQty + change;

    // Validate
    if (newQty < 1) newQty = 1;
    if (newQty > maxStock) {
        alert(`Maximum stock available: ${maxStock}`);
        return;
    }

    // Update display
    qtyDisplay.textContent = newQty;
    qtyInput.value = newQty;

    // Submit form
    btn.closest('form').submit();
}
</script>
@endsection