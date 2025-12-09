@extends('layouts.app')

@section('title', 'Seller Dashboard - DrizStuff')

@push('styles')
<style>
.seller-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
    padding: var(--spacing-2xl) 0;
    min-height: 80vh;
}

/* Sidebar */
.seller-sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.store-header {
    text-align: center;
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border);
    margin-bottom: var(--spacing-lg);
}

.store-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto var(--spacing-md);
    border: 3px solid var(--border);
}

.store-name {
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
}

.store-status {
    font-size: 12px;
}

.seller-nav {
    list-style: none;
}

.seller-nav-item {
    margin-bottom: var(--spacing-xs);
}

.seller-nav-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: 10px 12px;
    border-radius: var(--radius-md);
    color: var(--dark);
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
}

.seller-nav-link:hover,
.seller-nav-link.active {
    background: var(--primary-light);
    color: var(--primary);
}

/* Dashboard Content */
.dashboard-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xl);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-lg);
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
}

.stat-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.stat-icon {
    font-size: 32px;
    margin-bottom: var(--spacing-sm);
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    font-size: 14px;
    color: var(--gray);
}

.content-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
}

.card-title {
    font-size: 18px;
    font-weight: 600;
}

/* Recent Orders */
.order-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: all 0.2s;
}

.order-item:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.order-info {
    flex: 1;
}

.order-code {
    font-weight: 600;
    margin-bottom: 4px;
}

.order-customer {
    font-size: 14px;
    color: var(--gray);
}

.order-amount {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin-right: var(--spacing-md);
}

/* Low Stock Products */
.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--spacing-md);
}

.low-stock-item {
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: all 0.2s;
}

.low-stock-item:hover {
    border-color: var(--danger);
    box-shadow: var(--shadow-md);
}

.low-stock-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    background: var(--light-gray);
}

.low-stock-info {
    padding: var(--spacing-md);
}

.low-stock-name {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.low-stock-quantity {
    font-size: 12px;
    color: var(--danger);
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--gray);
}

@media (max-width: 768px) {
    .seller-layout {
        grid-template-columns: 1fr;
    }
    
    .seller-sidebar {
        position: static;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
    }
    
    .product-list {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="seller-layout">
        <!-- Sidebar -->
        <aside class="seller-sidebar">
            <div class="store-header">
                <img 
                    src="{{ $store->logo ? asset('storage/' . $store->logo) : asset('images/default-store.png') }}" 
                    alt="{{ $store->name }}"
                    class="store-logo">
                <div class="store-name">{{ $store->name }}</div>
                <div class="store-status">
                    @if($store->is_verified)
                        <span class="badge badge-success">✓ Verified</span>
                    @else
                        <span class="badge badge-warning">⏳ Pending Verification</span>
                    @endif
                </div>
            </div>

            <ul class="seller-nav">
                <li class="seller-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="seller-nav-link active">
                        📊 Dashboard
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.products.index') }}" class="seller-nav-link">
                        📦 Products
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.categories.index') }}" class="seller-nav-link">
                        🏷️ Categories
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.orders.index') }}" class="seller-nav-link">
                        🛒 Orders
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.balance.index') }}" class="seller-nav-link">
                        💰 Balance
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.withdrawal.index') }}" class="seller-nav-link">
                        💳 Withdrawal
                    </a>
                </li>
                <li class="seller-nav-item">
                    <a href="{{ route('seller.store.edit') }}" class="seller-nav-link">
                        ⚙️ Store Settings
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h1>📊 Dashboard</h1>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🛒</div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-value">{{ $pendingOrders }}</div>
                    <div class="stat-label">Pending Orders</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">🛒 Recent Orders</h2>
                    <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-outline">View All</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="empty-state">
                        <p>No orders yet</p>
                    </div>
                @else
                    <div class="order-list">
                        @foreach($recentOrders as $order)
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="order-code">{{ $order->code }}</div>
                                    <div class="order-customer">
                                        👤 {{ $order->buyer->user->name }} • 
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="order-amount">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </div>

                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">✓ Paid</span>
                                @else
                                    <span class="badge badge-warning">⏳ Unpaid</span>
                                @endif

                                <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline">
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Low Stock Alert -->
            @if($lowStockProducts->isNotEmpty())
                <div class="content-card">
                    <div class="card-header">
                        <h2 class="card-title">⚠️ Low Stock Alert</h2>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline">Manage Products</a>
                    </div>

                    <div class="product-list">
                        @foreach($lowStockProducts as $product)
                            <div class="low-stock-item">
                                <img 
                                    src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->image) : asset('images/default-product.png') }}" 
                                    alt="{{ $product->name }}"
                                    class="low-stock-image">
                                
                                <div class="low-stock-info">
                                    <div class="low-stock-name">{{ $product->name }}</div>
                                    <div class="low-stock-quantity">
                                        ⚠️ Only {{ $product->stock }} left
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection