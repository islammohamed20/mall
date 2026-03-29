<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $shops = Shop::with(['category', 'floorRelation'])
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%");
                });
            })
            ->when($request->is_active, function ($query, $isActive) {
                return $query->where('is_active', $isActive === 'true');
            })
            ->when($request->is_featured, function ($query, $isFeatured) {
                return $query->where('is_featured', $isFeatured === 'true');
            })
            ->orderBy('sort_order', 'asc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => $shops->items(),
            'current_page' => $shops->currentPage(),
            'last_page' => $shops->lastPage(),
            'per_page' => $shops->perPage(),
            'total' => $shops->total(),
        ]);
    }

    public function show($id)
    {
        $shop = Shop::with(['category', 'floorRelation'])->findOrFail($id);
        
        return response()->json([
            'data' => $shop,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:shop_categories,id',
            'floor_id' => 'nullable|exists:floors,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shops,slug',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $shopData = $validated;
        
        // Handle file uploads
        if ($request->hasFile('logo')) {
            $shopData['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        
        if ($request->hasFile('cover_image')) {
            $shopData['cover_image'] = $request->file('cover_image')->store('shops/covers', 'public');
        }

        $shop = Shop::create($shopData);

        return response()->json([
            'data' => $shop->load(['category', 'floorRelation']),
            'message' => 'Shop created successfully',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:shop_categories,id',
            'floor_id' => 'sometimes|nullable|exists:floors,id',
            'name_ar' => 'sometimes|required|string|max:255',
            'name_en' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:shops,slug,' . $id,
            'description_ar' => 'sometimes|nullable|string',
            'description_en' => 'sometimes|nullable|string',
            'phone' => 'sometimes|nullable|string|max:50',
            'whatsapp' => 'sometimes|nullable|string|max:50',
            'email' => 'sometimes|nullable|email|max:255',
            'website' => 'sometimes|nullable|url|max:255',
            'logo' => 'sometimes|nullable|image|max:2048',
            'cover_image' => 'sometimes|nullable|image|max:4096',
            'is_active' => 'sometimes|nullable|boolean',
            'is_featured' => 'sometimes|nullable|boolean',
            'sort_order' => 'sometimes|nullable|integer|min:0',
        ]);

        $shopData = $validated;
        
        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $shopData['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($shop->cover_image) {
                Storage::disk('public')->delete($shop->cover_image);
            }
            $shopData['cover_image'] = $request->file('cover_image')->store('shops/covers', 'public');
        }

        $shop->update($shopData);

        return response()->json([
            'data' => $shop->load(['category', 'floorRelation']),
            'message' => 'Shop updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        // Delete associated files
        if ($shop->logo) {
            Storage::disk('public')->delete($shop->logo);
        }
        
        if ($shop->cover_image) {
            Storage::disk('public')->delete($shop->cover_image);
        }

        $shop->delete();

        return response()->json([
            'message' => 'Shop deleted successfully',
        ]);
    }

    public function categories()
    {
        $categories = ShopCategory::orderBy('sort_order', 'asc')->get();
        
        return response()->json([
            'data' => $categories,
        ]);
    }

    public function floors()
    {
        $floors = \App\Models\Floor::orderBy('sort_order', 'asc')->get();
        
        return response()->json([
            'data' => $floors,
        ]);
    }
}
