@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-4xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'سلة التسوق' : 'Shopping Cart' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'إدارة المنتجات في سلتك' : 'Manage products in your cart' }}
                    </p>
                </div>
                @if ($cartItems->count())
                    <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من إفراغ السلة؟' : 'Are you sure you want to clear the cart?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            {{ app()->getLocale() === 'ar' ? 'إفراغ السلة' : 'Clear Cart' }}
                        </button>
                    </form>
                @endif
            </div>

            @if ($cartItems->count())
                <div class="mt-8 space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="card p-4 sm:p-5">
                            <div class="flex gap-4">
                                {{-- Product Image --}}
                                <a href="{{ route('shops.products.show', [$item['product']->shop, $item['product']]) }}" class="shrink-0">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gray-100 dark:bg-secondary-900 overflow-hidden">
                                        @if ($item['product']->image_url)
                                            <img class="w-full h-full object-cover" src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-gold-900/30"></div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('shops.products.show', [$item['product']->shop, $item['product']]) }}" class="font-semibold text-secondary-900 dark:text-secondary-50 hover:text-primary-600 dark:hover:text-primary-400 transition-colors line-clamp-2">
                                        {{ $item['product']->name }}
                                    </a>
                                    @if ($item['product']->shop)
                                        <a href="{{ route('shop.direct', $item['product']->shop) }}" class="mt-1 text-sm text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors block">
                                            {{ $item['product']->shop->name }}
                                        </a>
                                    @endif
                                    
                                    {{-- Price --}}
                                    @if ($item['product']->price)
                                        <div class="mt-2 text-primary-600 dark:text-primary-400 font-semibold">
                                            {{ number_format((float) $item['product']->price, 2) }} {{ $item['product']->currency }}
                                        </div>
                                    @endif

                                    {{-- Actions Row (Mobile) --}}
                                    <div class="mt-3 flex items-center gap-3 flex-wrap sm:hidden">
                                        {{-- Quantity --}}
                                        <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-secondary-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-secondary-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="w-8 h-8 rounded-lg border border-gray-200 dark:border-secondary-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-secondary-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </form>

                                        {{-- Remove --}}
                                        <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center transition-colors" title="{{ app()->getLocale() === 'ar' ? 'إزالة' : 'Remove' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Desktop Actions --}}
                                <div class="hidden sm:flex items-center gap-4">
                                    {{-- Quantity --}}
                                    <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-secondary-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-secondary-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                        <span class="w-10 text-center font-medium">{{ $item['quantity'] }}</span>
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-secondary-700 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-secondary-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </form>

                                    {{-- Subtotal --}}
                                    @if ($item['subtotal'])
                                        <div class="w-24 text-end font-semibold text-secondary-900 dark:text-secondary-50">
                                            {{ number_format($item['subtotal'], 2) }} {{ $item['product']->currency }}
                                        </div>
                                    @endif

                                    {{-- Remove --}}
                                    <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center transition-colors" title="{{ app()->getLocale() === 'ar' ? 'إزالة' : 'Remove' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Summary --}}
                @if ($total > 0)
                    <div class="mt-8 card p-5 sm:p-6">
                        <div class="flex items-center justify-between text-lg font-bold text-secondary-900 dark:text-secondary-50">
                            <span>{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                            <span>{{ number_format($total, 2) }} {{ $cartItems->first()['product']->currency ?? 'EGP' }}</span>
                        </div>
                        <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                            {{ app()->getLocale() === 'ar' ? 'أكمل بياناتك لاختيار طريقة الدفع وإتمام الطلب.' : 'Complete your details to choose a payment method and place the order.' }}
                        </p>
                    </div>

                    <div class="mt-6 card p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="text-lg font-bold text-secondary-900 dark:text-secondary-50">
                                    {{ app()->getLocale() === 'ar' ? 'إتمام الشراء والدفع' : 'Checkout & Payment' }}
                                </div>
                                <p class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">
                                    {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع المناسبة وتواصل لإتمام الشراء.' : 'Choose a payment method and contact us to complete your purchase.' }}
                                </p>
                            </div>
                            <a href="{{ route('checkout.create') }}" class="btn-primary text-sm">
                                {{ app()->getLocale() === 'ar' ? 'إتمام الشراء' : 'Proceed to Checkout' }}
                            </a>
                        </div>

                        @if ($paymentMethods->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-3">
                                @foreach ($paymentMethods as $method)
                                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-secondary-100 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                                        @if ($method->icon)
                                            <span class="text-lg">{{ $method->icon }}</span>
                                        @endif
                                        <div class="text-sm font-medium">{{ $method->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Continue Shopping --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('shops.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                    </a>
                </div>
            @else
                {{-- Empty Cart --}}
                <div class="mt-12 text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center">
                        <svg class="w-12 h-12 text-secondary-400 dark:text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'سلتك فارغة' : 'Your cart is empty' }}
                    </h2>
                    <p class="mt-2 text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'ابدأ بإضافة بعض المنتجات!' : 'Start adding some products!' }}
                    </p>
                    <a href="{{ route('shops.index') }}" class="btn-primary mt-6">
                        {{ app()->getLocale() === 'ar' ? 'تصفح المحلات' : 'Browse Shops' }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
