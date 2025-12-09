@extends('layouts.app')

@section('title', 'Write Review - DrizStuff')

@push('styles')
<style>
.review-container {
    padding: var(--spacing-2xl) 0;
    min-height: 60vh;
}

.review-card {
    max-width: 800px;
    margin: 0 auto;
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-lg);
}

.review-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
}

.review-icon {
    font-size: 64px;
    margin-bottom: var(--spacing-md);
}

.product-info-card {
    background: var(--light-gray);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-2xl);
    display: flex;
    gap: var(--spacing-lg);
    align-items: center;
}

.product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: var(--white);
}

.product-details h3 {
    margin-bottom: var(--spacing-xs);
}

.product-meta {
    font-size: 14px;
    color: var(--gray);
}

.rating-selector {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
}

.rating-selector h3 {
    margin-bottom: var(--spacing-lg);
}

.star-rating {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
    font-size: 48px;
    margin-bottom: var(--spacing-md);
}

.star {
    cursor: pointer;
    transition: all 0.2s;
    filter: grayscale(100%);
}

.star:hover,
.star.active {
    filter: grayscale(0%);
    transform: scale(1.2);
}

.rating-label {
    font-size: 18px;
    font-weight: 600;
    color: var(--primary);
}

.review-form-section {
    margin-bottom: var(--spacing-xl);
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
}

@media (max-width: 768px) {
    .product-info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .star-rating {
        font-size: 36px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="review-container">
    <div class="container">
        <div class="review-card">
            <!-- Header -->
            <div class="review-header">
                <div class="review-icon">⭐</div>
                <h1>Write Your Review</h1>
                <p style="color: var(--gray);">Share your experience with this product</p>
            </div>

            <!-- Product Info -->
            <div class="product-info-card">
                <img 
                    src="{{ $product->productImages->first() ? asset('storage/' . $product->productImages->first()->image) : asset('images/default-product.png') }}" 
                    alt="{{ $product->name }}"
                    class="product-image">
                
                <div class="product-details">
                    <h3>{{ $product->name }}</h3>
                    <div class="product-meta">
                        <div>🏪 {{ $product->store->name }}</div>
                        <div>📦 Order: {{ $transaction->code }}</div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <form method="POST" action="{{ route('reviews.store', $product) }}">
                @csrf
                
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                <input type="hidden" name="rating" id="rating" value="5">

                <!-- Rating Selector -->
                <div class="rating-selector">
                    <h3>How would you rate this product?</h3>
                    
                    <div class="star-rating">
                        <span class="star active" data-rating="1" onclick="setRating(1)">⭐</span>
                        <span class="star active" data-rating="2" onclick="setRating(2)">⭐</span>
                        <span class="star active" data-rating="3" onclick="setRating(3)">⭐</span>
                        <span class="star active" data-rating="4" onclick="setRating(4)">⭐</span>
                        <span class="star active" data-rating="5" onclick="setRating(5)">⭐</span>
                    </div>

                    <div class="rating-label" id="ratingLabel">Excellent!</div>
                </div>

                @error('rating')
                    <div class="alert alert-danger mb-lg">{{ $message }}</div>
                @enderror

                <!-- Review Text -->
                <div class="review-form-section">
                    <label for="review" class="form-label">
                        <h3>Share your experience</h3>
                    </label>
                    <textarea 
                        id="review" 
                        name="review" 
                        required
                        rows="8"
                        class="form-control @error('review') error @enderror"
                        placeholder="Tell us what you think about this product. What did you like or dislike? How was the quality? Would you recommend it to others?"
                        maxlength="1000">{{ old('review') }}</textarea>
                    
                    <div style="text-align: right; font-size: 12px; color: var(--gray); margin-top: var(--spacing-xs);">
                        <span id="charCount">0</span> / 1000 characters
                    </div>

                    @error('review')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline btn-lg">
                        ← Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        📝 Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const ratingLabels = {
    1: 'Poor',
    2: 'Fair',
    3: 'Good',
    4: 'Very Good',
    5: 'Excellent!'
};

function setRating(rating) {
    // Update hidden input
    document.getElementById('rating').value = rating;
    
    // Update label
    document.getElementById('ratingLabel').textContent = ratingLabels[rating];
    
    // Update stars
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

// Character counter
const reviewTextarea = document.getElementById('review');
const charCount = document.getElementById('charCount');

reviewTextarea.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// Initialize char count
charCount.textContent = reviewTextarea.value.length;
</script>
@endsection