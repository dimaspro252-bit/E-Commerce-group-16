@extends('layouts.app')

@section('title', 'Forgot Password - DrizStuff')

@push('styles')
<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-2xl) 0;
}

.auth-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    width: 100%;
    max-width: 450px;
    padding: var(--spacing-2xl);
}

.auth-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.auth-logo {
    font-size: 48px;
    margin-bottom: var(--spacing-sm);
}

.auth-title {
    font-size: 28px;
    margin-bottom: var(--spacing-xs);
    color: var(--dark);
}

.auth-subtitle {
    color: var(--gray);
    font-size: 14px;
    line-height: 1.6;
}

.auth-form {
    margin-bottom: var(--spacing-lg);
}

.form-actions {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.form-footer {
    text-align: center;
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border);
}

.form-footer a {
    color: var(--primary);
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">🔑</div>
                <h1 class="auth-title">Forgot Password?</h1>
                <p class="auth-subtitle">
                    No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-md">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="form-control @error('email') error @enderror"
                        placeholder="your@email.com">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        📧 Email Password Reset Link
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="form-footer">
                <p>Remember your password? 
                    <a href="{{ route('login') }}">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection