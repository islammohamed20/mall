@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}</a>
                    <span>/</span>
                    <span>#{{ $order->id }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }} #{{ $order->id }}
                </h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ $order->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <div>
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
                <span class="inline-flex px-4 py-2 rounded-lg text-sm font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 dark:bg-secondary-800 text-gray-800 dark:text-gray-200' }}">
                    {{ $statusLabels[$order->status] ?? $order->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Order Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Products --}}
                <div class="admin-card overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                        <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @foreach($order->cart_snapshot as $item)
                            <div class="p-5 flex items-start gap-4">
                                @if(isset($item['image']))
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-secondary-800 dark:bg-secondary-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ app()->getLocale() === 'ar' ? ($item['name_ar'] ?? $item['name']) : ($item['name_en'] ?? $item['name']) }}</h4>
                                    <p class="text-sm text-secondary-500 mt-1">
                                        {{ app()->getLocale() === 'ar' ? ($item['shop_name_ar'] ?? $item['shop_name'] ?? '') : ($item['shop_name_en'] ?? $item['shop_name'] ?? '') }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-4 text-sm">
                                        <span>{{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }} {{ number_format($item['price'], 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Qty:' }} {{ $item['quantity'] }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <p class="font-semibold">{{ number_format($item['total'], 2) }}</p>
                                    <p class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-5 py-4 bg-gray-50 dark:bg-secondary-900 dark:bg-secondary-900 border-t border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                            <span>{{ number_format($order->total, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                @if($order->shipping_address)
                    <div class="admin-card p-5">
                        <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'عنوان الشحن' : 'Shipping Address' }}</h3>
                        <div class="space-y-2 text-secondary-700 dark:text-secondary-300">
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}</p>
                        </div>
                    </div>
                @endif

                {{-- Billing Info --}}
                @if($order->billing_address)
                    <div class="admin-card p-5">
                        <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'عنوان الفوترة' : 'Billing Address' }}</h3>
                        <div class="space-y-2 text-secondary-700 dark:text-secondary-300">
                            <p><strong>{{ app()->getLocale() === 'ar' ? 'الاسم:' : 'Name:' }}</strong> {{ $order->billing_name }}</p>
                            <p><strong>{{ app()->getLocale() === 'ar' ? 'البريد:' : 'Email:' }}</strong> {{ $order->billing_email }}</p>
                            <p><strong>{{ app()->getLocale() === 'ar' ? 'الهاتف:' : 'Phone:' }}</strong> {{ $order->billing_phone }}</p>
                            <p><strong>{{ app()->getLocale() === 'ar' ? 'العنوان:' : 'Address:' }}</strong> {{ $order->billing_address }}</p>
                            <p><strong>{{ app()->getLocale() === 'ar' ? 'المدينة:' : 'City:' }}</strong> {{ $order->billing_city }}</p>
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if($order->notes)
                    <div class="admin-card p-5">
                        <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'ملاحظات' : 'Notes' }}</h3>
                        <p class="text-secondary-700 dark:text-secondary-300">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Customer Info --}}
                <div class="admin-card p-5">
                    <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Info' }}</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</p>
                            <p class="font-medium">{{ $order->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</p>
                            <p class="font-medium">{{ $order->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</p>
                            <p class="font-medium">{{ $order->phone }}</p>
                        </div>
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="admin-card p-5">
                    <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'معلومات الدفع' : 'Payment Info' }}</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</p>
                            <p class="font-medium">
                                @if($order->paymentMethod)
                                    {{ app()->getLocale() === 'ar' ? $order->paymentMethod->name_ar : $order->paymentMethod->name_en }}
                                @else
                                    {{ $order->payment_type === 'cod' ? (app()->getLocale() === 'ar' ? 'دفع عند الاستلام' : 'Cash on Delivery') : (app()->getLocale() === 'ar' ? 'بطاقة' : 'Card') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'نوع الدفع' : 'Payment Type' }}</p>
                            <span class="inline-flex px-2 py-1 rounded text-xs {{ $order->payment_type === 'cod' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ $order->payment_type === 'cod' ? (app()->getLocale() === 'ar' ? 'دفع عند الاستلام' : 'COD') : (app()->getLocale() === 'ar' ? 'بطاقة' : 'Card') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Update Status --}}
                <div class="admin-card p-5">
                    <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'تحديث الحالة' : 'Update Status' }}</h3>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <select name="status" class="input-field w-full">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'معلق' : 'Pending' }}</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'قيد التنفيذ' : 'Processing' }}</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مكتمل' : 'Completed' }}</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled' }}</option>
                            </select>
                            <button type="submit" class="btn-primary w-full">
                                {{ app()->getLocale() === 'ar' ? 'تحديث الحالة' : 'Update Status' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
