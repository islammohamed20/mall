<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Display the favorites page.
     */
    public function index(Request $request)
    {
        $favoriteIds = collect($request->session()->get('favorites', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
        
        $products = Product::with(['shop'])
            ->whereIn('id', $favoriteIds)
            ->get();
        
        return view('pages.favorites.index', compact('products'));
    }

    /**
     * Toggle a product in the favorites.
     */
    public function toggle(Request $request, Product $product)
    {
        $favorites = collect($request->session()->get('favorites', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $productId = (int) $product->id;
        $isAdded = false;

        if (in_array($productId, $favorites, true)) {
            $favorites = array_values(array_filter($favorites, fn ($id) => (int) $id !== $productId));
            $message = app()->getLocale() === 'ar' ? 'تمت إزالة المنتج من المفضلة.' : 'Product removed from favorites.';
        } else {
            $favorites[] = $productId;
            $isAdded = true;
            $message = app()->getLocale() === 'ar' ? 'تمت إضافة المنتج إلى المفضلة.' : 'Product added to favorites.';
        }

        $request->session()->put('favorites', $favorites);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'is_favorite' => $isAdded,
                'favorites_count' => count($favorites),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove a product from favorites.
     */
    public function remove(Request $request, Product $product)
    {
        $favorites = collect($request->session()->get('favorites', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $productId = (int) $product->id;
        $favorites = array_values(array_filter($favorites, fn ($id) => (int) $id !== $productId));
        
        $request->session()->put('favorites', $favorites);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'favorites_count' => count($favorites),
                'message' => app()->getLocale() === 'ar' ? 'تمت إزالة المنتج من المفضلة.' : 'Product removed from favorites.',
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تمت إزالة المنتج من المفضلة.' : 'Product removed from favorites.');
    }

    /**
     * Clear all favorites.
     */
    public function clear(Request $request)
    {
        $request->session()->forget('favorites');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'favorites_count' => 0,
                'message' => app()->getLocale() === 'ar' ? 'تم إفراغ المفضلة.' : 'Favorites cleared.',
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' ? 'تم إفراغ المفضلة.' : 'Favorites cleared.');
    }

    /**
     * Check if a product is in favorites (for AJAX).
     */
    public function check(Request $request, Product $product)
    {
        $favorites = $request->session()->get('favorites', []);
        $isFavorite = in_array((int) $product->id, array_map('intval', $favorites), true);
        
        return response()->json([
            'is_favorite' => $isFavorite,
            'count' => count($favorites),
        ]);
    }

    /**
     * Get favorites count (for AJAX).
     */
    public function count(Request $request)
    {
        $favorites = $request->session()->get('favorites', []);
        return response()->json(['count' => count($favorites)]);
    }
}
