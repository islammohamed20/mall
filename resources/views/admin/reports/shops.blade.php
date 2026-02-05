@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير المحلات' : 'Shops Report' }}
                </h1>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'التصنيف' : 'Category' }}</label>
                    <select name="category" class="input-field">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
                <a href="{{ route('admin.reports.shops') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي المحلات' : 'Total Shops' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($shopsSummary['total']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($shopsSummary['active']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($shopsSummary['inactive']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</p>
                <p class="mt-2 text-2xl font-bold text-yellow-600">{{ number_format($shopsSummary['featured']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'لديها منتجات' : 'With Products' }}</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($shopsSummary['with_products']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'بدون منتجات' : 'No Products' }}</p>
                <p class="mt-2 text-2xl font-bold text-orange-600">{{ number_format($shopsSummary['without_products']) }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Shops by Category --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'المحلات حسب التصنيف' : 'Shops by Category' }}</h3>
                <div class="h-72">
                    <canvas id="shopsByCategoryChart"></canvas>
                </div>
            </div>

            {{-- Shops with Most Products --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'المحلات الأكثر منتجات' : 'Shops with Most Products' }}</h3>
                <div class="h-72">
                    <canvas id="shopsProductsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Selling Shops --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المحلات الأكثر مبيعاً' : 'Top Selling Shops' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التصنيف' : 'Category' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'إجمالي المبيعات' : 'Total Sales' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topSellingShops as $index => $shop)
                            <tr>
                                <td>
                                    <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 text-sm flex items-center justify-center font-semibold">{{ $index + 1 }}</span>
                                </td>
                                <td class="font-medium">{{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}</td>
                                <td>
                                    @if($shop->category)
                                        {{ app()->getLocale() === 'ar' ? $shop->category->name_ar : $shop->category->name_en }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="font-semibold text-green-600">{{ number_format($shop->total_sales ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات مبيعات' : 'No sales data' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Shops List --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'قائمة المحلات' : 'Shops List' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التصنيف' : 'Category' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الطابق' : 'Floor' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shops as $shop)
                            <tr>
                                <td>{{ $shop->id }}</td>
                                <td class="font-medium">{{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}</td>
                                <td>
                                    @if($shop->category)
                                        {{ app()->getLocale() === 'ar' ? $shop->category->name_ar : $shop->category->name_en }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($shop->floor)
                                        {{ app()->getLocale() === 'ar' ? $shop->floor->name_ar : $shop->floor->name_en }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded text-sm">
                                        {{ $shop->products_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($shop->is_active)
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
                                    <a href="{{ route('admin.shops.edit', $shop) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد محلات' : 'No shops found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($shops->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-secondary-800">
                    {{ $shops->withQueryString()->links() }}
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

            // Shops by Category Chart
            const categoryCtx = document.getElementById('shopsByCategoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($shopsByCategory->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')),
                    datasets: [{
                        data: @json($shopsByCategory->pluck('shops_count')),
                        backgroundColor: [
                            '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6',
                            '#ef4444', '#6366f1', '#14b8a6', '#f97316', '#84cc16'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: textColor, font: { size: 11 } }
                        }
                    }
                }
            });

            // Shops with Most Products Chart
            const productsCtx = document.getElementById('shopsProductsChart').getContext('2d');
            new Chart(productsCtx, {
                type: 'bar',
                data: {
                    labels: @json($shopsWithMostProducts->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "المنتجات" : "Products" }}',
                        data: @json($shopsWithMostProducts->pluck('products_count')),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
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
        });
    </script>
@endsection
