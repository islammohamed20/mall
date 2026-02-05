@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-6xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'إتمام الشراء' : 'Checkout' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'أدخل بياناتك واختر طريقة الدفع المناسبة.' : 'Enter your details and choose a payment method.' }}
                    </p>
                </div>
                <a href="{{ route('cart.index') }}" class="btn-outline">
                    {{ app()->getLocale() === 'ar' ? 'رجوع للسلة' : 'Back to Cart' }}
                </a>
            </div>

            @php
                $selectedMethodId = old('payment_method_id', $paymentMethods->first()?->id);
                $selectedMethod = $paymentMethods->firstWhere('id', $selectedMethodId);
                $selectedType = $selectedMethod?->type ?? 'cod';
            @endphp

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <form class="lg:col-span-2 card p-5 sm:p-6 space-y-6" method="POST" action="{{ route('checkout.store') }}" x-data="{ paymentType: '{{ $selectedType }}' }">
                    @csrf

                    <div class="space-y-4">
                        <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'بيانات العميل' : 'Customer Details' }}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                                <input class="form-input" name="name" value="{{ old('name') }}" required />
                                @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم التواصل' : 'Phone' }}</label>
                                <input class="form-input" name="phone" value="{{ old('phone') }}" required />
                                @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                            <input class="form-input" type="email" name="email" value="{{ old('email') }}" required />
                            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($paymentMethods as $method)
                                <label class="border rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-colors {{ (int) $selectedMethodId === (int) $method->id ? 'border-primary-500 bg-primary-50/60 dark:bg-primary-900/20' : 'border-gray-200 dark:border-secondary-700' }}">
                                    <input
                                        type="radio"
                                        name="payment_method_id"
                                        value="{{ $method->id }}"
                                        class="text-primary-600 focus:ring-primary-500"
                                        @checked((int) $selectedMethodId === (int) $method->id)
                                        @change="paymentType = '{{ $method->type }}'"
                                    />
                                    <div class="flex items-center gap-2">
                                        @if ($method->icon)
                                            <span class="text-lg">{{ $method->icon }}</span>
                                        @endif
                                        <span class="font-medium text-secondary-900 dark:text-secondary-100">{{ $method->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="space-y-4" x-show="paymentType === 'card'">
                        <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'بيانات الشحن والفوترة' : 'Shipping & Billing' }}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'عنوان التوصيل' : 'Shipping Address' }}</label>
                                <input class="form-input" name="shipping_address" value="{{ old('shipping_address') }}" :required="paymentType === 'card'" />
                                @error('shipping_address') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}</label>
                                <input class="form-input" name="shipping_city" value="{{ old('shipping_city') }}" :required="paymentType === 'card'" />
                                @error('shipping_city') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم الفاتورة' : 'Billing Name' }}</label>
                                <input class="form-input" name="billing_name" value="{{ old('billing_name') }}" :required="paymentType === 'card'" />
                                @error('billing_name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'بريد الفاتورة' : 'Billing Email' }}</label>
                                <input class="form-input" type="email" name="billing_email" value="{{ old('billing_email') }}" :required="paymentType === 'card'" />
                                @error('billing_email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الفاتورة' : 'Billing Phone' }}</label>
                                <input class="form-input" name="billing_phone" value="{{ old('billing_phone') }}" :required="paymentType === 'card'" />
                                @error('billing_phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مدينة الفاتورة' : 'Billing City' }}</label>
                                <input class="form-input" name="billing_city" value="{{ old('billing_city') }}" :required="paymentType === 'card'" />
                                @error('billing_city') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'عنوان الفاتورة' : 'Billing Address' }}</label>
                            <input class="form-input" name="billing_address" value="{{ old('billing_address') }}" :required="paymentType === 'card'" />
                            @error('billing_address') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="space-y-4" x-show="paymentType === 'cod'">
                        <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'بيانات إضافية (اختياري)' : 'Additional Details (Optional)' }}
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}</label>
                                <input class="form-input" name="shipping_address" value="{{ old('shipping_address') }}" />
                                @error('shipping_address') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}</label>
                                <input class="form-input" name="shipping_city" value="{{ old('shipping_city') }}" />
                                @error('shipping_city') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ملاحظات إضافية' : 'Additional Notes' }}</label>
                        <textarea class="form-input" rows="3" name="notes">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn-primary w-full sm:w-auto" type="submit">
                        {{ app()->getLocale() === 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}
                    </button>
                </form>

                <div class="card p-5 sm:p-6">
                    <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach ($cartItems as $item)
                            <div class="flex items-center justify-between text-sm text-secondary-700 dark:text-secondary-200">
                                <span class="line-clamp-1">{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                                <span>{{ number_format($item['subtotal'], 2) }} {{ $item['product']->currency }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-secondary-800 flex items-center justify-between font-semibold text-secondary-900 dark:text-secondary-50">
                        <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                        <span>{{ number_format($total, 2) }} {{ $cartItems->first()['product']->currency ?? 'EGP' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
