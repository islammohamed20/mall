<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\Floor;
use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::query()
            ->with(['category', 'floorRelation'])
            ->latest()
            ->paginate(15);

        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        $categories = ShopCategory::query()->ordered()->get();
        $floors = Floor::query()->ordered()->get();

        return view('admin.shops.create', compact('categories', 'floors'));
    }

    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);
        $data['facebook_page_access_token'] = $data['facebook_page_access_token'] ?: null;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('shops/covers', 'public');
        }

        Shop::create($data);

        return redirect()->route('admin.shops.index')->with('status', 'Saved.');
    }

    public function edit(Shop $shop)
    {
        $categories = ShopCategory::query()->ordered()->get();
        $floors = Floor::query()->ordered()->get();

        return view('admin.shops.edit', compact('shop', 'categories', 'floors'));
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en']);

        if (! array_key_exists('facebook_page_access_token', $data) || $data['facebook_page_access_token'] === null || $data['facebook_page_access_token'] === '') {
            unset($data['facebook_page_access_token']);
        }

        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }

            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($shop->cover_image) {
                Storage::disk('public')->delete($shop->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('shops/covers', 'public');
        }

        $shop->update($data);

        return redirect()->route('admin.shops.index')->with('status', 'Saved.');
    }

    public function destroy(Shop $shop)
    {
        if ($shop->logo) {
            Storage::disk('public')->delete($shop->logo);
        }

        if ($shop->cover_image) {
            Storage::disk('public')->delete($shop->cover_image);
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')->with('status', 'Deleted.');
    }
}
