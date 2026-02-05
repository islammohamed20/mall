<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckoutOrder;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Main Dashboard with comprehensive analytics
     */
    public function dashboard()
    {
        // Basic Stats
        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('is_active', true)->count(),
            'total_products' => Product::count(),
            'total_orders' => CheckoutOrder::count(),
            'total_revenue' => CheckoutOrder::where('status', '!=', 'cancelled')->sum('total'),
            'total_customers' => User::where('role', 'user')->count(),
            'pending_orders' => CheckoutOrder::where('status', 'pending')->count(),
            'new_messages' => ContactMessage::where('status', 'new')->count(),
            'active_offers' => Offer::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })->count(),
            'upcoming_events' => Event::where('is_active', true)
                ->where('start_date', '>=', now())->count(),
        ];

        // Sales data for last 30 days
        $salesData = $this->getSalesChartData(30);

        // Orders by status
        $ordersByStatus = CheckoutOrder::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Shops by category
        $shopsByCategory = ShopCategory::withCount('shops')
            ->orderByDesc('shops_count')
            ->take(10)
            ->get();

        // Top selling shops
        $topShops = $this->getTopSellingShops(5);

        // Top selling products
        $topProducts = $this->getTopSellingProducts(5);

        // Recent orders
        $recentOrders = CheckoutOrder::with(['user', 'paymentMethod'])
            ->latest()
            ->take(10)
            ->get();

        // Payment methods distribution
        $paymentMethodStats = CheckoutOrder::select('payment_type', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
            ->groupBy('payment_type')
            ->get();

        return view('admin.reports.dashboard', compact(
            'stats',
            'salesData',
            'ordersByStatus',
            'shopsByCategory',
            'topShops',
            'topProducts',
            'recentOrders',
            'paymentMethodStats'
        ));
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $end = now();
            $start = match($period) {
                '7' => now()->subDays(7),
                '30' => now()->subDays(30),
                '90' => now()->subDays(90),
                '365' => now()->subYear(),
                default => now()->subDays(30),
            };
        }

        // Sales summary
        $salesSummary = [
            'total_orders' => CheckoutOrder::whereBetween('created_at', [$start, $end])->count(),
            'completed_orders' => CheckoutOrder::whereBetween('created_at', [$start, $end])
                ->where('status', 'completed')->count(),
            'cancelled_orders' => CheckoutOrder::whereBetween('created_at', [$start, $end])
                ->where('status', 'cancelled')->count(),
            'total_revenue' => CheckoutOrder::whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled')->sum('total'),
            'average_order_value' => CheckoutOrder::whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled')->avg('total') ?? 0,
        ];

        // Daily sales
        $dailySales = CheckoutOrder::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as orders'),
                DB::raw('sum(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sales by payment type
        $salesByPaymentType = CheckoutOrder::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->select('payment_type', DB::raw('count(*) as orders'), DB::raw('sum(total) as revenue'))
            ->groupBy('payment_type')
            ->get();

        // Sales by shop
        $salesByShop = $this->getSalesByShop($start, $end);

        // Orders list
        $orders = CheckoutOrder::with(['user', 'paymentMethod'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->paginate(20);

        return view('admin.reports.sales', compact(
            'period',
            'startDate',
            'endDate',
            'salesSummary',
            'dailySales',
            'salesByPaymentType',
            'salesByShop',
            'orders'
        ));
    }

    /**
     * Shops Report
     */
    public function shops(Request $request)
    {
        $categoryId = $request->get('category');
        $floorId = $request->get('floor');

        // Shops summary
        $shopsSummary = [
            'total' => Shop::count(),
            'active' => Shop::where('is_active', true)->count(),
            'inactive' => Shop::where('is_active', false)->count(),
            'featured' => Shop::where('is_featured', true)->count(),
            'with_products' => Shop::has('products')->count(),
            'without_products' => Shop::doesntHave('products')->count(),
        ];

        // Shops by category
        $shopsByCategory = ShopCategory::withCount('shops')
            ->orderByDesc('shops_count')
            ->get();

        // Shops by floor
        $shopsByFloor = Shop::select('floor_id', DB::raw('count(*) as count'))
            ->whereNotNull('floor_id')
            ->groupBy('floor_id')
            ->with('floor')
            ->get();

        // Top selling shops
        $topSellingShops = $this->getTopSellingShops(10);

        // Shops with most products
        $shopsWithMostProducts = Shop::withCount('products')
            ->orderByDesc('products_count')
            ->take(10)
            ->get();

        // Shops list with filters
        $shopsQuery = Shop::with(['category', 'floor'])
            ->withCount('products');

        if ($categoryId) {
            $shopsQuery->where('shop_category_id', $categoryId);
        }

        if ($floorId) {
            $shopsQuery->where('floor_id', $floorId);
        }

        $shops = $shopsQuery->orderByDesc('products_count')->paginate(20);

        $categories = ShopCategory::orderBy('name_ar')->get();

        return view('admin.reports.shops', compact(
            'shopsSummary',
            'shopsByCategory',
            'shopsByFloor',
            'topSellingShops',
            'shopsWithMostProducts',
            'shops',
            'categories',
            'categoryId',
            'floorId'
        ));
    }

    /**
     * Products Report
     */
    public function products(Request $request)
    {
        $shopId = $request->get('shop');

        // Products summary
        $productsSummary = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'with_images' => Product::has('images')->count(),
            'without_images' => Product::doesntHave('images')->count(),
            'average_price' => Product::avg('price') ?? 0,
            'min_price' => Product::min('price') ?? 0,
            'max_price' => Product::max('price') ?? 0,
        ];

        // Products by shop
        $productsByShop = Shop::withCount('products')
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->take(10)
            ->get();

        // Top selling products
        $topSellingProducts = $this->getTopSellingProducts(10);

        // Price distribution
        $priceDistribution = [
            'under_100' => Product::where('price', '<', 100)->count(),
            '100_500' => Product::whereBetween('price', [100, 500])->count(),
            '500_1000' => Product::whereBetween('price', [500, 1000])->count(),
            '1000_5000' => Product::whereBetween('price', [1000, 5000])->count(),
            'over_5000' => Product::where('price', '>', 5000)->count(),
        ];

        // Products list with filters
        $productsQuery = Product::with(['shop', 'images']);

        if ($shopId) {
            $productsQuery->where('shop_id', $shopId);
        }

        $products = $productsQuery->latest()->paginate(20);

        $shops = Shop::orderBy('name_ar')->get();

        return view('admin.reports.products', compact(
            'productsSummary',
            'productsByShop',
            'topSellingProducts',
            'priceDistribution',
            'products',
            'shops',
            'shopId'
        ));
    }

    /**
     * Customers Report
     */
    public function customers(Request $request)
    {
        $period = $request->get('period', '30');

        $end = now();
        $start = match($period) {
            '7' => now()->subDays(7),
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            '365' => now()->subYear(),
            default => now()->subDays(30),
        };

        // Customers summary
        $customersSummary = [
            'total' => User::where('role', 'user')->count(),
            'new_this_period' => User::where('role', 'user')
                ->whereBetween('created_at', [$start, $end])->count(),
            'with_orders' => User::where('role', 'user')->has('orders')->count(),
            'without_orders' => User::where('role', 'user')->doesntHave('orders')->count(),
        ];

        // New customers over time
        $newCustomersData = User::where('role', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers by orders
        $topCustomers = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->orderByDesc('orders_sum_total')
            ->take(10)
            ->get();

        // Customers by city
        $customersByCity = CheckoutOrder::whereNotNull('shipping_city')
            ->select('shipping_city', DB::raw('count(distinct email) as count'))
            ->groupBy('shipping_city')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Customers list
        $customers = User::where('role', 'user')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->latest()
            ->paginate(20);

        return view('admin.reports.customers', compact(
            'period',
            'customersSummary',
            'newCustomersData',
            'topCustomers',
            'customersByCity',
            'customers'
        ));
    }

    /**
     * Orders Report
     */
    public function orders(Request $request)
    {
        $status = $request->get('status');
        $paymentType = $request->get('payment_type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Orders summary
        $ordersSummary = [
            'total' => CheckoutOrder::count(),
            'pending' => CheckoutOrder::where('status', 'pending')->count(),
            'processing' => CheckoutOrder::where('status', 'processing')->count(),
            'completed' => CheckoutOrder::where('status', 'completed')->count(),
            'cancelled' => CheckoutOrder::where('status', 'cancelled')->count(),
        ];

        // Orders by status (for chart)
        $ordersByStatus = CheckoutOrder::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Orders by payment type
        $ordersByPaymentType = CheckoutOrder::select('payment_type', DB::raw('count(*) as count'))
            ->groupBy('payment_type')
            ->get();

        // Build orders query
        $ordersQuery = CheckoutOrder::with(['user', 'paymentMethod']);

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        if ($paymentType) {
            $ordersQuery->where('payment_type', $paymentType);
        }

        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $orders = $ordersQuery->latest()->paginate(20);

        return view('admin.reports.orders', compact(
            'ordersSummary',
            'ordersByStatus',
            'ordersByPaymentType',
            'orders',
            'status',
            'paymentType',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Offers & Events Report
     */
    public function offersEvents()
    {
        // Offers summary
        $offersSummary = [
            'total' => Offer::count(),
            'active' => Offer::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })->count(),
            'expired' => Offer::where('end_date', '<', now())->count(),
            'by_shop' => Offer::whereNotNull('shop_id')->count(),
            'general' => Offer::whereNull('shop_id')->count(),
        ];

        // Events summary
        $eventsSummary = [
            'total' => Event::count(),
            'active' => Event::where('is_active', true)->count(),
            'upcoming' => Event::where('start_date', '>=', now())->count(),
            'ongoing' => Event::where('start_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })->count(),
            'past' => Event::where('end_date', '<', now())->count(),
        ];

        // Active offers
        $activeOffers = Offer::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->with('shop')
            ->orderBy('start_date')
            ->get();

        // Upcoming events
        $upcomingEvents = Event::where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        // Offers by shop
        $offersByShop = Shop::withCount(['offers' => function ($q) {
            $q->where('is_active', true);
        }])
        ->having('offers_count', '>', 0)
        ->orderByDesc('offers_count')
        ->take(10)
        ->get();

        return view('admin.reports.offers-events', compact(
            'offersSummary',
            'eventsSummary',
            'activeOffers',
            'upcomingEvents',
            'offersByShop'
        ));
    }

    /**
     * Messages Report
     */
    public function messages(Request $request)
    {
        $status = $request->get('status');

        // Messages summary
        $messagesSummary = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
        ];

        // Messages over time (last 30 days)
        $messagesOverTime = ContactMessage::whereBetween('created_at', [now()->subDays(30), now()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Messages by status (for chart)
        $messagesByStatus = ContactMessage::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Build messages query
        $messagesQuery = ContactMessage::query();

        if ($status) {
            $messagesQuery->where('status', $status);
        }

        $messages = $messagesQuery->latest()->paginate(20);

        return view('admin.reports.messages', compact(
            'messagesSummary',
            'messagesOverTime',
            'messagesByStatus',
            'messages',
            'status'
        ));
    }

    /**
     * Export report data
     */
    public function export(Request $request, string $type)
    {
        // TODO: Implement export functionality (CSV/Excel)
        return back()->with('info', app()->getLocale() === 'ar' 
            ? 'سيتم إضافة ميزة التصدير قريباً' 
            : 'Export feature coming soon');
    }

    // ============ Helper Methods ============

    private function getSalesChartData(int $days): array
    {
        $dates = [];
        $revenues = [];
        $orders = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M d');

            $dayData = CheckoutOrder::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('count(*) as orders, sum(total) as revenue')
                ->first();

            $revenues[] = $dayData->revenue ?? 0;
            $orders[] = $dayData->orders ?? 0;
        }

        return [
            'labels' => $dates,
            'revenues' => $revenues,
            'orders' => $orders,
        ];
    }

    private function getTopSellingShops(int $limit): \Illuminate\Support\Collection
    {
        // Get all non-cancelled orders with their cart snapshots
        $orders = CheckoutOrder::where('status', '!=', 'cancelled')
            ->whereNotNull('cart_snapshot')
            ->get(['cart_snapshot']);

        // Calculate sales per shop
        $shopSales = [];
        foreach ($orders as $order) {
            $items = is_array($order->cart_snapshot) ? $order->cart_snapshot : json_decode($order->cart_snapshot, true);
            if (!is_array($items)) continue;
            
            foreach ($items as $item) {
                $shopId = $item['shop_id'] ?? null;
                $total = $item['total'] ?? 0;
                if ($shopId) {
                    $shopSales[$shopId] = ($shopSales[$shopId] ?? 0) + $total;
                }
            }
        }

        // Sort by sales and get top shop IDs
        arsort($shopSales);
        $topShopIds = array_slice(array_keys($shopSales), 0, $limit);

        if (empty($topShopIds)) {
            return collect();
        }

        // Get shops with their sales
        $shops = Shop::whereIn('id', $topShopIds)->get();
        
        return $shops->map(function ($shop) use ($shopSales) {
            $shop->total_sales = $shopSales[$shop->id] ?? 0;
            return $shop;
        })->sortByDesc('total_sales')->values();
    }

    private function getTopSellingProducts(int $limit): \Illuminate\Support\Collection
    {
        // Get all non-cancelled orders with their cart snapshots
        $orders = CheckoutOrder::where('status', '!=', 'cancelled')
            ->whereNotNull('cart_snapshot')
            ->get(['cart_snapshot']);

        // Calculate quantity sold per product
        $productQuantities = [];
        foreach ($orders as $order) {
            $items = is_array($order->cart_snapshot) ? $order->cart_snapshot : json_decode($order->cart_snapshot, true);
            if (!is_array($items)) continue;
            
            foreach ($items as $item) {
                $productId = $item['product_id'] ?? null;
                $quantity = $item['quantity'] ?? 0;
                if ($productId) {
                    $productQuantities[$productId] = ($productQuantities[$productId] ?? 0) + $quantity;
                }
            }
        }

        // Sort by quantity and get top product IDs
        arsort($productQuantities);
        $topProductIds = array_slice(array_keys($productQuantities), 0, $limit);

        if (empty($topProductIds)) {
            return collect();
        }

        // Get products with their quantities
        $products = Product::with(['shop', 'images'])
            ->whereIn('id', $topProductIds)
            ->get();
        
        return $products->map(function ($product) use ($productQuantities) {
            $product->total_sold = $productQuantities[$product->id] ?? 0;
            return $product;
        })->sortByDesc('total_sold')->values();
    }

    private function getSalesByShop($start, $end): \Illuminate\Support\Collection
    {
        // Get orders in date range
        $orders = CheckoutOrder::where('status', '!=', 'cancelled')
            ->whereNotNull('cart_snapshot')
            ->whereBetween('created_at', [$start, $end])
            ->get(['cart_snapshot']);

        // Calculate sales per shop
        $shopSales = [];
        foreach ($orders as $order) {
            $items = is_array($order->cart_snapshot) ? $order->cart_snapshot : json_decode($order->cart_snapshot, true);
            if (!is_array($items)) continue;
            
            foreach ($items as $item) {
                $shopId = $item['shop_id'] ?? null;
                $total = $item['total'] ?? 0;
                if ($shopId) {
                    $shopSales[$shopId] = ($shopSales[$shopId] ?? 0) + $total;
                }
            }
        }

        // Sort by sales and get top shop IDs
        arsort($shopSales);
        $topShopIds = array_slice(array_keys($shopSales), 0, 10);

        if (empty($topShopIds)) {
            return collect();
        }

        // Get shops with their sales
        $shops = Shop::whereIn('id', $topShopIds)->get();
        
        return $shops->map(function ($shop) use ($shopSales) {
            $shop->period_sales = $shopSales[$shop->id] ?? 0;
            return $shop;
        })->sortByDesc('period_sales')->values();
    }
}
