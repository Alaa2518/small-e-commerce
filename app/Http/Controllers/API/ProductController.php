<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // Store a new product
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:1',
        ]);

        $product = Product::create($validatedData);
        Cache::forget('products'); // Clear cache when a product is created

        return response()->json($product, 201);
    }

    // Retrieve products with caching, pagination, and search filters
    public function index(Request $request)
    {
        // Retrieve query parameters
        $search   = $request->query('search');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $perPage  = $request->query('per_page', 10); // Default 10 products per page

        // Cache the product query results for 60 seconds
        $products = Cache::remember(
            "products_{$search}_{$minPrice}_{$maxPrice}_{$perPage}_{$request->query('page', 1)}",
            60,
            function () use ($search, $minPrice, $maxPrice, $perPage) {
                $query = Product::query();

                // Search by name
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }

                // Filter by price range
                if ($minPrice) {
                    $query->where('price', '>=', $minPrice);
                }
                if ($maxPrice) {
                    $query->where('price', '<=', $maxPrice);
                }

                return $query->paginate($perPage); // Paginate with the given perPage value
            }
        );

        // Custom response with pagination metadata
        return response()->json([
            'data'         => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'per_page'     => $products->perPage(),
            'total'        => $products->total(),
        ]);
    }

}
