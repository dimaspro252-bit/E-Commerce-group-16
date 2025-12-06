<?
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Homepage
    public function index(Request $request)
    {
        $categories = ProductCategory::orderBy('name')->get();

        $productsQuery = Product::with(['productImages', 'productCategory', 'store']);

        if ($request->filled('category')) {
            $productsQuery->where('product_category_id', $request->category);
        }

        $products = $productsQuery->latest()->paginate(12);

        return view('customer.home', compact('products', 'categories'));
    }

    // Product detail
    public function show(Product $product)
    {
        $product->load([
            'productImages',
            'productCategory',
            'productReviews',
            'store'
        ]);

        return view('customer.product-show', compact('product'));
    }
}
