@extends('layouts.app')

@section('title', 'Register - DrizStuff')

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

.password-requirements {
    background: var(--light-gray);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-top: var(--spacing-sm);
}

.password-requirements h4 {
    font-size: 14px;
    margin-bottom: var(--spacing-sm);
    color: var(--dark);
}

.password-requirements ul {
    list-style: none;
    font-size: 12px;
    color: var(--gray);
}

.password-requirements li {
    padding: 2px 0;
}

.password-requirements li::before {
    content: "• ";
    color: var(--primary);
    font-weight: bold;
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
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join DrizStuff and start shopping!</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        class="form-control @error('name') error @enderror"
                        placeholder="John Doe">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
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
                        autocomplete="new-password"
                        class="form-control @error('password') error @enderror"
                        placeholder="Create a strong password">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <h4>Password must contain:</h4>
                        <ul>
                            <li>At least 8 characters</li>
                            <li>Mix of uppercase & lowercase letters</li>
                            <li>At least one number or special character</li>
                        </ul>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="form-control"
                        placeholder="Re-enter your password">
                </div>

                <!-- Terms & Conditions -->
                <div class="form-group">
                    <label class="remember-me" style="display: flex; align-items: start; gap: 8px;">
                        <input type="checkbox" required style="margin-top: 4px;">
                        <span style="font-size: 12px; color: var(--gray);">
                            I agree to the <a href="#" style="color: var(--primary);">Terms & Conditions</a> 
                            and <a href="#" style="color: var(--primary);">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        🎉 Create Account
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="form-footer">
                <p>Already have an account? 
                    <a href="{{ route('login') }}">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection