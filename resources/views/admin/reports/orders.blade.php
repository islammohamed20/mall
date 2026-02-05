@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير الطلبات' : 'Orders Report' }}
                </h1>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</label>
                    <select name="status" class="input-field">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'معلق' : 'Pending' }}</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'قيد التنفيذ' : 'Processing' }}</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مكتمل' : 'Completed' }}</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Type' }}</label>
                    <select name="payment_type" class="input-field">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                        <option value="cod" {{ $paymentType == 'cod' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'دفع عند الاستلام' : 'COD' }}</option>
                        <option value="card" {{ $paymentType == 'card' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'بطاقة' : 'Card' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="input-field">
                </div>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
                <a href="{{ route('admin.reports.orders') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($ordersSummary['total']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'معلق' : 'Pending' }}</p>
                <p class="mt-2 text-2xl font-bold text-yellow-600">{{ number_format($ordersSummary['pending']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'قيد التنفيذ' : 'Processing' }}</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($ordersSummary['processing']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'مكتمل' : 'Completed' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($ordersSummary['completed']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled' }}</p>
                <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($ordersSummary['cancelled']) }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'الطلبات حسب الحالة' : 'Orders by Status' }}</h3>
                <div class="h-64">
                    <canvas id="ordersByStatusChart"></canvas>
                </div>
            </div>
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'الطلبات حسب طريقة الدفع' : 'Orders by Payment Type' }}</h3>
                <div class="h-64">
                    <canvas id="ordersByPaymentChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'قائمة الطلبات' : 'Orders List' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'العميل' : 'Customer' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المبلغ' : 'Amount' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="font-medium">#{{ $order->id }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->phone }}</td>
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
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات' : 'No orders found' }}
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
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';

            // Orders by Status Chart
            const statusCtx = document.getElementById('ordersByStatusChart').getContext('2d');
            const statusData = @json($ordersByStatus);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(s => {
                        const labels = {
                            'pending': '{{ app()->getLocale() === "ar" ? "معلق" : "Pending" }}',
                            'processing': '{{ app()->getLocale() === "ar" ? "قيد التنفيذ" : "Processing" }}',
                            'completed': '{{ app()->getLocale() === "ar" ? "مكتمل" : "Completed" }}',
                            'cancelled': '{{ app()->getLocale() === "ar" ? "ملغي" : "Cancelled" }}'
                        };
                        return labels[s.status] || s.status;
                    }),
                    datasets: [{
                        data: statusData.map(s => s.count),
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

            // Orders by Payment Chart
            const paymentCtx = document.getElementById('ordersByPaymentChart').getContext('2d');
            const paymentData = @json($ordersByPaymentType);
            new Chart(paymentCtx, {
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
