<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_product_with_multiple_images(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_admin' => true]);
        $category = ShopCategory::create([
            'name_ar' => 'تصنيف',
            'name_en' => 'Category',
            'slug' => 'category',
        ]);

        $shop = Shop::create([
            'category_id' => $category->id,
            'name_ar' => 'متجر',
            'name_en' => 'Shop',
            'slug' => 'shop',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('admin.shops.products.store', $shop), [
            'name_ar' => 'Test AR',
            'name_en' => 'Test EN',
            'price' => 10,
            'images' => [
                UploadedFile::fake()->image('one.jpg'),
                UploadedFile::fake()->image('two.jpg'),
            ],
        ]);

        $response->assertRedirect();

        $product = Product::first();

        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);
    }
}
