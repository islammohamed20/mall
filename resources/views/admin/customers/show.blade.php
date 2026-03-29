@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تفاصيل العميل' : 'Customer Details' }}</h1>
        <a href="{{ route('admin.customers.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'عودة' : 'Back' }}</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-bold text-secondary-900 dark:text-secondary-50 mb-4">{{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Info' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-secondary-400 uppercase">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                        <p class="text-secondary-900 dark:text-secondary-200 font-medium">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary-400 uppercase">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <p class="text-secondary-900 dark:text-secondary-200 font-medium">{{ $customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary-400 uppercase">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
                        <p class="text-secondary-900 dark:text-secondary-200 font-medium">{{ $customer->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary-400 uppercase">{{ app()->getLocale() === 'ar' ? 'تاريخ التسجيل' : 'Registered At' }}</label>
                        <p class="text-secondary-900 dark:text-secondary-200 font-medium">{{ $customer->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-secondary-800 flex gap-2">
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-secondary-sm flex-1 justify-center">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-secondary-800">
                    <h3 class="text-lg font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'آخر الطلبات' : 'Recent Orders' }}</h3>
                </div>
                
                @if($customer->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                                <tr>
                                    <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'رقم الطلب' : 'Order #' }}</th>
                                    <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                                    <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                                    <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المجموع' : 'Total' }}</th>
                                    <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                                @foreach($customer->orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                        <td class="px-5 py-3 font-medium text-secondary-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="px-5 py-3">
                                            <span class="badge badge-secondary">{{ $order->status }}</span>
                                        </td>
                                        <td class="px-5 py-3 font-medium text-secondary-900 dark:text-white">{{ number_format($order->total, 2) }}</td>
                                        <td class="px-5 py-3 text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary-xs">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-secondary-500 dark:text-secondary-400">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات لهذا العميل.' : 'No orders found for this customer.' }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
