@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-6xl mx-auto px-4 lg:px-8">
            <div class="flex items-start sm:items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'إدارة بيانات حسابك وعرض طلباتك.' : 'Manage your account and view your orders.' }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @if($user?->is_admin)
                        <a class="btn-outline" href="{{ route('admin.dashboard') }}">
                            {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Admin' }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-primary" type="submit">
                            {{ app()->getLocale() === 'ar' ? 'تسجيل خروج' : 'Logout' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="card p-5 sm:p-6">
                        <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'بيانات الحساب' : 'Account Details' }}
                        </div>

                        <div class="mt-4 space-y-3 text-sm">
                            <div>
                                <div class="text-secondary-500 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</div>
                                <div class="font-medium text-secondary-900 dark:text-secondary-100">{{ $user->name }}</div>
                            </div>
                            <div>
                                <div class="text-secondary-500 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</div>
                                <div class="font-medium text-secondary-900 dark:text-secondary-100" dir="ltr">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <a class="btn-outline" href="{{ route('favorites.index') }}">
                                {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Favorites' }}
                            </a>
                            <a class="btn-outline" href="{{ route('cart.index') }}">
                                {{ app()->getLocale() === 'ar' ? 'السلة' : 'Cart' }}
                            </a>
                            <a class="btn-outline" href="{{ route('shops.index') }}">
                                {{ app()->getLocale() === 'ar' ? 'تصفح المحلات' : 'Browse Shops' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="card p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                                {{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}
                            </div>
                        </div>

                        @if($orders->isEmpty())
                            <div class="mt-6 text-sm text-secondary-600 dark:text-secondary-300">
                                {{ app()->getLocale() === 'ar' ? 'لا توجد طلبات حتى الآن.' : 'No orders yet.' }}
                            </div>
                        @else
                            <div class="mt-5 overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-secondary-500 dark:text-secondary-400 border-b border-gray-100 dark:border-secondary-800">
                                            <th class="text-start font-medium py-3">#</th>
                                            <th class="text-start font-medium py-3">{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                                            <th class="text-start font-medium py-3">{{ app()->getLocale() === 'ar' ? 'الدفع' : 'Payment' }}</th>
                                            <th class="text-start font-medium py-3">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                                            <th class="text-start font-medium py-3">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                                        @foreach($orders as $order)
                                            <tr class="text-secondary-800 dark:text-secondary-200">
                                                <td class="py-3 font-medium">{{ $order->id }}</td>
                                                <td class="py-3">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                                <td class="py-3">
                                                    @if($order->paymentMethod)
                                                        {{ app()->getLocale() === 'ar' ? $order->paymentMethod->name_ar : $order->paymentMethod->name_en }}
                                                    @else
                                                        {{ $order->payment_type }}
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-secondary-100 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="py-3 font-semibold">{{ number_format((float) $order->total, 2) }} {{ $order->currency }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
