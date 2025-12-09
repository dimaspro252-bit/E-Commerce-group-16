@extends('layouts.app')

@section('title', $product->name . ' - DrizStuff')

@section('content')
<div class="product-detail-container">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>›</span>
            <a href="{{ route('home') }}?category={{ $product->productCategory->id }}">
                {{ $product->productCategory->name }}
            </a>
            <span>›</span>
            <span>{{ $product->name }}</span>
        </div>

        <div class="product-detail-grid">
            <!-- Image Gallery -->
            <div class="product-gallery">
                <img 
                    id="mainImage"
                    src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->image) : asset('images/default-product.png') }}" 
                    alt="{{ $product->name }}"
                    class="main-image">

                @if($product->productImages->count() > 1)
                    <div class="thumbnail-grid">
                        @foreach($product->productImages as $image)
                            <img 
                                src="{{ asset('storage/' . $image->image) }}" 
                                alt="{{ $product->name }}"
                                class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                onclick="changeImage('{{ asset('storage/' . $image->image) }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="product-detail-info">
                <h1>{{ $product->name }}</h1>

                <div class="product-meta">
                    <div class="meta-item">
                        <span>⭐ {{ number_format($averageRating, 1) }}</span>
                        <span style="color: var(--gray);">({{ $totalReviews }} reviews)</span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="badge badge-info">
                            {{ ucfirst($product->condition) }}
                        </span>
                    </div>
                </div>

                <div class="price-section">
                    <div class="product-price-large">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <div class="stock-info">
                        @if($product->stock > 0)
                            ✓ In Stock ({{ $product->stock }} available)
                        @else
                            ✗ Out of Stock
                        @endif
                    </div>
                </div>

                @auth
                    @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf

                            <div class="quantity-section">
                                <div class="quantity-selector">
                                    <span class="qty-label">Quantity:</span>
                                    <div class="qty-input-group">
                                        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="qty-input" readonly>
                                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary btn-lg btn-add-cart">
                                    🛒 Add to Cart
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg btn-buy-now" onclick="buyNow()">
                                    ⚡ Buy Now
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            This product is currently out of stock.
                        </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}">Login</a> to purchase this product.
                    </div>
                @endauth

                <!-- Store Card -->
                <div class="store-card">
                    <div class="store-header">
                        <img 
                            src="{{ $product->store->logo ? asset('storage/' . $product->store->logo) : asset('images/default-store.png') }}" 
                            alt="{{ $product->store->name }}"
                            class="store-logo">
                        <div class="store-info">
                            <h3>{{ $product->store->name }}</h3>
                            @if($product->store->is_verified)
                                <span class="badge badge-success">✓ Verified Seller</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="store-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->store->products->count() }}</div>
                            <div>Products</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $product->store->city }}</div>
                            <div>Location</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="product-description">
            <h2>📝 Product Description</h2>
            <div class="description-content">
                {{ $product->description }}
            </div>
        </div>

        <!-- Reviews -->
        <div class="reviews-section">
            <div class="reviews-header">
                <h2>⭐ Customer Reviews</h2>
                
                @if($totalReviews > 0)
                    <div class="rating-summary">
                        <div class="rating-number">{{ number_format($averageRating, 1) }}</div>
                        <div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $averageRating)
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-text">Based on {{ $totalReviews }} reviews</div>
                        </div>
                    </div>
                @endif
            </div>

            @if($product->productReviews->isEmpty())
                <div class="empty-reviews">
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            @else
                @foreach($product->productReviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    {{ strtoupper(substr($review->transaction->buyer->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="reviewer-name">{{ $review->transaction->buyer->user->name }}</div>
                                    <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <p class="review-text">{{ $review->review }}</p>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="products-section">
                <h2 class="mb-xl">🔥 Related Products</h2>

                <div class="product-grid">
                    @foreach($relatedProducts as $relatedProduct)
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="product-card">
                            <img 
                                src="{{ $relatedProduct->productImages->first() ? asset('storage/' . $relatedProduct->productImages->first()->image) : asset('images/default-product.png') }}" 
                                alt="{{ $relatedProduct->name }}"
                                class="product-image">
                            
                            <div class="product-info">
                                <div class="product-category">{{ $relatedProduct->productCategory->name }}</div>
                                <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                                <div class="product-price">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</div>
                                
                                <div class="product-footer">
                                    <div class="product-store">🏪 {{ $relatedProduct->store->name }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function changeImage(src, element) {
    document.getElementById('mainImage').src = src;
    
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function changeQty(delta) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + delta;
    
    if (value < 1) value = 1;
    if (value > parseInt(input.max)) value = parseInt(input.max);
    
    input.value = value;
}

function buyNow() {
    // Submit form and redirect to checkout
    const form = document.querySelector('form');
    form.action = "{{ route('cart.add', $product) }}";
    form.submit();
    
    // Redirect after a short delay
    setTimeout(() => {
        window.location.href = "{{ route('checkout.index') }}";
    }, 100);
}
</script>
@endsection