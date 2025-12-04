<?php

namespace App\Http\Controllers\Admin;

use App\Models\StoreProduct;
use App\Models\StoreOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class StoreProductController extends Controller
{
    /**
     * Display all store products
     */
    public function index()
    {
        $products = StoreProduct::withCount('orders')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.store.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $categories = ['mobile_recharge', 'streaming', 'tv', 'gaming', 'other'];
        $providers = ['MTC', 'Alfa', 'Netflix', 'Anghami', 'Cablevision', 'Spotify', 'Steam', 'PlayStation', 'Xbox'];

        return view('admin.store.products.create', [
            'categories' => $categories,
            'providers' => $providers,
        ]);
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:100',
            'category' => 'required|string|in:mobile_recharge,streaming,tv,gaming,other',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            StoreProduct::create($validated);

            return redirect()->route('admin.store.products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create store product: ' . $e->getMessage());
            return back()->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Show edit form
     */
    public function edit(StoreProduct $product)
    {
        $categories = ['mobile_recharge', 'streaming', 'tv', 'gaming', 'other'];
        $providers = ['MTC', 'Alfa', 'Netflix', 'Anghami', 'Cablevision', 'Spotify', 'Steam', 'PlayStation', 'Xbox'];

        return view('admin.store.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'providers' => $providers,
        ]);
    }

    /**
     * Update product
     */
    public function update(Request $request, StoreProduct $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:100',
            'category' => 'required|string|in:mobile_recharge,streaming,tv,gaming,other',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $product->update($validated);

            return redirect()->route('admin.store.products.index')
                ->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update store product: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Toggle product active/inactive status
     */
    public function toggle(StoreProduct $product)
    {
        try {
            $product->update(['is_active' => !$product->is_active]);

            $status = $product->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Product {$status} successfully!");
        } catch (\Exception $e) {
            Log::error('Failed to toggle store product: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product status.');
        }
    }

    /**
     * Delete product
     */
    public function destroy(StoreProduct $product)
    {
        try {
            // Check if product has orders
            if ($product->orders()->exists()) {
                return back()->with('error', 'Cannot delete product with existing orders. Deactivate instead.');
            }

            $product->delete();
            return back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete store product: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product.');
        }
    }

    /**
     * Display all store orders
     */
    public function viewOrders()
    {
        $orders = StoreOrder::with(['user', 'product'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.store.orders.index', [
            'orders' => $orders,
        ]);
    }
}
