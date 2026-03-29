@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-5xl mx-auto px-4 lg:px-8">
            {{-- Back to Store Button --}}
            <div class="mb-4">
                <a 
                    href="{{ route('shop.direct', $shop) }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-200 hover:bg-gray-200 dark:hover:bg-secondary-700 transition-colors text-sm font-medium"
                >
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span>{{ app()->getLocale() === 'ar' ? 'العودة إلى' : 'Back to' }}</span>
                    <span class="font-semibold text-primary-600 dark:text-primary-400">{{ $shop->name }}</span>
                </a>
            </div>

            <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">{{ $product->name }}</h1>

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 items-start">
                <div>
                    @if ($product->images->count())
                        <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-[4/3] dark:bg-secondary-900">
                            <img class="w-full h-full object-cover" src="{{ asset('storage/'.$product->images->first()->path) }}" alt="{{ $product->name }}" loading="lazy" />
                        </div>
                        @if ($product->images->count() > 1)
                            <div class="mt-4 grid grid-cols-4 gap-2">
                                @foreach ($product->images as $image)
                                    <div class="rounded-xl overflow-hidden bg-gray-100 aspect-square dark:bg-secondary-900 cursor-pointer hover:opacity-80 transition">
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" loading="lazy" />
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @elseif ($product->image_url)
                        <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-[4/3] dark:bg-secondary-900">
                            <img class="w-full h-full object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" />
                        </div>
                    @endif
                </div>

                <div>
                    @if (!is_null($product->price))
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                                {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
                            </span>
                            @if (!is_null($product->old_price) && (float) $product->old_price > (float) $product->price)
                                <span class="text-base sm:text-lg text-secondary-400 line-through">
                                    {{ number_format((float) $product->old_price, 2) }} {{ $product->currency }}
                                </span>
                                <span class="px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold">
                                    {{ app()->getLocale() === 'ar' ? 'خصم' : 'SALE' }}
                                </span>
                            @endif
                        </div>
                    @endif

                    @if ($product->description)
                        <div class="mt-4 text-sm sm:text-base text-secondary-700 dark:text-secondary-200 whitespace-pre-line leading-relaxed">
                            {{ $product->description }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="mt-6 space-y-3">
                        @php
                            $favorites = session('favorites', []);
                            $isFavorite = in_array((int) $product->id, array_map('intval', $favorites), true);
                        @endphp

                        {{-- Add to Cart Button --}}
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full h-14 sm:h-16 rounded-2xl bg-gradient-to-r from-primary-500 to-gold-500 text-white font-bold text-base sm:text-lg flex items-center justify-center gap-3 shadow-xl shadow-primary-500/25 hover:shadow-2xl hover:shadow-primary-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <span>{{ app()->getLocale() === 'ar' ? 'أضف إلى السلة' : 'Add to Cart' }}</span>
                            </button>
                        </form>

                        {{-- Add to Favorites Button --}}
                        <form method="POST" action="{{ route('favorites.toggle', $product) }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full h-14 sm:h-16 rounded-2xl border-2 {{ $isFavorite ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-secondary-700 bg-white dark:bg-secondary-900' }} flex items-center justify-center gap-3 font-bold text-base sm:text-lg {{ $isFavorite ? 'text-red-500' : 'text-secondary-700 dark:text-secondary-200' }} hover:border-red-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                            >
                                <svg class="w-6 h-6" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                <span>{{ $isFavorite ? (app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from Favorites') : (app()->getLocale() === 'ar' ? 'أضف إلى المفضلة' : 'Add to Favorites') }}</span>
                            </button>
                        </form>
                    </div>

                    {{-- Shop Info Card --}}
                    <div class="mt-6 p-4 rounded-2xl bg-gray-50 dark:bg-secondary-900/50 border border-gray-100 dark:border-secondary-800">
                        <div class="flex items-center gap-3">
                            @if ($shop->logo_url)
                                <img class="w-12 h-12 rounded-xl object-cover" src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" />
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-gold-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ mb_substr($shop->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-secondary-900 dark:text-secondary-50 truncate">{{ $shop->name }}</div>
                                @if ($shop->category)
                                    <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $shop->category->name }}</div>
                                @endif
                            </div>
                            <a 
                                href="{{ route('shop.direct', $shop) }}" 
                                class="px-4 py-2 rounded-xl bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600 transition-colors"
                            >
                                {{ app()->getLocale() === 'ar' ? 'زيارة المتجر' : 'Visit Store' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
