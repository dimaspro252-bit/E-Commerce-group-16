@extends('layouts.app')

@section('title', 'My Orders - DrizStuff')

@push('styles')
<style>
.transactions-container {
    padding: var(--spacing-2xl) 0;
    min-height: 60vh;
}

.transactions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-xl);
}

.transaction-filters {
    display: flex;
    gap: var(--spacing-sm);
}

.filter-btn {
    padding: 8px 16px;
    border: 1px solid var(--border);
    background: var(--white);
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

.transaction-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.transaction-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
}

.transaction-card:hover {
    box-shadow: var(--shadow-lg);
}

.transaction-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
    margin-bottom: var(--spacing-md);
}

.transaction-code {
    font-weight: 600;
    color: var(--primary);
}

.transaction-date {
    font-size: 14px;
    color: var(--gray);
}

.store-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
    font-size: 14px;
}

.store-name {
    font-weight: 500;
}

.transaction-items {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.transaction-item {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: var(--light-gray);
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.item-details {
    font-size: 14px;
    color: var(--gray);
}

.item-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary);
    text-align: right;
}

.transaction-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border);
}

.transaction-total {
    text-align: right;
}

.total-label {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: 4px;
}

.total-amount {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
}

.transaction-actions {
    display: flex;
    gap: var(--spacing-sm);
}

/* Empty State */
.empty-transactions {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
}

.empty-icon {
    font-size: 80px;
    margin-bottom: var(--spacing-lg);
}

.empty-transactions h2 {
    margin-bottom: var(--spacing-sm);
}

.empty-transactions p {
    color: var(--gray);
    margin-bottom: var(--spacing-xl);
}

@media (max-width: 768px) {
    .transactions-header {
        flex-direction: column;
        align-items: stretch;
        gap: var(--spacing-md);
    }
    
    .transaction-filters {
        flex-wrap: wrap;
    }
    
    .transaction-footer {
        flex-direction: column;
        gap: var(--spacing-md);
        align-items: stretch;
    }
    
    .transaction-total {
        text-align: left;
    }
    
    .transaction-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="transactions-container">
    <div class="container">
        <div class="transactions-header">
            <h1>📦 My Orders</h1>

            <!-- Filters -->
            <div class="transaction-filters">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Unpaid</button>
                <button class="filter-btn">Paid</button>
            </div>
        </div>

        @if($transactions->isEmpty())
            <!-- Empty State -->
            <div class="empty-transactions">
                <div class="empty-icon">📦</div>
                <h2>No orders yet</h2>
                <p>You haven't placed any orders. Start shopping now!</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    🛍️ Start Shopping
                </a>
            </div>
        @else
            <!-- Transaction List -->
            <div class="transaction-list">
                @foreach($transactions as $transaction)
                    <div class="transaction-card">
                        <!-- Header -->
                        <div class="transaction-header-row">
                            <div>
                                <div class="transaction-code">
                                    {{ $transaction->code }}
                                </div>
                                <div class="transaction-date">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            <div>
                                @if($transaction->payment_status === 'paid')
                                    <span class="badge badge-success">✓ Paid</span>
                                @else
                                    <span class="badge badge-warning">⏳ Unpaid</span>
                                @endif
                            </div>
                        </div>

                        <!-- Store Info -->
                        <div class="store-info">
                            <span>🏪</span>
                            <span class="store-name">{{ $transaction->store->name }}</span>
                        </div>

                        <!-- Items -->
                        <div class="transaction-items">
                            @foreach($transaction->transactionDetails->take(2) as $detail)
                                <div class="transaction-item">
                                    <img 
                                        src="{{ $detail->product->productImages->first() ? asset('storage/' . $detail->product->productImages->first()->image) : asset('images/default-product.png') }}" 
                                        alt="{{ $detail->product->name }}"
                                        class="item-image">
                                    
                                    <div class="item-info">
                                        <div class="item-name">{{ $detail->product->name }}</div>
                                        <div class="item-details">
                                            Qty: {{ $detail->qty }} × Rp {{ number_format($detail->product->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="item-price">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            @if($transaction->transactionDetails->count() > 2)
                                <div style="font-size: 14px; color: var(--gray); text-align: center;">
                                    +{{ $transaction->transactionDetails->count() - 2 }} more items
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="transaction-footer">
                            <div class="transaction-total">
                                <div class="total-label">Total Payment</div>
                                <div class="total-amount">
                                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="transaction-actions">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline">
                                    📋 View Details
                                </a>
                                
                                @if($transaction->payment_status === 'unpaid')
                                    <form method="POST" action="{{ route('transactions.pay', $transaction) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            💳 Pay Now
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination mt-xl">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Filter functionality (can be implemented with actual filtering)
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Add your filtering logic here
        const filter = this.textContent.toLowerCase();
        console.log('Filter by:', filter);
    });
});
</script>
@endsection