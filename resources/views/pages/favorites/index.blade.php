@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Favorites' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'المنتجات المحفوظة لديك' : 'Your saved products' }}
                    </p>
                </div>
                @if ($products->count())
                    <form method="POST" action="{{ route('favorites.clear') }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من إفراغ المفضلة؟' : 'Are you sure you want to clear favorites?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            {{ app()->getLocale() === 'ar' ? 'إفراغ المفضلة' : 'Clear Favorites' }}
                        </button>
                    </form>
                @endif
            </div>

            @if ($products->count())
                <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        <div class="card overflow-hidden group">
                            {{-- Product Image --}}
                            <a href="{{ route('shops.products.show', [$product->shop, $product]) }}" class="block relative">
                                <div class="aspect-square bg-gray-100 dark:bg-secondary-900 overflow-hidden">
                                    @if ($product->image_url)
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" />
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-gold-900/30 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-primary-400 dark:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Remove Button (Overlay) --}}
                                <form method="POST" action="{{ route('favorites.remove', $product) }}" class="absolute top-2 ltr:right-2 rtl:left-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-white/90 dark:bg-secondary-900/90 text-red-500 hover:bg-red-500 hover:text-white shadow-lg flex items-center justify-center transition-all" title="{{ app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from favorites' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                        </svg>
                                    </button>
                                </form>
                            </a>

                            {{-- Product Info --}}
                            <div class="p-3 sm:p-4">
                                <a href="{{ route('shops.products.show', [$product->shop, $product]) }}" class="font-semibold text-sm text-secondary-900 dark:text-secondary-50 hover:text-primary-600 dark:hover:text-primary-400 transition-colors line-clamp-2">
                                    {{ $product->name }}
                                </a>
                                
                                @if ($product->shop)
                                    <a href="{{ route('shop.direct', $product->shop) }}" class="mt-1 text-xs text-secondary-500 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors block truncate">
                                        {{ $product->shop->name }}
                                    </a>
                                @endif
                                
                                @if ($product->price)
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="text-primary-600 dark:text-primary-400 font-bold text-sm">
                                            {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
                                        </span>
                                        @if ($product->old_price && (float) $product->old_price > (float) $product->price)
                                            <span class="text-xs text-secondary-400 line-through">
                                                {{ number_format((float) $product->old_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Add to Cart Button --}}
                                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="btn-primary btn-sm w-12 h-12 sm:w-full sm:h-auto flex items-center justify-center gap-2"
                                        aria-label="{{ app()->getLocale() === 'ar' ? 'إضافة إلى السلة' : 'Add to cart' }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                        <span class="hidden sm:inline">{{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Continue Shopping --}}
                <div class="mt-8 text-center">
                    <a href="{{ route('shops.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                    </a>
                </div>
            @else
                {{-- Empty Favorites --}}
                <div class="mt-12 text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center">
                        <svg class="w-12 h-12 text-secondary-400 dark:text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'المفضلة فارغة' : 'No favorites yet' }}
                    </h2>
                    <p class="mt-2 text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'احفظ المنتجات التي تعجبك!' : 'Save products you like!' }}
                    </p>
                    <a href="{{ route('shops.index') }}" class="btn-primary mt-6">
                        {{ app()->getLocale() === 'ar' ? 'تصفح المحلات' : 'Browse Shops' }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
