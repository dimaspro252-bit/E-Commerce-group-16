@extends('layouts.app')

@section('title', 'Order Detail - DrizStuff')

@push('styles')
<style>
.detail-container {
    padding: var(--spacing-2xl) 0;
}

.breadcrumb {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xl);
    font-size: 14px;
    color: var(--gray);
}

.breadcrumb a {
    color: var(--gray);
}

.breadcrumb a:hover {
    color: var(--primary);
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-xl);
}

.detail-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: var(--spacing-sm) 0;
    font-size: 14px;
}

.info-label {
    color: var(--gray);
}

.info-value {
    font-weight: 500;
    text-align: right;
}

.product-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.product-item {
    display: flex;
    gap: var(--spacing-md);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border);
}

.product-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: var(--light-gray);
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.product-details {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: var(--spacing-sm);
}

.product-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary);
}

.review-btn {
    margin-top: var(--spacing-sm);
}

/* Summary Sidebar */
.summary-sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.status-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    text-align: center;
}

.status-icon {
    font-size: 64px;
    margin-bottom: var(--spacing-md);
}

.status-title {
    font-size: 24px;
    margin-bottom: var(--spacing-sm);
}

.status-message {
    color: var(--gray);
    font-size: 14px;
}

.payment-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
}

.payment-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    font-size: 14px;
}

.payment-total {
    font-size: 18px;
    font-weight: 700;
    padding-top: var(--spacing-md);
    border-top: 2px solid var(--border);
}

.total-amount {
    font-size: 28px;
    color: var(--primary);
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-sidebar {
        order: -1;
    }
    
    .product-item {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <a href="{{ route('transactions.index') }}">My Orders</a>
            <span>›</span>
            <span>{{ $transaction->code }}</span>
        </div>

        <div class="detail-grid">
            <!-- Main Content -->
            <div>
                <!-- Transaction Info -->
                <div class="detail-section">
                    <div class="section-header">
                        <h2 class="section-title">📋 Order Information</h2>
                        @if($transaction->payment_status === 'paid')
                            <span class="badge badge-success">✓ Paid</span>
                        @else
                            <span class="badge badge-warning">⏳ Unpaid</span>
                        @endif
                    </div>

                    <div class="info-row">
                        <span class="info-label">Order Number</span>
                        <span class="info-value">{{ $transaction->code }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Order Date</span>
                        <span class="info-value">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Store</span>
                        <span class="info-value">🏪 {{ $transaction->store->name }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Shipping Method</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $transaction->shipping_type)) }}</span>
                    </div>

                    @if($transaction->tracking_number)
                        <div class="info-row">
                            <span class="info-label">Tracking Number</span>
                            <span class="info-value">{{ $transaction->tracking_number }}</span>
                        </div>
                    @endif
                </div>

                <!-- Shipping Address -->
                <div class="detail-section">
                    <div class="section-header">
                        <h2 class="section-title">📍 Shipping Address</h2>
                    </div>

                    <p style="line-height: 1.6;">
                        {{ $transaction->address }}<br>
                        {{ $transaction->city }}, {{ $transaction->postal_code }}<br>
                        <strong>{{ $transaction->address_id }}</strong>
                    </p>
                </div>

                <!-- Products -->
                <div class="detail-section">
                    <div class="section-header">
                        <h2 class="section-title">🛍️ Order Items</h2>
                    </div>

                    <div class="product-list">
                        @foreach($transaction->transactionDetails as $detail)
                            <div class="product-item">
                                <img 
                                    src="{{ $detail->product->productImages->first() ? asset('storage/' . $detail->product->productImages->first()->image) : asset('images/default-product.png') }}" 
                                    alt="{{ $detail->product->name }}"
                                    class="product-image">
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $detail->product->name }}</div>
                                    <div class="product-details">
                                        Quantity: {{ $detail->qty }} × Rp {{ number_format($detail->product->price, 0, ',', '.') }}
                                    </div>
                                    <div class="product-price">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>

                                    @if($transaction->payment_status === 'paid')
                                        @if(in_array($detail->product_id, $reviewedProductIds))
                                            <span class="badge badge-success review-btn">
                                                ✓ Reviewed
                                            </span>
                                        @else
                                            <a href="{{ route('reviews.create', ['product' => $detail->product, 'transaction_id' => $transaction->id]) }}" class="btn btn-sm btn-outline review-btn">
                                                ⭐ Write Review
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="summary-sidebar">
                <!-- Status Card -->
                <div class="status-card">
                    @if($transaction->payment_status === 'paid')
                        <div class="status-icon">✅</div>
                        <h3 class="status-title">Payment Successful</h3>
                        <p class="status-message">
                            Your order has been confirmed and will be processed soon.
                        </p>
                    @else
                        <div class="status-icon">⏳</div>
                        <h3 class="status-title">Waiting for Payment</h3>
                        <p class="status-message">
                            Please complete your payment to process this order.
                        </p>
                        
                        <form method="POST" action="{{ route('transactions.pay', $transaction) }}" style="margin-top: var(--spacing-lg);">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                                💳 Pay Now
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="payment-card">
                    <h3 class="section-title mb-lg">💰 Payment Summary</h3>

                    <div class="payment-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->grand_total - $transaction->tax - $transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    <div class="payment-row">
                        <span>Tax (10%)</span>
                        <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>

                    <div class="payment-row">
                        <span>Shipping</span>
                        <span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    <div class="payment-row payment-total">
                        <span>Total</span>
                        <span class="total-amount">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <a href="{{ route('transactions.index') }}" class="btn btn-outline" style="width: 100%;">
                    ← Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection