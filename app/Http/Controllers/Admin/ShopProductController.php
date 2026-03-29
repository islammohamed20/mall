<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{
    public function index(Shop $shop)
    {
        $products = Product::query()
            ->where('shop_id', $shop->id)
            ->ordered()
            ->paginate(15);

        return view('admin.products.index', compact('shop', 'products'));
    }

    public function export(Shop $shop)
    {
        $fileName = 'products_'.$shop->id.'_'.now()->format('Ymd_His').'.csv';

        $callback = function () use ($shop) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id',
                'name_ar',
                'name_en',
                'slug',
                'sku',
                'price',
                'old_price',
                'currency',
                'description_ar',
                'description_en',
                'is_active',
                'sort_order',
            ]);

            Product::query()
                ->where('shop_id', $shop->id)
                ->orderBy('id')
                ->chunk(200, function ($products) use ($handle) {
                    foreach ($products as $product) {
                        fputcsv($handle, [
                            $product->id,
                            $product->name_ar,
                            $product->name_en,
                            $product->slug,
                            $product->sku,
                            $product->price,
                            $product->old_price,
                            $product->currency,
                            $product->description_ar,
                            $product->description_en,
                            $product->is_active ? 1 : 0,
                            $product->sort_order,
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function showImportForm(Shop $shop)
    {
        return view('admin.products.import', compact('shop'));
    }

    public function import(Shop $shop)
    {
        request()->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = request()->file('file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->withErrors(['file' => 'Unable to read file.']);
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);

            return back()->withErrors(['file' => 'File is empty.']);
        }

        $header = array_map('trim', $header);

        $expected = [
            'id',
            'name_ar',
            'name_en',
            'slug',
            'sku',
            'price',
            'old_price',
            'currency',
            'description_ar',
            'description_en',
            'is_active',
            'sort_order',
        ];

        $indices = [];
        foreach ($expected as $column) {
            $indices[$column] = array_search($column, $header, true);
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $data = [];
            foreach ($expected as $column) {
                $index = $indices[$column];
                $data[$column] = $index !== false && array_key_exists($index, $row) ? $row[$index] : null;
            }

            if (! $data['name_ar'] || ! $data['name_en']) {
                continue;
            }

            $payload = [
                'shop_id' => $shop->id,
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'slug' => $data['slug'] ?: Str::slug($data['name_en']),
                'sku' => $data['sku'] ?: null,
                'price' => $data['price'] !== '' ? (float) $data['price'] : null,
                'old_price' => $data['old_price'] !== '' ? (float) $data['old_price'] : null,
                'currency' => $data['currency'] ?: 'EGP',
                'description_ar' => $data['description_ar'] ?: null,
                'description_en' => $data['description_en'] ?: null,
                'is_active' => (bool) $data['is_active'],
                'sort_order' => $data['sort_order'] !== '' ? (int) $data['sort_order'] : 0,
            ];

            if ($data['id']) {
                Product::query()
                    ->where('shop_id', $shop->id)
                    ->where('id', (int) $data['id'])
                    ->update($payload);
            } else {
                Product::create($payload);
            }
        }

        fclose($handle);

        return redirect()->route('admin.shops.products.index', $shop)->with('status', 'Imported.');
    }

    public function create(Shop $shop)
    {
        $shop->load('category.productAttributes');
        $attributes = $shop->category?->productAttributes()->where('is_active', true)->get() ?? collect();

        return view('admin.products.create', compact('shop', 'attributes'));
    }

    public function store(StoreProductRequest $request, Shop $shop)
    {
        $data = $request->validated();
        $data['shop_id'] = $shop->id;
        $data['slug'] = ($data['slug'] ?? '') !== '' ? $data['slug'] : Str::slug($data['name_en']);
        $images = $request->file('images', []);

        if ($request->hasFile('image')) {
            $singlePath = $request->file('image')->store('products', 'public');
            $data['image'] = $singlePath;
            $images = array_merge([$request->file('image')], $images);
        }

        $product = Product::create($data);

        $this->syncAttributes($request, $shop, $product);

        foreach ($images as $index => $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.shops.products.index', $shop)->with('status', 'Saved.');
    }

    public function edit(Shop $shop, Product $product)
    {
        $this->assertBelongsToShop($shop, $product);

        $shop->load('category.productAttributes');
        $attributes = $shop->category?->productAttributes()->where('is_active', true)->get() ?? collect();
        $attributeValues = $product->attributeValues()->get()->keyBy('product_attribute_id');

        return view('admin.products.edit', compact('shop', 'product', 'attributes', 'attributeValues'));
    }

    public function update(UpdateProductRequest $request, Shop $shop, Product $product)
    {
        $this->assertBelongsToShop($shop, $product);

        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? '') !== '' ? $data['slug'] : Str::slug($data['name_en']);
        $newImages = $request->file('images', []);

        if ($request->hasFile('image')) {
            $singlePath = $request->file('image')->store('products', 'public');
            $data['image'] = $singlePath;
            $newImages = array_merge([$request->file('image')], $newImages);
        }

        $product->update($data);

        $this->syncAttributes($request, $shop, $product);

        $order = collect(explode(',', (string) $request->input('images_order')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($order->isNotEmpty()) {
            foreach ($order as $position => $id) {
                ProductImage::where('product_id', $product->id)
                    ->where('id', $id)
                    ->update(['sort_order' => $position]);
            }
        }

        $deleteIds = (array) $request->input('delete_images', []);

        if (! empty($deleteIds)) {
            $imagesToDelete = ProductImage::where('product_id', $product->id)
                ->whereIn('id', $deleteIds)
                ->get();

            foreach ($imagesToDelete as $image) {
                if ($image->path) {
                    Storage::disk('public')->delete($image->path);
                }

                $image->delete();
            }
        }

        if (! empty($newImages)) {
            $maxOrder = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 0;

            foreach ($newImages as $offset => $file) {
                $path = $file->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'sort_order' => $maxOrder + $offset + 1,
                ]);
            }
        }

        return redirect()->route('admin.shops.products.index', $shop)->with('status', 'Saved.');
    }

    public function destroy(Shop $shop, Product $product)
    {
        $this->assertBelongsToShop($shop, $product);

        $product->delete();

        return redirect()->route('admin.shops.products.index', $shop)->with('status', 'Deleted.');
    }

    private function assertBelongsToShop(Shop $shop, Product $product): void
    {
        if ((int) $product->shop_id !== (int) $shop->id) {
            abort(404);
        }
    }

    private function syncAttributes($request, Shop $shop, Product $product): void
    {
        $category = $shop->category;
        if (! $category) {
            return;
        }

        $attributes = $category->productAttributes()->where('is_active', true)->get();
        if ($attributes->isEmpty()) {
            return;
        }

        $inputAttributes = (array) $request->input('attributes', []);

        foreach ($attributes as $attribute) {
            $raw = $inputAttributes[$attribute->id] ?? null;

            if ($attribute->input_type === 'multi_select') {
                $value = is_array($raw) ? implode(',', $raw) : (string) ($raw ?? '');
            } elseif ($attribute->input_type === 'checkbox') {
                $value = $raw ? '1' : null;
            } else {
                $value = $raw !== null && $raw !== '' ? (string) $raw : null;
            }

            $existing = ProductAttributeValue::query()
                ->where('product_id', $product->id)
                ->where('product_attribute_id', $attribute->id)
                ->first();

            if ($value === null || $value === '') {
                if ($existing) {
                    $existing->delete();
                }

                continue;
            }

            if ($existing) {
                $existing->update(['value' => $value]);
            } else {
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'product_attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
