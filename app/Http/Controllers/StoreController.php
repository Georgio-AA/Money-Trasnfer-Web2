<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use App\Models\StoreOrder;
use App\Models\User;
use App\Services\StoreCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    /**
     * Display all active store products
     */
    public function index()
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('error', 'Please login to access the store.');
        }

        // Get current user and ensure balance is in session
        $user = User::find(Session::get('user.id'));
        if ($user) {
            // Update session with fresh user data including balance
            Session::put('user', $user->toArray());
        }

        // Get all active products
        $query = StoreProduct::active()
            ->orderBy('category')
            ->orderBy('provider');

        // Filter by category if provided
        if (request('category')) {
            $query->where('category', request('category'));
        }

        $products = $query->get();

        return view('store.index', [
            'products' => $products,
        ]);
    }

    /**
     * Handle product purchase
     */
    public function buy(Request $request, StoreProduct $product)
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('error', 'Please login to make a purchase.');
        }

        try {
            $userId = Session::get('user.id');
            $user = User::find($userId);

            // Validate product is active
            if (!$product->is_active) {
                return back()->with('error', 'This product is no longer available.');
            }

            // Check if user has sufficient balance
            if ($user->balance < $product->price) {
                return back()->with('error', 'Insufficient balance. Please reload your account.');
            }

            // Process purchase within a transaction to ensure consistency
            DB::beginTransaction();

            try {
                // Deduct balance from user
                $user->decrement('balance', $product->price);

                // Generate unique code
                $generatedCode = StoreCodeGenerator::generate();

                // Create store order
                $order = StoreOrder::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'price_at_purchase' => $product->price,
                    'generated_code' => $generatedCode,
                    'status' => 'completed', // Marked as completed immediately
                ]);

                DB::commit();

                // Update session with new balance (update full user array)
                $user = $user->fresh();
                Session::put('user', $user->toArray());

                return redirect()->route('store.confirmation', $order->id)
                    ->with('success', 'Purchase completed successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Store purchase failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to process purchase. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Store purchase error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Show purchase confirmation page
     */
    public function confirmation($orderId)
    {
        if (!Session::has('user')) {
            return redirect()->route('login');
        }

        $order = StoreOrder::findOrFail($orderId);

        // Verify order belongs to current user
        if ($order->user_id !== Session::get('user.id')) {
            abort(403, 'Unauthorized');
        }

        // Get current user and ensure balance is in session
        $user = User::find(Session::get('user.id'));
        if ($user) {
            Session::put('user', $user->toArray());
        }

        // Format code for display
        $formattedCode = StoreCodeGenerator::formatForDisplay($order->generated_code);

        return view('store.confirmation', [
            'order' => $order,
            'formattedCode' => $formattedCode,
        ]);
    }

    /**
     * Display user's past purchases
     */
    public function myPurchases()
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('error', 'Please login to view your purchases.');
        }

        // Get current user and ensure balance is in session
        $user = User::find(Session::get('user.id'));
        if ($user) {
            Session::put('user', $user->toArray());
        }

        $userId = Session::get('user.id');
        $orders = StoreOrder::forUser($userId)
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('store.my_purchases', [
            'purchases' => $orders,
        ]);
    }
}
