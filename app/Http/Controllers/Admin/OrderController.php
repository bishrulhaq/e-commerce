<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(): Response
    {
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(10);
        return Inertia::render('Admin/Orders/Index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(): Response
    {
        $products = Product::all();
        return Inertia::render('Admin/Orders/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:' . implode(',', PaymentMethod::values()),
        ]);

        // Create Order
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => OrderStatus::PENDING->value,
            'payment_method' => $validated['payment_method'],
            'total' => 0,
        ]);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->regular_price * $item['quantity']; // Use correct price field

            // Add Order Item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->regular_price, // Store price at the time of order
                'subtotal' => $subtotal,
            ]);

            $totalAmount += $subtotal;
        }

        // Update total price
        $order->update(['total' => $totalAmount]);
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Show the details of a specific order.
     */
    public function edit(Order $order): Response
    {
        return Inertia::render('Admin/Orders/Edit', [
            'order' => $order->load(['user', 'items.product']),
            'customers' => User::where('role_id', 2)->get(),
            'products' => Product::all(),
        ]);
    }

    /**
     * Update the status of an order.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:pending,processing,shipped,delivered,canceled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:' . implode(',', PaymentMethod::values()),
        ]);

        // Update order details
        $order->update([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
        ]);

        // Remove old items and recalculate total
        $order->items()->delete();
        $totalAmount = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->regular_price;
            $subtotal = $price * $item['quantity'];

            // Add updated order items
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $subtotal,
            ]);

            $totalAmount += $subtotal;
        }

        // Update total order price
        $order->update(['total' => $totalAmount]);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Delete an order (if allowed).
     */
    public function destroy(Order $order): RedirectResponse
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Cannot delete order with dependencies.');
        }
    }
}
