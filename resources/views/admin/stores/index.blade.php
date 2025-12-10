@extends('layouts.app')

@section('title', 'Store Management - DrizStuff')

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

.stores-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-md);
}

.stats-mini {
    display: flex;
    gap: var(--spacing-md);
    background: var(--white);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
}

.stat-mini {
    flex: 1;
    text-align: center;
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    background: var(--light-gray);
}

.stat-mini-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary);
}

.stat-mini-label {
    font-size: 12px;
    color: var(--gray);
}

.filters-bar {
    display: flex;
    gap: var(--spacing-sm);
    background: var(--white);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    flex-wrap: wrap;
}

.filter-tab {
    padding: 8px 16px;
    border: 1px solid var(--border);
    background: var(--white);
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
    text-decoration: none;
    color: var(--dark);
}

.filter-tab:hover,
.filter-tab.active {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}

.search-box {
    flex: 1;
    min-width: 200px;
}

.search-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-size: 14px;
}

.stores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-lg);
}

.store-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
}

.store-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.store-card-header {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.store-logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border);
}

.store-info {
    flex: 1;
}

.store-name {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 4px;
}

.store-owner {
    font-size: 14px;
    color: var(--gray);
}

.store-meta {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    font-size: 14px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--gray);
}

.store-actions {
    display: flex;
    gap: var(--spacing-sm);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border);
}

.empty-state {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: var(--spacing-md);
}

@media (max-width: 768px) {
    .admin-layout {
        grid-template-columns: 1fr;
    }
    
    .admin-sidebar {
        position: static;
    }
    
    .stats-mini {
        flex-direction: column;
    }
    
    .stores-grid {
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
        <main class="stores-content">
            <div class="page-header">
                <h1>🏪 Store Management</h1>
            </div>

            <!-- Statistics -->
            <div class="stats-mini">
                <div class="stat-mini">
                    <div class="stat-mini-value">{{ $totalStores }}</div>
                    <div class="stat-mini-label">Total Stores</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-value">{{ $verifiedStores }}</div>
                    <div class="stat-mini-label">Verified</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-value">{{ $pendingStores }}</div>
                    <div class="stat-mini-label">Pending</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <a href="{{ route('admin.stores.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
                    All Stores
                </a>
                <a href="{{ route('admin.stores.index', ['status' => 'verified']) }}" class="filter-tab {{ request('status') == 'verified' ? 'active' : '' }}">
                    Verified
                </a>
                <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
                    Pending
                </a>

                <form method="GET" action="{{ route('admin.stores.index') }}" class="search-box">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search stores..." 
                        value="{{ request('search') }}"
                        class="search-input">
                </form>
            </div>

            <!-- Stores Grid -->
            @if($stores->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">🏪</div>
                    <h3>No stores found</h3>
                    <p style="color: var(--gray);">Try adjusting your filters</p>
                </div>
            @else
                <div class="stores-grid">
                    @foreach($stores as $store)
                        <div class="store-card">
                            <div class="store-card-header">
                                <img 
                                    src="{{ $store->logo ? asset('storage/' . $store->logo) : asset('images/default-store.png') }}" 
                                    alt="{{ $store->name }}"
                                    class="store-logo">
                                
                                <div class="store-info">
                                    <div class="store-name">{{ $store->name }}</div>
                                    <div class="store-owner">
                                        👤 {{ $store->user->name }}
                                    </div>
                                    @if($store->is_verified)
                                        <span class="badge badge-success" style="margin-top: 4px;">✓ Verified</span>
                                    @else
                                        <span class="badge badge-warning" style="margin-top: 4px;">⏳ Pending</span>
                                    @endif
                                </div>
                            </div>

                            <div class="store-meta">
                                <div class="meta-item">
                                    <span>📍</span>
                                    <span>{{ $store->city }}</span>
                                </div>
                                <div class="meta-item">
                                    <span>📦</span>
                                    <span>{{ $store->products->count() }} products</span>
                                </div>
                            </div>

                            <div style="font-size: 14px; color: var(--gray); margin-bottom: var(--spacing-md);">
                                Registered {{ $store->created_at->diffForHumans() }}
                            </div>

                            <div class="store-actions">
                                <a href="{{ route('admin.stores.show', $store) }}" class="btn btn-primary" style="flex: 1;">
                                    👁️ View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $stores->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection