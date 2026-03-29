<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = ShopCategory::query()->active()->ordered()->get();
        $floors = Floor::query()->active()->ordered()->get();

        $shopsQuery = Shop::query()
            ->active()
            ->with(['category', 'floorRelation'])
            ->ordered();

        if ($request->filled('category')) {
            $shopsQuery->where('category_id', (int) $request->input('category'));
        }

        if ($request->filled('floor')) {
            $shopsQuery->where('floor_id', (int) $request->input('floor'));
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $shopsQuery->where(function ($query) use ($q) {
                $query->where('name_ar', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        $shops = $shopsQuery->paginate(12)->withQueryString();

        return view('pages.shops.index', compact('shops', 'categories', 'floors'));
    }

    public function show(Shop $shop)
    {
        $shop->load([
            'category',
            'floorRelation',
            'images',
            'offers',
            'events',
            'products' => fn ($query) => $query->with('images')->active()->ordered(),
            'facebookPosts' => fn ($query) => $query->approved()->orderByDesc('posted_at')->orderByDesc('id'),
        ]);

        return view('pages.shops.show', compact('shop'));
    }

    public function product(Shop $shop, Product $product)
    {
        if ((int) $product->shop_id !== (int) $shop->id || ! $product->is_active) {
            abort(404);
        }

        $product->load('images');

        return view('pages.products.show', compact('shop', 'product'));
    }
}
