@extends('layouts.app')

@section('title', 'Add New Product - DrizStuff')

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

.form-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-sm);
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

.images-upload-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--light-gray);
}

.images-upload-area:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.upload-icon {
    font-size: 48px;
    margin-bottom: var(--spacing-md);
}

.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}

.preview-item {
    position: relative;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.preview-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.remove-preview {
    position: absolute;
    top: 4px;
    right: 4px;
    background: var(--danger);
    color: var(--white);
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 12px;
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
        @include('seller.partials.sidebar', ['activeMenu' => 'products'])

        <!-- Main Content -->
        <main class="form-content">
            <h1 class="mb-xl">➕ Add New Product</h1>

            <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">📋 Basic Information</h3>

                    <div class="form-group">
                        <label for="name" class="form-label">Product Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            value="{{ old('name') }}"
                            class="form-control @error('name') error @enderror"
                            placeholder="e.g. Smartphone XYZ Pro">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="product_category_id" class="form-label">Category *</label>
                        <select 
                            id="product_category_id" 
                            name="product_category_id" 
                            required
                            class="form-control @error('product_category_id') error @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_category_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            required
                            rows="6"
                            class="form-control @error('description') error @enderror"
                            placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div class="form-section">
                    <h3 class="section-title">💰 Pricing & Stock</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="form-label">Price (Rp) *</label>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                required
                                min="0"
                                step="0.01"
                                value="{{ old('price') }}"
                                class="form-control @error('price') error @enderror"
                                placeholder="100000">
                            @error('price')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock" class="form-label">Stock *</label>
                            <input 
                                type="number" 
                                id="stock" 
                                name="stock" 
                                required
                                min="0"
                                value="{{ old('stock') }}"
                                class="form-control @error('stock') error @enderror"
                                placeholder="50">
                            @error('stock')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight" class="form-label">Weight (grams) *</label>
                            <input 
                                type="number" 
                                id="weight" 
                                name="weight" 
                                required
                                min="1"
                                value="{{ old('weight') }}"
                                class="form-control @error('weight') error @enderror"
                                placeholder="500">
                            @error('weight')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="condition" class="form-label">Condition *</label>
                            <select 
                                id="condition" 
                                name="condition" 
                                required
                                class="form-control @error('condition') error @enderror">
                                <option value="">Select Condition</option>
                                <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="second" {{ old('condition') == 'second' ? 'selected' : '' }}>Second</option>
                            </select>
                            @error('condition')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="form-section">
                    <h3 class="section-title">📷 Product Images</h3>

                    <div class="form-group">
                        <label class="form-label">Images * (Min 1, Max 5)</label>
                        <div class="images-upload-area" onclick="document.getElementById('images').click()">
                            <div class="upload-icon">📷</div>
                            <p><strong>Click to upload</strong> or drag and drop</p>
                            <p style="font-size: 12px; color: var(--gray);">
                                PNG, JPG up to 2MB each
                            </p>
                        </div>
                        <input 
                            type="file" 
                            id="images" 
                            name="images[]" 
                            accept="image/*"
                            multiple
                            required
                            style="display: none;"
                            onchange="previewImages(event)"
                            class="@error('images') error @enderror">
                        @error('images')
                            <div class="form-error">{{ $message }}</div>
                        @enderror

                        <div id="imagePreviewGrid" class="image-preview-grid"></div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-md">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Save Product
                    </button>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-outline btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
let selectedFiles = [];

function previewImages(event) {
    const files = Array.from(event.target.files);
    selectedFiles = files;
    
    const previewGrid = document.getElementById('imagePreviewGrid');
    previewGrid.innerHTML = '';
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" class="preview-image">
                <button type="button" class="remove-preview" onclick="removeImage(${index})">✕</button>
            `;
            previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    // Create new file list
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('images').files = dt.files;
    
    // Re-render previews
    const event = { target: { files: selectedFiles } };
    previewImages(event);
}
</script>
@endsection