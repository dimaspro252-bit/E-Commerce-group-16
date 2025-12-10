@extends('layouts.app')

@section('title', 'Store Detail - DrizStuff')

@push('styles')
<style>
.admin-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
    padding: var(--spacing-2xl) 0;
}

.admin-sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.admin-header {
    text-align: center;
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border);
    margin-bottom: var(--spacing-lg);
}

.admin-badge {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--danger) 0%, #DC2626 100%);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto var(--spacing-md);
}

.admin-title {
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
}

.admin-nav {
    list-style: none;
}

.admin-nav-item {
    margin-bottom: var(--spacing-xs);
}

.admin-nav-link {
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

.admin-nav-link:hover,
.admin-nav-link.active {
    background: var(--primary-light);
    color: var(--primary);
}

.store-detail-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.breadcrumb {
    display: flex;
    gap: var(--spacing-sm);
    font-size: 14px;
    color: var(--gray);
}

.breadcrumb a {
    color: var(--gray);
}

.breadcrumb a:hover {
    color: var(--primary);
}

.detail-card {
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

.store-profile {
    display: flex;
    gap: var(--spacing-xl);
    align-items: start;
}

.store-logo-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--border);
}

.store-details {
    flex: 1;
}

.store-name-large {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
}

.store-meta-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.meta-box {
    padding: var(--spacing-md);
    background: var(--light-gray);
    border-radius: var(--radius-md);
}

.meta-label {
    font-size: 12px;
    color: var(--gray);
    margin-bottom: 4px;
}

.meta-value {
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
}

.stat-box {
    text-align: center;
    padding: var(--spacing-lg);
    background: var(--light-gray);
    border-radius: var(--radius-md);
}

.stat-value-large {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    font-size: 14px;
    color: var(--gray);
}

.verification-actions {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--light-gray);
    border-radius: var(--radius-lg);
}

.danger-zone {
    background: #FEF2F2;
    border: 2px solid var(--danger);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
}

.danger-zone h3 {
    color: var(--danger);
    margin-bottom: var(--spacing-md);
}

@media (max-width: 768px) {
    .admin-layout {
        grid-template-columns: 1fr;
    }
    
    .admin-sidebar {
        position: static;
    }
    
    .store-profile {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .store-meta-grid,
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="admin-layout">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-header">
                <div class="admin-badge">🔧</div>
                <div class="admin-title">Admin Panel</div>
                <span class="badge badge-danger">Administrator</span>
            </div>

            <ul class="admin-nav">
                <li class="admin-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                        📊 Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.stores.index') }}" class="admin-nav-link active">
                        🏪 Stores
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link">
                        👥 Users
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.withdrawals.index') }}" class="admin-nav-link">
                        💳 Withdrawals
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="store-detail-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>›</span>
                <a href="{{ route('admin.stores.index') }}">Stores</a>
                <span>›</span>
                <span>{{ $store->name }}</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>🏪 Store Details</h1>
                @if($store->is_verified)
                    <span class="badge badge-success" style="font-size: 16px; padding: 8px 16px;">✓ Verified</span>
                @else
                    <span class="badge badge-warning" style="font-size: 16px; padding: 8px 16px;">⏳ Pending Verification</span>
                @endif
            </div>

            <!-- Store Profile -->
            <div class="detail-card">
                <div class="store-profile">
                    <img 
                        src="{{ $store->logo ? asset('storage/' . $store->logo) : asset('images/default-store.png') }}" 
                        alt="{{ $store->name }}"
                        class="store-logo-large">
                    
                    <div class="store-details">
                        <div class="store-name-large">{{ $store->name }}</div>
                        
                        <p style="color: var(--gray); line-height: 1.6; margin-bottom: var(--spacing-lg);">
                            {{ $store->about }}
                        </p>

                        <div class="store-meta-grid">
                            <div class="meta-box">
                                <div class="meta-label">Owner</div>
                                <div class="meta-value">{{ $store->user->name }}</div>
                            </div>

                            <div class="meta-box">
                                <div class="meta-label">Email</div>
                                <div class="meta-value">{{ $store->user->email }}</div>
                            </div>

                            <div class="meta-box">
                                <div class="meta-label">Phone</div>
                                <div class="meta-value">{{ $store->phone }}</div>
                            </div>

                            <div class="meta-box">
                                <div class="meta-label">City</div>
                                <div class="meta-value">{{ $store->city }}</div>
                            </div>

                            <div class="meta-box">
                                <div class="meta-label">Postal Code</div>
                                <div class="meta-value">{{ $store->postal_code }}</div>
                            </div>

                            <div class="meta-box">
                                <div class="meta-label">Registered</div>
                                <div class="meta-value">{{ $store->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Address -->
            <div class="detail-card">
                <div class="card-header">
                    <h2 class="card-title">📍 Store Address</h2>
                </div>

                <p style="line-height: 1.8;">
                    <strong>{{ $store->address_id }}</strong><br>
                    {{ $store->address }}<br>
                    {{ $store->city }}, {{ $store->postal_code }}
                </p>
            </div>

            <!-- Statistics -->
            <div class="detail-card">
                <div class="card-header">
                    <h2 class="card-title">📊 Store Statistics</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-value-large">{{ $totalProducts }}</div>
                        <div class="stat-label">Total Products</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value-large">{{ $totalOrders }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value-large">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
            </div>

            <!-- Verification Actions -->
            @if(!$store->is_verified)
                <div class="verification-actions">
                    <form method="POST" action="{{ route('admin.stores.verify', $store) }}" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;" onclick="return confirm('Verify this store?')">
                            ✓ Verify Store
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.stores.reject', $store) }}" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%;" onclick="return confirm('Reject this store verification?')">
                            ✗ Reject
                        </button>
                    </form>
                </div>
            @else
                <div class="verification-actions">
                    <form method="POST" action="{{ route('admin.stores.reject', $store) }}" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%;" onclick="return confirm('Revoke store verification?')">
                            ✗ Revoke Verification
                        </button>
                    </form>
                </div>
            @endif

            <!-- Danger Zone -->
            <div class="danger-zone">
                <h3>🗑️ Delete Store</h3>
                <p style="color: var(--gray); margin-bottom: var(--spacing-lg);">
                    Permanently delete this store and all its data. This action cannot be undone.
                </p>
                
                <form method="POST" action="{{ route('admin.stores.destroy', $store) }}" onsubmit="return confirm('Are you absolutely sure? This will delete all store data including products and cannot be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary">
                        🗑️ Delete Store
                    </button>
                </form>
            </div>

            <!-- Back Button -->
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline btn-lg">
                ← Back to Stores
            </a>
        </main>
    </div>
</div>
@endsection