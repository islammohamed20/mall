@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'إدارة الطلبات' : 'Orders Management' }}
                </h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar' ? 'عرض وإدارة جميع الطلبات' : 'View and manage all orders' }}
                </p>
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
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Orders Table --}}
        <div class="admin-card overflow-hidden">
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
@endsection
