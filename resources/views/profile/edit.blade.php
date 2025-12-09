@extends('layouts.app')

@section('title', 'Profile Settings - DrizStuff')

@push('styles')
<style>
.profile-container {
    padding: var(--spacing-2xl) 0;
}

.profile-grid {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
}

/* Sidebar */
.profile-sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 700;
    margin: 0 auto var(--spacing-md);
}

.profile-name {
    text-align: center;
    font-weight: 600;
    font-size: 18px;
    margin-bottom: var(--spacing-xs);
}

.profile-email {
    text-align: center;
    font-size: 14px;
    color: var(--gray);
    margin-bottom: var(--spacing-lg);
}

.profile-nav {
    list-style: none;
    border-top: 1px solid var(--border);
    padding-top: var(--spacing-md);
}

.profile-nav-item {
    margin-bottom: var(--spacing-xs);
}

.profile-nav-link {
    display: block;
    padding: 10px 12px;
    border-radius: var(--radius-md);
    color: var(--dark);
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
}

.profile-nav-link:hover,
.profile-nav-link.active {
    background: var(--primary-light);
    color: var(--primary);
}

/* Main Content */
.profile-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.profile-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
}

.section-header {
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border);
}

.section-title {
    font-size: 20px;
    margin-bottom: var(--spacing-xs);
}

.section-description {
    font-size: 14px;
    color: var(--gray);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

/* Danger Zone */
.danger-zone {
    border: 2px solid var(--danger);
    background: #FEF2F2;
}

.danger-zone .section-header {
    border-bottom-color: var(--danger);
}

.danger-zone .section-title {
    color: var(--danger);
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        position: static;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="container">
        <div class="profile-grid">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-email">{{ $user->email }}</div>

                <ul class="profile-nav">
                    <li class="profile-nav-item">
                        <a href="#info" class="profile-nav-link active">
                            👤 Profile Information
                        </a>
                    </li>
                    <li class="profile-nav-item">
                        <a href="#password" class="profile-nav-link">
                            🔒 Change Password
                        </a>
                    </li>
                    <li class="profile-nav-item">
                        <a href="#delete" class="profile-nav-link">
                            🗑️ Delete Account
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <div class="profile-content">
                <!-- Profile Information -->
                <section id="info" class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Profile Information</h2>
                        <p class="section-description">
                            Update your account's profile information and email address.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}"
                                required
                                class="form-control @error('name') error @enderror">
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                required
                                class="form-control @error('email') error @enderror">
                            @error('email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="alert alert-warning mt-md">
                                    Your email address is unverified.
                                    <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline">
                                            Resend Verification Email
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-md">
                            <button type="submit" class="btn btn-primary">
                                💾 Save Changes
                            </button>
                            
                            @if (session('status') === 'profile-updated')
                                <div class="text-success" style="display: flex; align-items: center;">
                                    ✅ Saved successfully!
                                </div>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- Change Password -->
                <section id="password" class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Update Password</h2>
                        <p class="section-description">
                            Ensure your account is using a long, random password to stay secure.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password"
                                required
                                class="form-control @error('current_password', 'updatePassword') error @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                required
                                class="form-control @error('password', 'updatePassword') error @enderror">
                            @error('password', 'updatePassword')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                required
                                class="form-control">
                        </div>

                        <div class="flex gap-md">
                            <button type="submit" class="btn btn-primary">
                                🔒 Update Password
                            </button>
                            
                            @if (session('status') === 'password-updated')
                                <div class="text-success" style="display: flex; align-items: center;">
                                    ✅ Password updated!
                                </div>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- Delete Account -->
                <section id="delete" class="profile-section danger-zone">
                    <div class="section-header">
                        <h2 class="section-title">Delete Account</h2>
                        <p class="section-description">
                            Once your account is deleted, all of its resources and data will be permanently deleted. 
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                    </div>

                    <button 
                        type="button" 
                        class="btn btn-secondary"
                        onclick="document.getElementById('deleteModal').style.display='flex'">
                        🗑️ Delete Account
                    </button>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 500px; margin: 16px;">
        <h3 style="margin-bottom: 16px;">Are you sure?</h3>
        <p style="color: var(--gray); margin-bottom: 24px;">
            Once your account is deleted, all of its resources and data will be permanently deleted. 
            Please enter your password to confirm you would like to permanently delete your account.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="delete_password" 
                    name="password"
                    required
                    class="form-control"
                    placeholder="Enter your password">
            </div>

            <div class="flex gap-md">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('deleteModal').style.display='none'">
                    Cancel
                </button>
                <button type="submit" class="btn btn-secondary">
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection