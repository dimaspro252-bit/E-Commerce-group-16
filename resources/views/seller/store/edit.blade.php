@extends('layouts.app')

@section('title', 'Store Settings - DrizStuff')

@push('styles')
<style>
.seller-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-xl);
    padding: var(--spacing-2xl) 0;
}

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

.settings-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-sm);
}

.section-header {
    margin-bottom: var(--spacing-xl);
}

.form-section {
    margin-bottom: var(--spacing-xl);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--border);
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: var(--spacing-lg);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

.current-logo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: var(--spacing-md);
    border: 3px solid var(--border);
}

.image-upload-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--light-gray);
}

.image-upload-area:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.upload-icon {
    font-size: 32px;
    margin-bottom: var(--spacing-sm);
}

.danger-zone {
    background: #FEF2F2;
    border: 2px solid var(--danger);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.danger-zone h3 {
    color: var(--danger);
    margin-bottom: var(--spacing-md);
}

@media (max-width: 768px) {
    .seller-layout {
        grid-template-columns: 1fr;
    }
    
    .seller-sidebar {
        position: static;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="seller-layout">
        <!-- Sidebar -->
        @include('seller.partials.sidebar', ['activeMenu' => 'settings'])

        <!-- Main Content -->
        <main class="settings-content">
            <div class="section-header">
                <h1>⚙️ Store Settings</h1>
                <p style="color: var(--gray);">Manage your store information and preferences</p>
            </div>

            <form method="POST" action="{{ route('seller.store.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Store Information -->
                <div class="form-section">
                    <h3 class="section-title">🏪 Store Information</h3>

                    <div class="form-group">
                        <label class="form-label">Current Logo</label>
                        <div>
                            <img 
                                src="{{ $store->logo ? asset('storage/' . $store->logo) : asset('images/default-store.png') }}" 
                                alt="{{ $store->name }}"
                                class="current-logo">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="logo" class="form-label">Update Logo (Optional)</label>
                        <div class="image-upload-area" onclick="document.getElementById('logo').click()">
                            <div class="upload-icon">📷</div>
                            <p><strong>Click to upload new logo</strong></p>
                            <p style="font-size: 12px; color: var(--gray);">
                                PNG, JPG up to 2MB
                            </p>
                        </div>
                        <input 
                            type="file" 
                            id="logo" 
                            name="logo" 
                            accept="image/*"
                            style="display: none;"
                            class="@error('logo') error @enderror">
                        @error('logo')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Store Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            value="{{ old('name', $store->name) }}"
                            class="form-control @error('name') error @enderror">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="about" class="form-label">About Store</label>
                        <textarea 
                            id="about" 
                            name="about" 
                            required
                            rows="4"
                            class="form-control @error('about') error @enderror">{{ old('about', $store->about) }}</textarea>
                        @error('about')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="section-title">📞 Contact Information</h3>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            required
                            value="{{ old('phone', $store->phone) }}"
                            class="form-control @error('phone') error @enderror">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Store Address -->
                <div class="form-section">
                    <h3 class="section-title">📍 Store Address</h3>

                    <div class="form-group">
                        <label for="address" class="form-label">Full Address</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            required
                            rows="3"
                            class="form-control @error('address') error @enderror">{{ old('address', $store->address) }}</textarea>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">City</label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                required
                                value="{{ old('city', $store->city) }}"
                                class="form-control @error('city') error @enderror">
                            @error('city')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                required
                                value="{{ old('postal_code', $store->postal_code) }}"
                                class="form-control @error('postal_code') error @enderror">
                            @error('postal_code')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address_id" class="form-label">Address Label</label>
                        <input 
                            type="text" 
                            id="address_id" 
                            name="address_id" 
                            required
                            value="{{ old('address_id', $store->address_id) }}"
                            class="form-control @error('address_id') error @enderror">
                        @error('address_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex gap-md">
                    <button type="submit" class="btn btn-primary btn-lg">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-outline btn-lg">
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Danger Zone -->
            <div class="danger-zone">
                <h3>🗑️ Delete Store</h3>
                <p style="color: var(--gray); margin-bottom: var(--spacing-lg);">
                    Once you delete your store, all of its data will be permanently deleted. 
                    This action cannot be undone.
                </p>
                
                <form method="POST" action="{{ route('seller.store.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your store? This action cannot be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary">
                        🗑️ Delete Store
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection