@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'المبيعات' : 'Sales' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير المبيعات' : 'Sales Report' }}
                </h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar' ? 'تحليل شامل للمبيعات والإيرادات' : 'Comprehensive analysis of sales and revenue' }}
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'الفترة' : 'Period' }}</label>
                    <select name="period" class="input-field" onchange="toggleCustomDate(this)">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 7 أيام' : 'Last 7 days' }}</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 30 يوم' : 'Last 30 days' }}</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 90 يوم' : 'Last 90 days' }}</option>
                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر سنة' : 'Last year' }}</option>
                        <option value="custom" {{ $startDate ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'فترة مخصصة' : 'Custom range' }}</option>
                    </select>
                </div>
                <div id="customDateFields" class="{{ $startDate ? '' : 'hidden' }} flex gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="input-field">
                    </div>
                </div>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="admin-card p-5 bg-gradient-to-br from-green-500 to-green-600 text-white">
                <p class="text-green-100 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الإيرادات' : 'Total Revenue' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($salesSummary['total_revenue'], 2) }}</p>
                <p class="text-green-100 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($salesSummary['total_orders']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'طلبات مكتملة' : 'Completed' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($salesSummary['completed_orders']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'طلبات ملغاة' : 'Cancelled' }}</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($salesSummary['cancelled_orders']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'متوسط قيمة الطلب' : 'Avg Order Value' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($salesSummary['average_order_value'], 2) }}</p>
                <p class="text-secondary-400 text-xs">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Daily Sales Chart --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'المبيعات اليومية' : 'Daily Sales' }}</h3>
                <div class="h-72">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            {{-- Sales by Payment Type --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'المبيعات حسب طريقة الدفع' : 'Sales by Payment Type' }}</h3>
                <div class="h-72">
                    <canvas id="paymentTypeChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Sales by Shop --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المبيعات حسب المحل' : 'Sales by Shop' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المبيعات' : 'Sales' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'النسبة' : 'Percentage' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSales = $salesByShop->sum('period_sales');
                        @endphp
                        @forelse ($salesByShop as $index => $shop)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-medium">{{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}</td>
                                <td>{{ number_format($shop->period_sales ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td>
                                    @if($totalSales > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-2 bg-gray-200 dark:bg-secondary-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary-500 rounded-full" style="width: {{ ($shop->period_sales / $totalSales) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm">{{ number_format(($shop->period_sales / $totalSales) * 100, 1) }}%</span>
                                        </div>
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد مبيعات في هذه الفترة' : 'No sales in this period' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Orders List --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800 flex items-center justify-between">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}</h3>
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
                        @forelse ($orders as $order)
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
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات في هذه الفترة' : 'No orders in this period' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleCustomDate(select) {
            const customFields = document.getElementById('customDateFields');
            if (select.value === 'custom') {
                customFields.classList.remove('hidden');
            } else {
                customFields.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            // Daily Sales Chart
            const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
            new Chart(dailySalesCtx, {
                type: 'line',
                data: {
                    labels: @json($dailySales->pluck('date')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "الإيرادات" : "Revenue" }}',
                        data: @json($dailySales->pluck('revenue')),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y',
                    }, {
                        label: '{{ app()->getLocale() === "ar" ? "الطلبات" : "Orders" }}',
                        data: @json($dailySales->pluck('orders')),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y1',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { drawOnChartArea: false }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Payment Type Chart
            const paymentTypeCtx = document.getElementById('paymentTypeChart').getContext('2d');
            const paymentData = @json($salesByPaymentType);
            new Chart(paymentTypeCtx, {
                type: 'bar',
                data: {
                    labels: paymentData.map(p => p.payment_type === 'cod' 
                        ? '{{ app()->getLocale() === "ar" ? "دفع عند الاستلام" : "Cash on Delivery" }}'
                        : '{{ app()->getLocale() === "ar" ? "بطاقة" : "Card" }}'),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "الطلبات" : "Orders" }}',
                        data: paymentData.map(p => p.orders),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                    }, {
                        label: '{{ app()->getLocale() === "ar" ? "الإيرادات" : "Revenue" }}',
                        data: paymentData.map(p => p.revenue),
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor }
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
        });
    </script>
@endsection
