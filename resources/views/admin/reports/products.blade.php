@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير المنتجات' : 'Products Report' }}
                </h1>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</label>
                    <select name="shop" class="input-field">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ $shopId == $shop->id ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
                <a href="{{ route('admin.reports.products') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي المنتجات' : 'Total Products' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($productsSummary['total']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($productsSummary['active']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($productsSummary['inactive']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</p>
                <p class="mt-2 text-2xl font-bold text-yellow-600">{{ number_format($productsSummary['featured']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'لديها صور' : 'With Images' }}</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($productsSummary['with_images']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'بدون صور' : 'No Images' }}</p>
                <p class="mt-2 text-2xl font-bold text-orange-600">{{ number_format($productsSummary['without_images']) }}</p>
            </div>
        </div>

        {{-- Price Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="admin-card p-5 bg-gradient-to-br from-green-500 to-green-600 text-white">
                <p class="text-green-100 text-sm">{{ app()->getLocale() === 'ar' ? 'متوسط السعر' : 'Average Price' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($productsSummary['average_price'], 2) }}</p>
                <p class="text-green-100 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
            </div>
            <div class="admin-card p-5 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <p class="text-blue-100 text-sm">{{ app()->getLocale() === 'ar' ? 'أقل سعر' : 'Min Price' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($productsSummary['min_price'], 2) }}</p>
                <p class="text-blue-100 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
            </div>
            <div class="admin-card p-5 bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                <p class="text-purple-100 text-sm">{{ app()->getLocale() === 'ar' ? 'أعلى سعر' : 'Max Price' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($productsSummary['max_price'], 2) }}</p>
                <p class="text-purple-100 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Products by Shop --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'المنتجات حسب المحل' : 'Products by Shop' }}</h3>
                <div class="h-72">
                    <canvas id="productsByShopChart"></canvas>
                </div>
            </div>

            {{-- Price Distribution --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'توزيع الأسعار' : 'Price Distribution' }}</h3>
                <div class="h-72">
                    <canvas id="priceDistributionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المنتجات الأكثر مبيعاً' : 'Top Selling Products' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المنتج' : 'Product' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الكمية المباعة' : 'Sold Qty' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topSellingProducts as $index => $product)
                            <tr>
                                <td>
                                    <span class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 text-sm flex items-center justify-center font-semibold">{{ $index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="" class="w-10 h-10 rounded object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded bg-gray-100 dark:bg-secondary-800 dark:bg-secondary-800 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="font-medium">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</span>
                                    </div>
                                </td>
                                <td>{{ app()->getLocale() === 'ar' ? $product->shop->name_ar : $product->shop->name_en }}</td>
                                <td>{{ number_format($product->price, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td class="font-semibold text-green-600">{{ number_format($product->total_sold ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات مبيعات' : 'No sales data' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Products List --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'قائمة المنتجات' : 'Products List' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المنتج' : 'Product' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Created' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="" class="w-10 h-10 rounded object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded bg-gray-100 dark:bg-secondary-800 dark:bg-secondary-800 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="font-medium">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</span>
                                    </div>
                                </td>
                                <td>{{ app()->getLocale() === 'ar' ? $product->shop->name_ar : $product->shop->name_en }}</td>
                                <td>{{ number_format($product->price, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded text-xs">
                                            {{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded text-xs">
                                            {{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_featured)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded text-xs">
                                            {{ app()->getLocale() === 'ar' ? 'مميز' : 'Yes' }}
                                        </span>
                                    @else
                                        <span class="text-secondary-400">-</span>
                                    @endif
                                </td>
                                <td class="text-secondary-500 text-sm">{{ $product->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد منتجات' : 'No products found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            // Products by Shop Chart
            const shopCtx = document.getElementById('productsByShopChart').getContext('2d');
            new Chart(shopCtx, {
                type: 'bar',
                data: {
                    labels: @json($productsByShop->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "المنتجات" : "Products" }}',
                        data: @json($productsByShop->pluck('products_count')),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        },
                        x: {
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });

            // Price Distribution Chart
            const priceCtx = document.getElementById('priceDistributionChart').getContext('2d');
            const priceData = @json($priceDistribution);
            new Chart(priceCtx, {
                type: 'bar',
                data: {
                    labels: [
                        '{{ app()->getLocale() === "ar" ? "أقل من 100" : "Under 100" }}',
                        '100 - 500',
                        '500 - 1000',
                        '1000 - 5000',
                        '{{ app()->getLocale() === "ar" ? "أكثر من 5000" : "Over 5000" }}'
                    ],
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "المنتجات" : "Products" }}',
                        data: [
                            priceData.under_100,
                            priceData['100_500'],
                            priceData['500_1000'],
                            priceData['1000_5000'],
                            priceData.over_5000
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection
