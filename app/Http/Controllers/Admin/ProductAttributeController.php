<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductAttributeRequest;
use App\Http\Requests\Admin\UpdateProductAttributeRequest;
use App\Models\ProductAttribute;
use App\Models\ShopCategory;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::query()
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->paginate(15);

        return view('admin.product-attributes.index', compact('attributes'));
    }

    public function create()
    {
        $categories = ShopCategory::query()->ordered()->get();

        return view('admin.product-attributes.create', compact('categories'));
    }

    public function store(StoreProductAttributeRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);

        $attribute = ProductAttribute::create($data);

        $categoryIds = (array) ($data['category_ids'] ?? []);
        $pivotData = [];
        foreach ($categoryIds as $categoryId) {
            $pivotData[$categoryId] = [
                'is_required' => (bool) ($data['is_required'] ?? false),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ];
        }

        if (! empty($pivotData)) {
            $attribute->categories()->sync($pivotData);
        }

        return redirect()->route('admin.product-attributes.index')->with('status', 'Saved.');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        $categories = ShopCategory::query()->ordered()->get();
        $selectedCategories = $productAttribute->categories()->pluck('shop_category_id')->all();

        return view('admin.product-attributes.edit', [
            'attribute' => $productAttribute,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }

    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $data = $request->validated();

        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);

        $productAttribute->update($data);

        $categoryIds = (array) ($data['category_ids'] ?? []);
        $pivotData = [];
        foreach ($categoryIds as $categoryId) {
            $pivotData[$categoryId] = [
                'is_required' => (bool) ($data['is_required'] ?? false),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ];
        }

        $productAttribute->categories()->sync($pivotData);

        return redirect()->route('admin.product-attributes.index')->with('status', 'Saved.');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();

        return redirect()->route('admin.product-attributes.index')->with('status', 'Deleted.');
    }
}

