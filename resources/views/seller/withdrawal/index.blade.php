@extends('layouts.app')

@section('title', 'Withdrawal - DrizStuff')

@push('styles')
<style>
.seller-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
    padding: var(--spacing-2xl) 0;
}

.withdrawal-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.withdrawal-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-xl);
}

.balance-info-card {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    text-align: center;
}

.balance-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: var(--spacing-sm);
}

.balance-amount {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
}

.min-withdrawal-text {
    font-size: 12px;
    opacity: 0.8;
}

.form-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
}

.form-header {
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
}

.form-title {
    font-size: 18px;
    font-weight: 600;
}

.alert-box {
    background: var(--light-gray);
    border-left: 4px solid var(--primary);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
    font-size: 14px;
}

.history-card {
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

.withdrawal-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.withdrawal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}

.withdrawal-info {
    flex: 1;
}

.withdrawal-amount {
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 4px;
}

.withdrawal-bank {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: 2px;
}

.withdrawal-date {
    font-size: 12px;
    color: var(--gray);
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
    
    .withdrawal-grid {
        grid-template-columns: 1fr;
    }
    
    .withdrawal-item {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="seller-layout">
        <!-- Sidebar -->
        @include('seller.partials.sidebar', ['activeMenu' => 'withdrawal'])

        <!-- Main Content -->
        <main class="withdrawal-content">
            <h1>💳 Withdrawal</h1>

            <div class="withdrawal-grid">
                <!-- Left Column -->
                <div>
                    <!-- Balance Info -->
                    <div class="balance-info-card">
                        <div class="balance-label">Available Balance</div>
                        <div class="balance-amount">
                            Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}
                        </div>
                        <div class="min-withdrawal-text">
                            Minimum withdrawal: Rp 10,000
                        </div>
                    </div>
                </div>

                <!-- Right Column - Withdrawal Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h2 class="form-title">💰 Request Withdrawal</h2>
                    </div>

                    <div class="alert-box">
                        ℹ️ Withdrawals are processed within 1-3 business days after approval by admin.
                    </div>

                    <form method="POST" action="{{ route('seller.withdrawal.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="amount" class="form-label">Withdrawal Amount (Rp) *</label>
                            <input 
                                type="number" 
                                id="amount" 
                                name="amount" 
                                required
                                min="10000"
                                max="{{ $storeBalance->balance }}"
                                value="{{ old('amount') }}"
                                class="form-control @error('amount') error @enderror"
                                placeholder="Enter amount">
                            @error('amount')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div style="font-size: 12px; color: var(--gray); margin-top: 4px;">
                                Max: Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bank_name" class="form-label">Bank Name *</label>
                            <input 
                                type="text" 
                                id="bank_name" 
                                name="bank_name" 
                                required
                                value="{{ old('bank_name', $lastWithdrawal->bank_name ?? '') }}"
                                class="form-control @error('bank_name') error @enderror"
                                placeholder="e.g. BCA, Mandiri, BNI">
                            @error('bank_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bank_account_name" class="form-label">Account Name *</label>
                            <input 
                                type="text" 
                                id="bank_account_name" 
                                name="bank_account_name" 
                                required
                                value="{{ old('bank_account_name', $lastWithdrawal->bank_account_name ?? '') }}"
                                class="form-control @error('bank_account_name') error @enderror"
                                placeholder="Account holder name">
                            @error('bank_account_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bank_account_number" class="form-label">Account Number *</label>
                            <input 
                                type="text" 
                                id="bank_account_number" 
                                name="bank_account_number" 
                                required
                                value="{{ old('bank_account_number', $lastWithdrawal->bank_account_number ?? '') }}"
                                class="form-control @error('bank_account_number') error @enderror"
                                placeholder="1234 5678 9012 3456">
                            @error('bank_account_number')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            💳 Submit Withdrawal Request
                        </button>
                    </form>
                </div>
            </div>

            <!-- Withdrawal History -->
            <div class="history-card">
                <div class="card-header">
                    <h2 class="card-title">📊 Withdrawal History</h2>
                </div>

                @if($withdrawals->isEmpty())
                    <div class="empty-state">
                        <p>No withdrawal history yet</p>
                    </div>
                @else
                    <div class="withdrawal-list">
                        @foreach($withdrawals as $withdrawal)
                            <div class="withdrawal-item">
                                <div class="withdrawal-info">
                                    <div class="withdrawal-amount">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </div>
                                    <div class="withdrawal-bank">
                                        {{ $withdrawal->bank_name }} - {{ $withdrawal->bank_account_number }}
                                    </div>
                                    <div class="withdrawal-bank">
                                        a/n {{ $withdrawal->bank_account_name }}
                                    </div>
                                    <div class="withdrawal-date">
                                        {{ $withdrawal->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>

                                <div>
                                    @if($withdrawal->status === 'pending')
                                        <span class="badge badge-warning">⏳ Pending</span>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="badge badge-success">✓ Approved</span>
                                    @else
                                        <span class="badge badge-danger">✗ Rejected</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: var(--spacing-lg);">
                        {{ $withdrawals->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
<script>
// Auto-format account number with spaces every 4 digits
const accountNumberInput = document.getElementById('bank_account_number');

if (accountNumberInput) {
    accountNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, ''); // Remove existing spaces
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value; // Add space every 4 chars
        e.target.value = formattedValue;
    });

    // On form submit, remove spaces before sending
    accountNumberInput.closest('form').addEventListener('submit', function() {
        accountNumberInput.value = accountNumberInput.value.replace(/\s/g, '');
    });
}
</script>
@endsection