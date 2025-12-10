@extends('layouts.app')

@section('title', 'Admin Dashboard - DrizStuff')

@push('styles')
<style>
.admin-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
    padding: var(--spacing-2xl) 0;
}

/* Admin Sidebar */
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
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: var(--primary-light);
    border-radius: 50%;
    transform: translate(30%, -30%);
    opacity: 0.3;
}

.stat-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.stat-icon {
    font-size: 36px;
    margin-bottom: var(--spacing-md);
}

.stat-value {
    font-size: 36px;
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

.item-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: all 0.2s;
}

.list-item:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.item-info {
    flex: 1;
}

.item-title {
    font-weight: 600;
    margin-bottom: 4px;
}

.item-subtitle {
    font-size: 14px;
    color: var(--gray);
}

.item-actions {
    display: flex;
    gap: var(--spacing-sm);
    align-items: center;
}

.empty-state {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--gray);
}

@media (max-width: 768px) {
    .admin-layout {
        grid-template-columns: 1fr;
    }
    
    .admin-sidebar {
        position: static;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-md);
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
                <div class="admin-badge">
                    🔧
                </div>
                <div class="admin-title">Admin Panel</div>
                <span class="badge badge-danger">Administrator</span>
            </div>

            <ul class="admin-nav">
                <li class="admin-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link active">
                        📊 Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="{{ route('admin.stores.index') }}" class="admin-nav-link">
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
        <main class="dashboard-content">
            <h1>📊 Admin Dashboard</h1>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-value">{{ $totalUsers }}</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🏪</div>
                    <div class="stat-value">{{ $totalStores }}</div>
                    <div class="stat-label">Total Stores</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value" style="font-size: 24px;">Rp {{ number_format($totalRevenue / 1000000, 1) }}M</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card" style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);">
                    <div class="stat-value" style="color: #1E40AF;">{{ $verifiedStores }}</div>
                    <div class="stat-label">Verified Stores</div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);">
                    <div class="stat-value" style="color: #92400E;">{{ $pendingStores }}</div>
                    <div class="stat-label">Pending Stores</div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);">
                    <div class="stat-value" style="color: #991B1B;">{{ $pendingWithdrawals }}</div>
                    <div class="stat-label">Pending Withdrawals</div>
                </div>

                <div class="stat-card" style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);">
                    <div class="stat-value" style="color: #065F46;">{{ $totalTransactions }}</div>
                    <div class="stat-label">Total Transactions</div>
                </div>
            </div>

            <!-- Recent Stores -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">🏪 Recent Store Registrations</h2>
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-outline">View All</a>
                </div>

                @if($recentStores->isEmpty())
                    <div class="empty-state">
                        <p>No recent store registrations</p>
                    </div>
                @else
                    <div class="item-list">
                        @foreach($recentStores as $store)
                            <div class="list-item">
                                <div class="item-info">
                                    <div class="item-title">{{ $store->name }}</div>
                                    <div class="item-subtitle">
                                        Owner: {{ $store->user->name }} • {{ $store->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="item-actions">
                                    @if($store->is_verified)
                                        <span class="badge badge-success">✓ Verified</span>
                                    @else
                                        <span class="badge badge-warning">⏳ Pending</span>
                                    @endif
                                    <a href="{{ route('admin.stores.show', $store) }}" class="btn btn-sm btn-outline">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">💰 Recent Transactions</h2>
                </div>

                @if($recentTransactions->isEmpty())
                    <div class="empty-state">
                        <p>No recent transactions</p>
                    </div>
                @else
                    <div class="item-list">
                        @foreach($recentTransactions as $transaction)
                            <div class="list-item">
                                <div class="item-info">
                                    <div class="item-title">{{ $transaction->code }}</div>
                                    <div class="item-subtitle">
                                        {{ $transaction->buyer->user->name }} → {{ $transaction->store->name }} • 
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="item-actions">
                                    <span style="font-weight: 700; color: var(--primary); margin-right: var(--spacing-md);">
                                        Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                    </span>
                                    @if($transaction->payment_status === 'paid')
                                        <span class="badge badge-success">✓ Paid</span>
                                    @else
                                        <span class="badge badge-warning">⏳ Unpaid</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Withdrawals -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">💳 Recent Withdrawal Requests</h2>
                    <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-outline">View All</a>
                </div>

                @if($recentWithdrawals->isEmpty())
                    <div class="empty-state">
                        <p>No recent withdrawal requests</p>
                    </div>
                @else
                    <div class="item-list">
                        @foreach($recentWithdrawals as $withdrawal)
                            <div class="list-item">
                                <div class="item-info">
                                    <div class="item-title">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </div>
                                    <div class="item-subtitle">
                                        {{ $withdrawal->storeBalance->store->name }} • 
                                        {{ $withdrawal->bank_name }} ({{ $withdrawal->bank_account_number }})
                                    </div>
                                </div>

                                <div class="item-actions">
                                    @if($withdrawal->status === 'pending')
                                        <span class="badge badge-warning">⏳ Pending</span>
                                        <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-primary">
                                            Review
                                        </a>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="badge badge-success">✓ Approved</span>
                                    @else
                                        <span class="badge badge-danger">✗ Rejected</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection