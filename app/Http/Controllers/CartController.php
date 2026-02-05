<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $productIds = array_keys($cart);
        
        $products = Product::with(['shop', 'images'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
        
        $cartItems = collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get($productId);
            if (!$product) return null;
            
            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price ? (float) $product->price * $quantity : 0,
            ];
        })->filter()->values();
        
        $total = $cartItems->sum('subtotal');
        
        $paymentMethods = PaymentMethod::query()->active()->ordered()->get();

        return view('pages.cart.index', compact('cartItems', 'total', 'paymentMethods'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;
        $quantity = max(1, (int) $request->input('quantity', 1));
        
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $request->session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'cart_count' => array_sum($cart),
                'message' => app()->getLocale() === 'ar' ? 'تمت إضافة المنتج إلى السلة.' : 'Product added to cart.',
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تمت إضافة المنتج إلى السلة.' : 'Product added to cart.');
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;
        $quantity = max(1, (int) $request->input('quantity', 1));
        
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
            $request->session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'cart_count' => array_sum($cart),
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الكمية.' : 'Quantity updated.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;
        
        unset($cart[$productId]);
        $request->session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'cart_count' => array_sum($cart),
                'message' => app()->getLocale() === 'ar' ? 'تمت إزالة المنتج من السلة.' : 'Product removed from cart.',
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تمت إزالة المنتج من السلة.' : 'Product removed from cart.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'cart_count' => 0,
                'message' => app()->getLocale() === 'ar' ? 'تم إفراغ السلة.' : 'Cart cleared.',
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تم إفراغ السلة.' : 'Cart cleared.');
    }

    /**
     * Get the current cart count (for AJAX).
     */
    public function count(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return response()->json(['count' => array_sum($cart)]);
    }
}
