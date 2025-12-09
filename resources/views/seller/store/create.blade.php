@extends('layouts.app')

@section('title', 'Register as Seller - DrizStuff')

@push('styles')
<style>
.register-store-container {
    padding: var(--spacing-2xl) 0;
    min-height: 70vh;
}

.register-store-card {
    max-width: 800px;
    margin: 0 auto;
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
}

.register-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
}

.register-icon {
    font-size: 64px;
    margin-bottom: var(--spacing-md);
}

.register-title {
    font-size: 32px;
    margin-bottom: var(--spacing-sm);
}

.register-subtitle {
    color: var(--gray);
    font-size: 16px;
}

.form-section {
    margin-bottom: var(--spacing-xl);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--border);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

.image-upload-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
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
    font-size: 48px;
    margin-bottom: var(--spacing-md);
}

.image-preview {
    max-width: 200px;
    max-height: 200px;
    margin: var(--spacing-md) auto 0;
    border-radius: var(--radius-md);
}

.requirements-box {
    background: var(--light-gray);
    border-left: 4px solid var(--primary);
    padding: var(--spacing-lg);
    border-radius: var(--radius-md);
    margin-top: var(--spacing-xl);
}

.requirements-box h4 {
    margin-bottom: var(--spacing-md);
}

.requirements-box ul {
    list-style: none;
    font-size: 14px;
}

.requirements-box li {
    padding: 4px 0;
    color: var(--gray);
}

.requirements-box li::before {
    content: "✓ ";
    color: var(--success);
    font-weight: bold;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="register-store-container">
    <div class="container">
        <div class="register-store-card">
            <!-- Header -->
            <div class="register-header">
                <div class="register-icon">🏪</div>
                <h1 class="register-title">Become a Seller</h1>
                <p class="register-subtitle">
                    Start your online business and reach thousands of customers
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('seller.register.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Store Information -->
                <div class="form-section">
                    <h3 class="section-title">🏪 Store Information</h3>

                    <div class="form-group">
                        <label for="name" class="form-label">Store Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            value="{{ old('name') }}"
                            class="form-control @error('name') error @enderror"
                            placeholder="e.g. Tech Store, Fashion Hub">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="logo" class="form-label">Store Logo *</label>
                        <div class="image-upload-area" onclick="document.getElementById('logo').click()">
                            <div class="upload-icon">📷</div>
                            <p><strong>Click to upload</strong> or drag and drop</p>
                            <p style="font-size: 12px; color: var(--gray);">
                                PNG, JPG up to 2MB
                            </p>
                            <img id="logoPreview" class="image-preview" style="display: none;">
                        </div>
                        <input 
                            type="file" 
                            id="logo" 
                            name="logo" 
                            accept="image/*"
                            required
                            style="display: none;"
                            onchange="previewImage(event, 'logoPreview')"
                            class="@error('logo') error @enderror">
                        @error('logo')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="about" class="form-label">About Store *</label>
                        <textarea 
                            id="about" 
                            name="about" 
                            required
                            rows="4"
                            class="form-control @error('about') error @enderror"
                            placeholder="Tell customers about your store, what you sell, and what makes you unique...">{{ old('about') }}</textarea>
                        @error('about')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="section-title">📞 Contact Information</h3>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            required
                            value="{{ old('phone') }}"
                            class="form-control @error('phone') error @enderror"
                            placeholder="e.g. 081234567890">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Store Address -->
                <div class="form-section">
                    <h3 class="section-title">📍 Store Address</h3>

                    <div class="form-group">
                        <label for="address" class="form-label">Full Address *</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            required
                            rows="3"
                            class="form-control @error('address') error @enderror"
                            placeholder="Enter your complete store/warehouse address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">City *</label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                required
                                value="{{ old('city') }}"
                                class="form-control @error('city') error @enderror"
                                placeholder="e.g. Jakarta">
                            @error('city')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="postal_code" class="form-label">Postal Code *</label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                required
                                value="{{ old('postal_code') }}"
                                class="form-control @error('postal_code') error @enderror"
                                placeholder="e.g. 12345">
                            @error('postal_code')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address_id" class="form-label">Address Label *</label>
                        <input 
                            type="text" 
                            id="address_id" 
                            name="address_id" 
                            required
                            value="{{ old('address_id') }}"
                            class="form-control @error('address_id') error @enderror"
                            placeholder="e.g. Warehouse, Main Store">
                        @error('address_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Requirements Info -->
                <div class="requirements-box">
                    <h4>📋 Requirements to Become a Seller</h4>
                    <ul>
                        <li>You must have a valid email address</li>
                        <li>Store will be reviewed by admin before being verified</li>
                        <li>Provide accurate and complete information</li>
                        <li>Follow our seller guidelines and policies</li>
                        <li>Maintain good customer service standards</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline btn-lg">
                        ← Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" style="flex: 1;">
                        🚀 Register Store
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event, previewId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection