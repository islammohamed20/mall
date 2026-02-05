<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopCategoryRequest;
use App\Http\Requests\Admin\UpdateShopCategoryRequest;
use App\Models\ShopCategory;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function index()
    {
        $categories = ShopCategory::query()->ordered()->paginate(15);

        return view('admin.shop-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.shop-categories.create');
    }

    public function store(StoreShopCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);

        ShopCategory::create($data);

        return redirect()->route('admin.shop-categories.index')->with('status', 'Saved.');
    }

    public function edit(ShopCategory $shopCategory)
    {
        return view('admin.shop-categories.edit', ['category' => $shopCategory]);
    }

    public function update(UpdateShopCategoryRequest $request, ShopCategory $shopCategory)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);

        $shopCategory->update($data);

        return redirect()->route('admin.shop-categories.index')->with('status', 'Saved.');
    }

    public function destroy(ShopCategory $shopCategory)
    {
        $shopCategory->delete();

        return redirect()->route('admin.shop-categories.index')->with('status', 'Deleted.');
    }
}
