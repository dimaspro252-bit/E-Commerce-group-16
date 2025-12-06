@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <form method="GET" class="mb-6">
        <select name="category" onchange="this.form.submit()"
            class="border rounded px-3 py-2">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    @selected(request('category') == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
            <a href="{{ route('products.show', $product) }}"
               class="border rounded-lg overflow-hidden hover:shadow">
                <div class="h-40 bg-gray-100">
                    @if($product->productImages->first())
                        <img src="{{ asset($product->productImages->first()->image_url) }}"
                             class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-3">
                    <p class="font-semibold">{{ $product->name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $product->productCategory?->name }}
                    </p>
                    <p class="mt-2 font-bold">
                        Rp {{ number_format($product->price,0,',','.') }}
                    </p>
                </div>
            </a>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
