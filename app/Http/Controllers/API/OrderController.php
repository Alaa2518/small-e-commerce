<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // Store a new order
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(), // Ensure the user is authenticated
            'total_amount' => 0, // We'll calculate this below
        ]);

        $totalAmount = 0;

        // Attach products to the order with quantities
        foreach ($validatedData['products'] as $productData) {
            $product = Product::find($productData['id']);

            // Check stock availability
            if ($product->stock_quantity < $productData['quantity']) {
                return response()->json([
                    'message' => "Insufficient stock for product: {$product->name}",
                ], 400);
            }

            // Update product stock
            $product->decrement('stock_quantity', $productData['quantity']);

            // Calculate total amount
            $totalAmount += $product->price * $productData['quantity'];

            // Attach product to order
            $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
        }

        // Update order total amount
        $order->update(['total_amount' => $totalAmount]);
        event(new OrderPlaced($order));

        return response()->json($order, 201);
    }

    // Retrieve all orders with pagination
    public function index(Request $request)
    {
        // Retrieve the page and per_page values from query parameters (default: 10 items per page)
        $perPage = $request->query('per_page', 10); // Default is 10 orders per page
        $page = $request->query('page', 1); // Default to page 1

        // Eager load products and apply pagination
        $orders = Order::with('products')->paginate($perPage, ['*'], 'page', $page);

        // Custom response with pagination metadata
        return response()->json([
            'data' => $orders->items(),            // Orders data for the current page
            'current_page' => $orders->currentPage(), // Current page number
            'last_page' => $orders->lastPage(),    // Last page number
            'per_page' => $orders->perPage(),      // Items per page
            'total' => $orders->total(),           // Total number of orders
        ]);
    }

    // Retrieve a specific order by ID
    public function show($id)
    {
        $order = Order::with('products')->findOrFail($id);
        return response()->json($order);
    }
}
