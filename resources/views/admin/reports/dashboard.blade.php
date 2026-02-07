@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'لوحة التقارير والتحليلات' : 'Reports & Analytics Dashboard' }}
                </h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar' ? 'نظرة شاملة على أداء المول وإحصائيات المبيعات' : 'Comprehensive overview of mall performance and sales statistics' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.sales') }}" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'تقرير المبيعات' : 'Sales Report' }}
                </a>
            </div>
        </div>

        {{-- Quick Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {{-- Total Revenue --}}
            <div class="admin-card p-5 bg-gradient-to-br from-green-500 to-green-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الإيرادات' : 'Total Revenue' }}</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_revenue'], 2) }}</p>
                        <p class="text-green-100 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white dark:bg-secondary-950/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Orders --}}
            <div class="admin-card p-5 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_orders']) }}</p>
                        <p class="text-blue-100 text-xs">{{ $stats['pending_orders'] }} {{ app()->getLocale() === 'ar' ? 'قيد الانتظار' : 'pending' }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white dark:bg-secondary-950/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Shops --}}
            <div class="admin-card p-5 bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_shops']) }}</p>
                        <p class="text-purple-100 text-xs">{{ $stats['active_shops'] }} {{ app()->getLocale() === 'ar' ? 'نشط' : 'active' }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white dark:bg-secondary-950/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Products --}}
            <div class="admin-card p-5 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_products']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white dark:bg-secondary-950/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Customers --}}
            <div class="admin-card p-5 bg-gradient-to-br from-pink-500 to-pink-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm">{{ app()->getLocale() === 'ar' ? 'العملاء' : 'Customers' }}</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($stats['total_customers']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white dark:bg-secondary-950/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Secondary Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="admin-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'العروض النشطة' : 'Active Offers' }}</p>
                        <p class="text-xl font-bold">{{ $stats['active_offers'] }}</p>
                    </div>
                </div>
            </div>
            <div class="admin-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'فعاليات قادمة' : 'Upcoming Events' }}</p>
                        <p class="text-xl font-bold">{{ $stats['upcoming_events'] }}</p>
                    </div>
                </div>
            </div>
            <div class="admin-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'طلبات معلقة' : 'Pending Orders' }}</p>
                        <p class="text-xl font-bold">{{ $stats['pending_orders'] }}</p>
                    </div>
                </div>
            </div>
            <div class="admin-card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'رسائل جديدة' : 'New Messages' }}</p>
                        <p class="text-xl font-bold">{{ $stats['new_messages'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Reports Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.reports.visits') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير الزيارات' : 'Visits Report' }}</p>
                <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'حركة الموقع' : 'Traffic' }}</p>
            </a>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sales Chart --}}
            <div class="admin-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المبيعات (آخر 30 يوم)' : 'Sales (Last 30 Days)' }}</h3>
                    <a href="{{ route('admin.reports.sales') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض التفاصيل' : 'View Details' }} →
                    </a>
                </div>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Orders by Status --}}
            <div class="admin-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'الطلبات حسب الحالة' : 'Orders by Status' }}</h3>
                    <a href="{{ route('admin.reports.orders') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض التفاصيل' : 'View Details' }} →
                    </a>
                </div>
                <div class="h-64">
                    <canvas id="ordersStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Shops & Products Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Shops by Category --}}
            <div class="admin-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المحلات حسب التصنيف' : 'Shops by Category' }}</h3>
                    <a href="{{ route('admin.reports.shops') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض التفاصيل' : 'View Details' }} →
                    </a>
                </div>
                <div class="h-64">
                    <canvas id="shopsCategoryChart"></canvas>
                </div>
            </div>

            {{-- Payment Methods Distribution --}}
            <div class="admin-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'طرق الدفع' : 'Payment Methods' }}</h3>
                </div>
                <div class="h-64">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Tables Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Selling Shops --}}
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800 flex items-center justify-between">
                    <h3 class="font-semibold">{{ app()->getLocale() === 'ar' ? 'المحلات الأكثر مبيعاً' : 'Top Selling Shops' }}</h3>
                    <a href="{{ route('admin.reports.shops') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @forelse ($topShops as $index => $shop)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 text-sm flex items-center justify-center font-semibold">{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-medium">{{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}</p>
                                    @if($shop->category)
                                        <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? $shop->category->name_ar : $shop->category->name_en }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="font-semibold text-green-600">{{ number_format($shop->total_sales ?? 0, 2) }}</p>
                                <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-secondary-500">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات مبيعات بعد' : 'No sales data yet' }}
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Selling Products --}}
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800 flex items-center justify-between">
                    <h3 class="font-semibold">{{ app()->getLocale() === 'ar' ? 'المنتجات الأكثر مبيعاً' : 'Top Selling Products' }}</h3>
                    <a href="{{ route('admin.reports.products') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @forelse ($topProducts as $index => $product)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 text-sm flex items-center justify-center font-semibold">{{ $index + 1 }}</span>
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-100 dark:bg-secondary-800 dark:bg-secondary-800 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</p>
                                    <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? $product->shop->name_ar : $product->shop->name_en }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="font-semibold">{{ number_format($product->total_sold ?? 0) }}</p>
                                <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'مبيعات' : 'sold' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-secondary-500">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات مبيعات بعد' : 'No sales data yet' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800 flex items-center justify-between">
                <h3 class="font-semibold">{{ app()->getLocale() === 'ar' ? 'آخر الطلبات' : 'Recent Orders' }}</h3>
                <a href="{{ route('admin.reports.orders') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'العميل' : 'Customer' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المبلغ' : 'Amount' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="font-medium">#{{ $order->id }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->email }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs {{ $order->payment_type === 'cod' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                        {{ $order->payment_type === 'cod' ? (app()->getLocale() === 'ar' ? 'دفع عند الاستلام' : 'COD') : (app()->getLocale() === 'ar' ? 'بطاقة' : 'Card') }}
                                    </span>
                                </td>
                                <td class="font-semibold">{{ number_format($order->total, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                        $statusLabels = [
                                            'pending' => app()->getLocale() === 'ar' ? 'معلق' : 'Pending',
                                            'processing' => app()->getLocale() === 'ar' ? 'قيد التنفيذ' : 'Processing',
                                            'completed' => app()->getLocale() === 'ar' ? 'مكتمل' : 'Completed',
                                            'cancelled' => app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded text-xs {{ $statusColors[$order->status] ?? 'bg-gray-100 dark:bg-secondary-800 text-gray-800 dark:text-gray-200' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="text-secondary-500 text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات بعد' : 'No orders yet' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.reports.sales') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير المبيعات' : 'Sales Report' }}</p>
            </a>
            <a href="{{ route('admin.reports.orders') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير الطلبات' : 'Orders Report' }}</p>
            </a>
            <a href="{{ route('admin.reports.shops') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير المحلات' : 'Shops Report' }}</p>
            </a>
            <a href="{{ route('admin.reports.products') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير المنتجات' : 'Products Report' }}</p>
            </a>
            <a href="{{ route('admin.reports.customers') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'تقرير العملاء' : 'Customers Report' }}</p>
            </a>
            <a href="{{ route('admin.reports.offers-events') }}" class="admin-card p-4 hover:shadow-md transition-shadow text-center group">
                <div class="w-12 h-12 mx-auto bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
                <p class="mt-2 font-medium text-sm">{{ app()->getLocale() === 'ar' ? 'العروض والفعاليات' : 'Offers & Events' }}</p>
            </a>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($salesData['labels']),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "الإيرادات" : "Revenue" }}',
                        data: @json($salesData['revenues']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
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

            // Orders Status Chart
            const ordersStatusCtx = document.getElementById('ordersStatusChart').getContext('2d');
            const orderStatusData = @json($ordersByStatus);
            new Chart(ordersStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        '{{ app()->getLocale() === "ar" ? "معلق" : "Pending" }}',
                        '{{ app()->getLocale() === "ar" ? "قيد التنفيذ" : "Processing" }}',
                        '{{ app()->getLocale() === "ar" ? "مكتمل" : "Completed" }}',
                        '{{ app()->getLocale() === "ar" ? "ملغي" : "Cancelled" }}'
                    ],
                    datasets: [{
                        data: [
                            orderStatusData['pending'] || 0,
                            orderStatusData['processing'] || 0,
                            orderStatusData['completed'] || 0,
                            orderStatusData['cancelled'] || 0
                        ],
                        backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor }
                        }
                    }
                }
            });

            // Shops Category Chart
            const shopsCategoryCtx = document.getElementById('shopsCategoryChart').getContext('2d');
            new Chart(shopsCategoryCtx, {
                type: 'bar',
                data: {
                    labels: @json($shopsByCategory->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "المحلات" : "Shops" }}',
                        data: @json($shopsByCategory->pluck('shops_count')),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
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

            // Payment Methods Chart
            const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            const paymentData = @json($paymentMethodStats);
            new Chart(paymentMethodsCtx, {
                type: 'pie',
                data: {
                    labels: paymentData.map(p => p.payment_type === 'cod' 
                        ? '{{ app()->getLocale() === "ar" ? "دفع عند الاستلام" : "Cash on Delivery" }}'
                        : '{{ app()->getLocale() === "ar" ? "بطاقة" : "Card" }}'),
                    datasets: [{
                        data: paymentData.map(p => p.count),
                        backgroundColor: ['#f59e0b', '#3b82f6'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor }
                        }
                    }
                }
            });
        });
    </script>
@endsection
