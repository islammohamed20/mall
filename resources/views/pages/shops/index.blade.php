@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</h1>
                    <p class="mt-2 text-sm sm:text-base text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'ابحث وفلتر حسب التصنيف والدور.' : 'Search and filter by category and floor.' }}</p>
                </div>
                <form class="w-full lg:w-auto flex flex-col sm:flex-row gap-3" method="GET" action="{{ route('shops.index') }}">
                    <input class="form-input w-full sm:w-72" name="q" value="{{ request('q') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'بحث باسم المحل' : 'Search by shop name' }}" />
                    <select class="form-input w-full sm:w-56" name="category">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'كل التصنيفات' : 'All categories' }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-input w-full sm:w-56" name="floor">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'كل الأدوار' : 'All floors' }}</option>
                        @foreach ($floors as $floor)
                            <option value="{{ $floor->id }}" @selected((string) request('floor') === (string) $floor->id)>{{ $floor->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn-primary w-full sm:w-auto" type="submit">{{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}</button>
                </form>
            </div>

            <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($shops as $shop)
                    <a href="{{ route('shop.direct', $shop) }}" class="card overflow-hidden hover:-translate-y-0.5">
                        <div class="h-24 sm:h-28 relative overflow-hidden flex items-center justify-between px-4">
                            @if ($shop->cover_url)
                                <img class="absolute inset-0 w-full h-full object-cover" src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" loading="lazy" />
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-200/70 to-gold-200/70 dark:from-primary-900/50 dark:to-yellow-900/50"></div>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-200 to-gold-200"></div>
                            @endif
                            <div class="relative z-10 flex items-center gap-3">
                                @if ($shop->logo_url)
                                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-white/80 flex items-center justify-center overflow-hidden">
                                        <img class="w-full h-full object-contain" src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" loading="lazy" />
                                    </div>
                                @elseif ($shop->category)
                                    <div class="h-10 w-10 rounded-full bg-white/80 flex items-center justify-center text-lg">
                                        {{ $shop->category->icon_symbol ?? '🏬' }}
                                    </div>
                                @endif
                            </div>
                            @if ($shop->is_open_now)
                                <span class="px-2 py-1 rounded-full bg-green-100 text-[11px] sm:text-xs font-semibold text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                    {{ app()->getLocale() === 'ar' ? 'مفتوح الآن' : 'Open now' }}
                                </span>
                            @endif
                        </div>
                        <div class="p-4 sm:p-5">
                            <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 text-sm sm:text-base leading-tight">{{ $shop->name }}</h3>
                            <p class="mt-1.5 text-xs sm:text-sm text-secondary-500 dark:text-secondary-400">
                                {{ $shop->category?->name }}{{ $shop->floorRelation ? ' • ' . $shop->floorRelation->name : '' }}
                            </p>
                            @if ($shop->description)
                                <p class="mt-2.5 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300 line-clamp-2 leading-relaxed">{{ $shop->description }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-4xl mb-3">🔍</div>
                        <p class="text-base text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'لا توجد نتائج.' : 'No results found.' }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $shops->links() }}
            </div>
        </div>
    </section>
@endsection
