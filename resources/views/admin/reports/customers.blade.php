@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'العملاء' : 'Customers' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير العملاء' : 'Customers Report' }}
                </h1>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'الفترة' : 'Period' }}</label>
                    <select name="period" class="input-field">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 7 أيام' : 'Last 7 days' }}</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 30 يوم' : 'Last 30 days' }}</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر 90 يوم' : 'Last 90 days' }}</option>
                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'آخر سنة' : 'Last year' }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="admin-card p-5 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <p class="text-blue-100 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي العملاء' : 'Total Customers' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($customersSummary['total']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'عملاء جدد' : 'New This Period' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($customersSummary['new_this_period']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'لديهم طلبات' : 'With Orders' }}</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($customersSummary['with_orders']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'بدون طلبات' : 'No Orders' }}</p>
                <p class="mt-2 text-2xl font-bold text-orange-600">{{ number_format($customersSummary['without_orders']) }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- New Customers Over Time --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'العملاء الجدد' : 'New Customers' }}</h3>
                <div class="h-72">
                    <canvas id="newCustomersChart"></canvas>
                </div>
            </div>

            {{-- Customers by City --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'العملاء حسب المدينة' : 'Customers by City' }}</h3>
                <div class="h-72">
                    <canvas id="customersByCityChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Customers --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'أفضل العملاء' : 'Top Customers' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'عدد الطلبات' : 'Orders' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'إجمالي الإنفاق' : 'Total Spent' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'تاريخ التسجيل' : 'Joined' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topCustomers as $index => $customer)
                            <tr>
                                <td>
                                    <span class="w-6 h-6 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 text-sm flex items-center justify-center font-semibold">{{ $index + 1 }}</span>
                                </td>
                                <td class="font-medium">{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded text-sm">
                                        {{ $customer->orders_count }}
                                    </span>
                                </td>
                                <td class="font-semibold text-green-600">{{ number_format($customer->orders_sum_total ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td class="text-secondary-500 text-sm">{{ $customer->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا يوجد عملاء' : 'No customers found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Customers List --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'قائمة العملاء' : 'Customers List' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الإنفاق' : 'Spent' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'تاريخ التسجيل' : 'Joined' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td class="font-medium">{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->orders_count }}</td>
                                <td>{{ number_format($customer->orders_sum_total ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                                <td class="text-secondary-500 text-sm">{{ $customer->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا يوجد عملاء' : 'No customers found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-secondary-800">
                    {{ $customers->withQueryString()->links() }}
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

            // New Customers Chart
            const customersCtx = document.getElementById('newCustomersChart').getContext('2d');
            new Chart(customersCtx, {
                type: 'line',
                data: {
                    labels: @json($newCustomersData->pluck('date')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "عملاء جدد" : "New Customers" }}',
                        data: @json($newCustomersData->pluck('count')),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
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

            // Customers by City Chart
            const cityCtx = document.getElementById('customersByCityChart').getContext('2d');
            new Chart(cityCtx, {
                type: 'bar',
                data: {
                    labels: @json($customersByCity->pluck('shipping_city')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "العملاء" : "Customers" }}',
                        data: @json($customersByCity->pluck('count')),
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
        });
    </script>
@endsection
