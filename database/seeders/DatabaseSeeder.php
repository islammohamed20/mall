<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Facility;
use App\Models\Floor;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@westelbaladmall.com'],
            ['name' => 'Super Admin', 'password' => 'Admin12345', 'is_admin' => true]
        );

        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $floors = collect([
            ['name_ar' => 'البدروم', 'name_en' => 'Basement', 'code' => 'B1', 'sort_order' => -1, 'is_active' => true],
            ['name_ar' => 'الدور الأرضي', 'name_en' => 'Ground Floor', 'code' => 'GF', 'sort_order' => 0, 'is_active' => true],
            ['name_ar' => 'الدور الأول', 'name_en' => 'First Floor', 'code' => '1F', 'sort_order' => 1, 'is_active' => true],
            ['name_ar' => 'الدور الثاني', 'name_en' => 'Second Floor', 'code' => '2F', 'sort_order' => 2, 'is_active' => true],
        ])->map(fn ($data) => Floor::query()->firstOrCreate(['code' => $data['code']], $data));

        $categories = collect([
            ['name_ar' => 'أزياء', 'name_en' => 'Fashion', 'icon' => 'fashion', 'sort_order' => 0, 'is_active' => true],
            ['name_ar' => 'مطاعم', 'name_en' => 'Restaurants', 'icon' => 'food', 'sort_order' => 1, 'is_active' => true],
            ['name_ar' => 'كافيهات', 'name_en' => 'Cafes', 'icon' => 'coffee', 'sort_order' => 2, 'is_active' => true],
            ['name_ar' => 'إلكترونيات', 'name_en' => 'Electronics', 'icon' => 'electronics', 'sort_order' => 3, 'is_active' => true],
            ['name_ar' => 'أطفال', 'name_en' => 'Kids', 'icon' => 'kids', 'sort_order' => 4, 'is_active' => true],
        ])->map(function ($data) {
            $slug = Str::slug($data['name_en']);

            return ShopCategory::query()->firstOrCreate(['slug' => $slug], array_merge($data, ['slug' => $slug]));
        });

        $shopsSeed = [
            [
                'category' => 'Fashion',
                'floor' => 'GF',
                'name_ar' => 'ستايل ستور',
                'name_en' => 'Style Store',
                'unit_number' => 'G-12',
                'is_featured' => true,
                'phone' => '+20 123 000 0001',
            ],
            [
                'category' => 'Restaurants',
                'floor' => '1F',
                'name_ar' => 'مطعم النكهة',
                'name_en' => 'Flavor Restaurant',
                'unit_number' => '1-05',
                'is_featured' => true,
                'phone' => '+20 123 000 0002',
            ],
            [
                'category' => 'Cafes',
                'floor' => '1F',
                'name_ar' => 'كافيه المدينة',
                'name_en' => 'City Cafe',
                'unit_number' => '1-18',
                'is_featured' => false,
                'phone' => '+20 123 000 0003',
            ],
            [
                'category' => 'Electronics',
                'floor' => '2F',
                'name_ar' => 'تك ستور',
                'name_en' => 'Tech Store',
                'unit_number' => '2-02',
                'is_featured' => true,
                'phone' => '+20 123 000 0004',
            ],
        ];

        $shops = collect($shopsSeed)->map(function ($seed) use ($categories, $floors) {
            $category = $categories->firstWhere('name_en', $seed['category']);
            $floor = $floors->firstWhere('code', $seed['floor']);
            $slug = Str::slug($seed['name_en']);

            return Shop::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'floor_id' => $floor?->id,
                    'name_ar' => $seed['name_ar'],
                    'name_en' => $seed['name_en'],
                    'slug' => $slug,
                    'unit_number' => $seed['unit_number'] ?? null,
                    'phone' => $seed['phone'] ?? null,
                    'opening_hours_ar' => 'يومياً 10 ص - 12 م',
                    'opening_hours_en' => 'Daily 10 AM - 12 AM',
                    'is_featured' => (bool) ($seed['is_featured'] ?? false),
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        });

        $productShop = $shops->first();
        if ($productShop) {
            Product::query()->firstOrCreate(
                ['shop_id' => $productShop->id, 'slug' => 'signature-item'],
                [
                    'shop_id' => $productShop->id,
                    'name_ar' => 'منتج مميز',
                    'name_en' => 'Signature Item',
                    'slug' => 'signature-item',
                    'description_ar' => 'وصف مختصر للمنتج داخل المحل.',
                    'description_en' => 'A short description for the shop product.',
                    'sku' => 'SKU-001',
                    'price' => 999.00,
                    'old_price' => 1299.00,
                    'currency' => 'EGP',
                    'image' => null,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        }

        $today = Carbon::today();
        $offerShop = $shops->first();
        Offer::query()->firstOrCreate(
            ['slug' => 'winter-sale'],
            [
                'shop_id' => $offerShop?->id,
                'title_ar' => 'تخفيضات الشتاء',
                'title_en' => 'Winter Sale',
                'slug' => 'winter-sale',
                'short_ar' => 'خصومات تصل إلى 50% على مجموعة مختارة.',
                'short_en' => 'Up to 50% off selected items.',
                'content_ar' => 'استمتع بتخفيضات قوية لفترة محدودة.',
                'content_en' => 'Enjoy strong discounts for a limited time.',
                'start_date' => $today->copy()->subDays(2),
                'end_date' => $today->copy()->addDays(10),
                'discount_text_ar' => 'حتى 50%',
                'discount_text_en' => 'Up to 50%',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 0,
            ]
        );

        Event::query()->firstOrCreate(
            ['slug' => 'kids-day'],
            [
                'shop_id' => null,
                'title_ar' => 'يوم الأطفال',
                'title_en' => 'Kids Day',
                'slug' => 'kids-day',
                'short_ar' => 'أنشطة وألعاب مجانية للأطفال.',
                'short_en' => 'Free activities and games for kids.',
                'content_ar' => 'انضموا إلينا لفعاليات مميزة للأطفال طوال اليوم.',
                'content_en' => 'Join us for special kids activities all day.',
                'start_date' => $today->copy()->addDays(5),
                'end_date' => $today->copy()->addDays(5),
                'start_time' => '16:00',
                'end_time' => '20:00',
                'location_ar' => 'منطقة الألعاب',
                'location_en' => 'Kids Area',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 0,
            ]
        );

        $facilities = [
            ['name_ar' => 'واي فاي', 'name_en' => 'Wi‑Fi', 'short_ar' => 'خدمة مجانية داخل المول', 'short_en' => 'Free in-mall Wi‑Fi'],
            ['name_ar' => 'مواقف سيارات', 'name_en' => 'Parking', 'short_ar' => 'مواقف واسعة وآمنة', 'short_en' => 'Spacious and safe parking'],
            ['name_ar' => 'ATM', 'name_en' => 'ATM', 'short_ar' => 'ماكينات صراف آلي', 'short_en' => 'ATMs available'],
            ['name_ar' => 'منطقة أطفال', 'name_en' => 'Kids Area', 'short_ar' => 'ألعاب وأنشطة', 'short_en' => 'Games and activities'],
        ];

        foreach ($facilities as $i => $facility) {
            Facility::query()->firstOrCreate(
                ['name_en' => $facility['name_en']],
                array_merge($facility, ['sort_order' => $i, 'is_active' => true, 'icon' => null])
            );
        }

        Slider::query()->firstOrCreate(
            ['id' => 1],
            [
                'title_ar' => 'وجهتك الأولى للتسوق والترفيه في قلب المدينة',
                'title_en' => 'Your first destination for shopping & entertainment',
                'subtitle_ar' => 'اكتشف أحدث العروض وأفضل العلامات والخدمات المتكاملة.',
                'subtitle_en' => 'Explore the latest offers, top brands, and complete services.',
                'cta_text_ar' => 'استكشف المحلات',
                'cta_text_en' => 'Explore Shops',
                'cta_link' => '/shops',
                'cta_text_2_ar' => 'عروض اليوم',
                'cta_text_2_en' => "Today's Offers",
                'cta_link_2' => '/offers',
                'image' => 'sliders/hero.jpg',
                'image_mobile' => null,
                'sort_order' => 0,
                'is_active' => true,
                'start_date' => null,
                'end_date' => null,
            ]
        );

        Setting::query()->firstOrCreate(
            ['key' => 'mall_name'],
            ['group' => 'general', 'value_ar' => config('mall.name.ar'), 'value_en' => config('mall.name.en'), 'type' => 'text']
        );

        Setting::query()->firstOrCreate(
            ['key' => 'mall_slogan'],
            ['group' => 'site', 'value_ar' => config('mall.slogan.ar'), 'value_en' => config('mall.slogan.en'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_contact_phone'],
            ['group' => 'site', 'value_ar' => config('mall.contact.phone'), 'value_en' => config('mall.contact.phone'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_contact_whatsapp'],
            ['group' => 'site', 'value_ar' => config('mall.contact.whatsapp'), 'value_en' => config('mall.contact.whatsapp'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_contact_email'],
            ['group' => 'site', 'value_ar' => config('mall.contact.email'), 'value_en' => config('mall.contact.email'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_contact_address'],
            ['group' => 'site', 'value_ar' => config('mall.contact.address_ar'), 'value_en' => config('mall.contact.address_en'), 'type' => 'textarea']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_working_hours'],
            ['group' => 'site', 'value_ar' => config('mall.working_hours.ar'), 'value_en' => config('mall.working_hours.en'), 'type' => 'textarea']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_map_embed_url'],
            ['group' => 'site', 'value_ar' => config('mall.map.embed_url'), 'value_en' => config('mall.map.embed_url'), 'type' => 'textarea']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_social_facebook'],
            ['group' => 'site', 'value_ar' => config('mall.social.facebook'), 'value_en' => config('mall.social.facebook'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_social_instagram'],
            ['group' => 'site', 'value_ar' => config('mall.social.instagram'), 'value_en' => config('mall.social.instagram'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_social_twitter'],
            ['group' => 'site', 'value_ar' => config('mall.social.twitter'), 'value_en' => config('mall.social.twitter'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_social_tiktok'],
            ['group' => 'site', 'value_ar' => config('mall.social.tiktok'), 'value_en' => config('mall.social.tiktok'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_social_snapchat'],
            ['group' => 'site', 'value_ar' => config('mall.social.snapchat'), 'value_en' => config('mall.social.snapchat'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_stats_shops'],
            ['group' => 'site', 'value_ar' => (string) config('mall.stats.shops'), 'value_en' => (string) config('mall.stats.shops'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_stats_restaurants'],
            ['group' => 'site', 'value_ar' => (string) config('mall.stats.restaurants'), 'value_en' => (string) config('mall.stats.restaurants'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_stats_parking_spots'],
            ['group' => 'site', 'value_ar' => (string) config('mall.stats.parking_spots'), 'value_en' => (string) config('mall.stats.parking_spots'), 'type' => 'text']
        );
        Setting::query()->firstOrCreate(
            ['key' => 'mall_stats_monthly_visitors'],
            ['group' => 'site', 'value_ar' => (string) config('mall.stats.monthly_visitors'), 'value_en' => (string) config('mall.stats.monthly_visitors'), 'type' => 'text']
        );
    }
}
