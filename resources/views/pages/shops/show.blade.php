@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-secondary-900">
                @if ($shop->cover_url)
                    <img class="w-full h-40 sm:h-52 lg:h-64 object-cover" src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" />
                @else
                    <div class="h-40 sm:h-52 lg:h-64 w-full bg-gradient-to-r from-primary-200 to-gold-200 dark:from-primary-900 dark:to-yellow-900/40"></div>
                @endif
            </div>

            <div class="mt-6 lg:mt-8 space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    @if ($shop->logo_url)
                        <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl bg-white shadow flex items-center justify-center overflow-hidden">
                            <img class="w-full h-full object-contain" src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" />
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-secondary-500 dark:text-secondary-300">
                            @if ($shop->category)
                                <span>{{ $shop->category->icon_symbol ?? '🏬' }}</span>
                            @endif
                            <span>{{ $shop->category?->name }}</span>
                        </div>
                        <h1 class="mt-1 text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50 flex items-center gap-2 flex-wrap">
                            <span>{{ $shop->name }}</span>
                            @if ($shop->is_open_now)
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-[11px] sm:text-xs font-semibold text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                    {{ app()->getLocale() === 'ar' ? 'مفتوح الآن' : 'Open now' }}
                                </span>
                            @endif
                        </h1>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300">
                            @if ($shop->floorRelation)
                                <span class="badge badge-info">{{ $shop->floorRelation->name }}</span>
                            @endif
                            @if ($shop->unit_number)
                                <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'وحدة' : 'Unit' }}: {{ $shop->unit_number }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex justify-start lg:justify-end">
                    <a class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-secondary-700 hover:bg-gray-50 dark:border-secondary-700 dark:text-secondary-100 dark:hover:bg-secondary-900" href="{{ route('shops.index') }}">
                        {{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}
                    </a>
                </div>
            </div>

            @if ($shop->description)
                <div class="mt-6 lg:mt-8 card p-6 lg:p-10">
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'نبذة' : 'About' }}</div>
                    <p class="mt-3 text-base sm:text-lg text-secondary-700 dark:text-secondary-200 whitespace-pre-line leading-relaxed">{{ $shop->description }}</p>
                </div>
            @endif

            @if ($shop->products->count())
                @php
                    $categorySlug = $shop->category?->slug;
                    $isRestaurantCategory = in_array($categorySlug, ['restaurants', 'cafes']);
                @endphp

                @if ($isRestaurantCategory)
                    <div class="mt-10">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'المنيو' : 'Menu' }}
                        </div>
                        <div class="mt-6 card divide-y divide-gray-100 dark:divide-secondary-800">
                            @foreach ($shop->products as $product)
                                <a
                                    href="{{ route('shops.products.show', [$shop, $product]) }}"
                                    class="flex flex-col sm:flex-row gap-3 sm:gap-4 px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 dark:hover:bg-secondary-900 transition"
                                >
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-base sm:text-lg text-secondary-900 dark:text-secondary-50 truncate">
                                            {{ $product->name }}
                                        </div>
                                    </div>
                                    @if (!is_null($product->price))
                                        <div class="flex items-center justify-start sm:justify-end">
                                            <div class="text-base sm:text-lg text-primary-700 font-semibold">
                                                {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
                                            </div>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-10" x-data="{ 
                        quickViewOpen: false, 
                        quickViewProduct: null,
                        openQuickView(el) {
                            this.quickViewProduct = {
                                name: el.dataset.name,
                                price: el.dataset.price || null,
                                old_price: el.dataset.oldPrice || null,
                                image_url: el.dataset.image || null,
                                description: el.dataset.description || null,
                                url: el.dataset.url,
                                cart_url: el.dataset.cartUrl
                            };
                            this.quickViewOpen = true;
                        }
                    }">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                            {{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}
                        </div>
                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($shop->products as $product)
                                <div class="card h-full flex flex-col overflow-hidden hover:-translate-y-0.5">
                                    <a href="{{ route('shops.products.show', [$shop, $product]) }}" class="bg-gray-100 aspect-[4/3] dark:bg-secondary-900 block">
                                        @if ($product->image_url)
                                            <img class="w-full h-full object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-yellow-900/30"></div>
                                        @endif
                                    </a>
                                    <div class="p-4 flex flex-col gap-3">
                                        <div class="font-semibold text-base sm:text-lg text-secondary-900 dark:text-secondary-50 line-clamp-2">{{ $product->name }}</div>

                                        @if (!is_null($product->price))
                                            <div class="flex items-baseline justify-between gap-2 text-sm sm:text-base">
                                                @if (!is_null($product->old_price) && (float) $product->old_price > (float) $product->price)
                                                    <span class="text-secondary-400 line-through">
                                                        {{ number_format((float) $product->old_price, 2) }} {{ $product->currency }}
                                                    </span>
                                                @else
                                                    <span class="text-secondary-400">&nbsp;</span>
                                                @endif

                                                <span class="text-primary-700 font-semibold">
                                                    {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="mt-3 rounded-2xl border border-gray-100 dark:border-secondary-800 bg-white/80 dark:bg-secondary-900/40 p-2 sm:p-3 shadow-sm">
                                            <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                                                {{-- Add to Cart Button --}}
                                                <form class="col-span-1 sm:flex-1" method="POST" action="{{ route('cart.add', $product) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="w-full h-12 rounded-2xl bg-gradient-to-r from-primary-500 to-gold-500 text-white font-semibold text-sm sm:text-base flex items-center justify-center gap-1.5 shadow-lg shadow-primary-500/20 hover:-translate-y-0.5 transition"
                                                        aria-label="{{ app()->getLocale() === 'ar' ? 'إضافة إلى السلة' : 'Add to cart' }}"
                                                    >
                                                        <span class="hidden sm:inline">{{ app()->getLocale() === 'ar' ? 'إضافة إلى السلة' : 'Add to Cart' }}</span>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                {{-- Favorites Button --}}
                                                <form class="col-span-1 sm:flex-1" method="POST" action="{{ route('favorites.toggle', $product) }}">
                                                    @csrf
                                                    @php
                                                        $favorites = session('favorites', []);
                                                        $isFavorite = in_array((int) $product->id, array_map('intval', $favorites), true);
                                                    @endphp
                                                    <button
                                                        type="submit"
                                                        class="w-full h-12 rounded-2xl border {{ $isFavorite ? 'border-red-500 bg-red-50 text-red-500 dark:bg-red-900/20 dark:border-red-500' : 'border-gray-200 dark:border-secondary-700 text-secondary-600 dark:text-secondary-300' }} flex items-center justify-center gap-1.5 font-semibold text-sm sm:text-base hover:border-red-500 hover:text-red-500 transition"
                                                        title="{{ $isFavorite ? (app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from favorites') : (app()->getLocale() === 'ar' ? 'أضف للمفضلة' : 'Add to favorites') }}"
                                                        aria-label="{{ $isFavorite ? (app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from favorites') : (app()->getLocale() === 'ar' ? 'أضف للمفضلة' : 'Add to favorites') }}"
                                                    >
                                                        <span class="hidden sm:inline">{{ $isFavorite ? (app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from Favorites') : (app()->getLocale() === 'ar' ? 'إضافة إلى المفضلة' : 'Add to Favorites') }}</span>
                                                        <svg class="w-5 h-5" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            {{-- Quick View Button --}}
                                            <button
                                                type="button"
                                                class="quick-view-btn mt-3 w-full rounded-2xl border border-primary-100 bg-primary-50/70 text-primary-700 dark:border-primary-900/40 dark:bg-primary-900/20 font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 py-2 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ !is_null($product->price) ? number_format((float) $product->price, 2) . ' ' . $product->currency : '' }}"
                                                data-old-price="{{ !is_null($product->old_price) && (float) $product->old_price > (float) $product->price ? number_format((float) $product->old_price, 2) . ' ' . $product->currency : '' }}"
                                                data-image="{{ $product->image_url ?? '' }}"
                                                data-description="{{ $product->description ?? '' }}"
                                                data-url="{{ route('shops.products.show', [$shop, $product]) }}"
                                                data-cart-url="{{ route('cart.add', $product) }}"
                                                @click="openQuickView($el)"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 0c0 2.5-4.03 6.75-9 6.75S3 14.5 3 12s4.03-6.75 9-6.75S21 9.5 21 12Z"/></svg>
                                                {{ app()->getLocale() === 'ar' ? 'معاينة سريعة' : 'Quick view' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- Quick View Modal --}}
                        <div
                            x-show="quickViewOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6"
                            @keydown.escape.window="quickViewOpen = false"
                            @click.self="quickViewOpen = false"
                        >
                            <div
                                x-show="quickViewOpen"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                                class="bg-white dark:bg-secondary-950 rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden flex flex-col"
                                @click.stop
                            >
                                {{-- Header --}}
                                <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-secondary-800 bg-gray-50/50 dark:bg-secondary-900/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-gold-500 flex items-center justify-center shadow-lg shadow-primary-500/20">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 0c0 2.5-4.03 6.75-9 6.75S3 14.5 3 12s4.03-6.75 9-6.75S21 9.5 21 12Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs text-secondary-500 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'معاينة سريعة' : 'Quick View' }}</div>
                                            <div class="font-bold text-secondary-900 dark:text-secondary-50 text-sm sm:text-base line-clamp-1" x-text="quickViewProduct?.name"></div>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-secondary-800 flex items-center justify-center text-secondary-500 hover:text-secondary-800 dark:text-secondary-400 dark:hover:text-secondary-100 hover:bg-gray-200 dark:hover:bg-secondary-700 transition-colors"
                                        @click="quickViewOpen = false"
                                        aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-5">
                                    {{-- Product Image --}}
                                    <template x-if="quickViewProduct?.image_url">
                                        <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-50 dark:from-secondary-900 dark:to-secondary-800 aspect-[4/3] shadow-inner">
                                            <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" :src="quickViewProduct.image_url" :alt="quickViewProduct.name" loading="lazy" />
                                        </div>
                                    </template>

                                    {{-- Price Section --}}
                                    <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-gradient-to-r from-primary-50 to-gold-50 dark:from-primary-900/20 dark:to-gold-900/20 border border-primary-100 dark:border-primary-800/30" x-show="quickViewProduct?.price">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-2xl font-bold text-primary-700 dark:text-primary-400" x-text="quickViewProduct.price"></span>
                                            <span class="text-sm text-secondary-400 line-through" x-show="quickViewProduct?.old_price" x-text="quickViewProduct.old_price"></span>
                                        </div>
                                        <template x-if="quickViewProduct?.old_price">
                                            <span class="px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-semibold">
                                                {{ app()->getLocale() === 'ar' ? 'خصم' : 'Sale' }}
                                            </span>
                                        </template>
                                    </div>

                                    {{-- Description --}}
                                    <div
                                        class="text-sm text-secondary-700 dark:text-secondary-200 whitespace-pre-line leading-relaxed"
                                        x-show="quickViewProduct?.description"
                                        x-text="quickViewProduct.description"
                                    ></div>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 dark:border-secondary-800 bg-gray-50/50 dark:bg-secondary-900/50">
                                    <div class="flex gap-3">
                                        <a
                                            :href="quickViewProduct?.url"
                                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-primary-500 to-gold-500 text-white font-semibold text-sm shadow-lg shadow-primary-500/20 hover:-translate-y-0.5 transition-all"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                            </svg>
                                            {{ app()->getLocale() === 'ar' ? 'عرض التفاصيل' : 'View Details' }}
                                        </a>
                                        <form method="POST" :action="quickViewProduct?.cart_url">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold text-sm hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                </svg>
                                                <span class="hidden sm:inline">{{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if ($shop->facebookPosts->count())
                <div class="mt-10">
                    <div class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'آخر منشورات فيسبوك' : 'Latest Facebook Posts' }}</div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach ($shop->facebookPosts as $post)
                            <a class="card overflow-hidden hover:bg-gray-50 dark:hover:bg-secondary-900 transition" href="{{ $post->permalink_url ?: '#' }}" target="{{ $post->permalink_url ? '_blank' : '_self' }}" rel="noreferrer">
                                @if ($post->image_url)
                                    <div class="bg-gray-100 aspect-[4/3] dark:bg-secondary-900">
                                        <img class="w-full h-full object-cover" src="{{ $post->image_url }}" alt="" loading="lazy" />
                                    </div>
                                @endif
                                <div class="p-4 sm:p-5">
                                    @if ($post->posted_at)
                                        <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ $post->posted_at->format('Y-m-d') }}</div>
                                    @endif
                                    @if ($post->message)
                                        <div class="mt-2 text-sm text-secondary-900 dark:text-secondary-100 line-clamp-4 whitespace-pre-line">{{ $post->message }}</div>
                                    @else
                                        <div class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'منشور بدون نص' : 'Post without text' }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="card p-5 sm:p-6">
                    <div class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'ساعات العمل' : 'Opening Hours' }}</div>
                    <div class="mt-2 text-secondary-700 dark:text-secondary-200">{{ $shop->opening_hours ?? (app()->getLocale() === 'ar' ? 'يومياً' : 'Daily') }}</div>
                </div>
                <div class="card p-5 sm:p-6">
                    <div class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'التواصل' : 'Contact' }}</div>
                    <div class="mt-3 space-y-2 text-sm text-secondary-700 dark:text-secondary-200">
                        @if ($shop->phone)<div>{{ $shop->phone }}</div>@endif
                        @if ($shop->email)<div><a class="text-primary-700 hover:text-primary-800" href="mailto:{{ $shop->email }}">{{ $shop->email }}</a></div>@endif
                        @if ($shop->whatsapp_link)<div><a class="text-primary-700 hover:text-primary-800" href="{{ $shop->whatsapp_link }}" target="_blank" rel="noreferrer">WhatsApp</a></div>@endif
                        @if ($shop->website)<div><a class="text-primary-700 hover:text-primary-800" href="{{ $shop->website }}" target="_blank" rel="noreferrer">{{ app()->getLocale() === 'ar' ? 'الموقع' : 'Website' }}</a></div>@endif
                    </div>
                </div>
                <div class="card p-5 sm:p-6">
                    <div class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'السوشيال' : 'Social' }}</div>
                    <div class="mt-3 flex flex-wrap gap-2 text-sm">
                        @if ($shop->instagram)<a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ $shop->instagram }}" target="_blank" rel="noreferrer">Instagram</a>@endif
                        @if ($shop->facebook)<a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ $shop->facebook }}" target="_blank" rel="noreferrer">Facebook</a>@endif
                        @if ($shop->tiktok)<a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ $shop->tiktok }}" target="_blank" rel="noreferrer">TikTok</a>@endif
                    </div>
                </div>
            </div>

            @if ($shop->images->count())
                <div class="mt-10">
                    <div class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'معرض الصور' : 'Gallery' }}</div>
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        @foreach ($shop->images as $image)
                            <a class="rounded-xl overflow-hidden bg-gray-100 aspect-[4/3] block dark:bg-secondary-900" href="{{ $image->image_url }}" target="_blank" rel="noreferrer">
                                <img class="w-full h-full object-cover" src="{{ $image->image_url }}" alt="{{ $image->alt ?? '' }}" loading="lazy" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($shop->offers->count())
                <div class="mt-10">
                    <div class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        @foreach ($shop->offers as $offer)
                            <a href="{{ route('offers.show', $offer) }}" class="card p-4 sm:p-5">
                                <div class="text-sm text-primary-700 font-semibold">{{ $offer->discount_text }}</div>
                                <div class="mt-2 font-semibold text-secondary-900 dark:text-secondary-50">{{ $offer->title }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($shop->events->count())
                <div class="mt-10">
                    <div class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        @foreach ($shop->events as $event)
                            <a href="{{ route('events.show', $event) }}" class="card p-4 sm:p-5">
                                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ $event->date_range }}</div>
                                <div class="mt-2 font-semibold text-secondary-900 dark:text-secondary-50">{{ $event->title }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
