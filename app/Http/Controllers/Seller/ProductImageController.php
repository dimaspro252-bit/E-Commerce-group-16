<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Upload product image
     */
    public function store(Request $request, Product $product)
    {
        // Check ownership
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('products', 'public');

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $path,
            'is_thumbnail' => false,
        ]);

        return back()->with('success', 'Image uploaded successfully!');
    }

    /**
     * Delete product image
     */
    public function destroy(ProductImage $image)
    {
        // Check ownership
        if ($image->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Set as thumbnail
     */
    public function setThumbnail(ProductImage $image)
    {
        // Check ownership
        if ($image->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        // Remove thumbnail from other images
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_thumbnail' => false]);

        // Set this as thumbnail
        $image->update(['is_thumbnail' => true]);

        return back()->with('success', 'Thumbnail set successfully!');
    }
}