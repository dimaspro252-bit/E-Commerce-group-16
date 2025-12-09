@extends('layouts.app')

@section('title', 'Login - DrizStuff')

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

.remember-me {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 14px;
}

.remember-me input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: var(--spacing-lg) 0;
    color: var(--gray);
    font-size: 14px;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid var(--border);
}

.divider span {
    padding: 0 var(--spacing-md);
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-logo">🛍️</div>
                <h1 class="auth-title">Welcome Back!</h1>
                <p class="auth-subtitle">Login to continue shopping</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-md">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="auth-form">
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
                        autocomplete="username"
                        class="form-control @error('email') error @enderror"
                        placeholder="your@email.com">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="form-control @error('password') error @enderror"
                        placeholder="Enter your password">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex-between mb-md">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        🔐 Login
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Footer -->
            <div class="form-footer">
                <p>Don't have an account? 
                    <a href="{{ route('register') }}">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection