@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Images --}}
        <div>
            <div class="border rounded-lg overflow-hidden mb-3">
                @if($product->productImages->first())
                    <img src="{{ asset($product->productImages->first()->image_url) }}"
                         class="w-full h-80 object-cover">
                @else
                    <div class="h-80 bg-gray-100 flex items-center justify-center">
                        No Image
                    </div>
                @endif
            </div>

            <div class="flex gap-2 flex-wrap">
                @foreach($product->productImages as $img)
                    <img src="{{ asset($img->image_url) }}"
                         class="w-20 h-20 object-cover border rounded">
                @endforeach
            </div>
        </div>

        {{-- Detail --}}
        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-gray-500 mt-1">
                Category: {{ $product->productCategory?->name }}
            </p>
            <p class="text-gray-500">
                Store: {{ $product->store?->name }}
            </p>

            <p class="text-2xl font-bold mt-4">
                Rp {{ number_format($product->price,0,',','.') }}
            </p>

            <p class="mt-4">{{ $product->description }}</p>

            <p class="mt-4 text-sm">
                Stock: {{ $product->stock }}
            </p>

            <button class="mt-6 px-5 py-2 bg-black text-white rounded">
                Add to Cart
            </button>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-3">Reviews</h2>
        @forelse($product->productReviews as $review)
            <div class="border-b py-3">
                {{-- Model kamu belum ada user, jadi tampilkan info sederhana dulu --}}
                <p class="text-yellow-500">Rating: {{ $review->rating ?? '-' }}/5</p>
                <p>{{ $review->comment ?? '-' }}</p>
            </div>
        @empty
            <p>No reviews yet.</p>
        @endforelse
    </div>
</div>
@endsection
