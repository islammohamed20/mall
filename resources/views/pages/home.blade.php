@extends('layouts.app')

@section('content')
    @php
        $showOffers = $publicSections['offers'] ?? true;
        $showUnits = $publicSections['units'] ?? true;
        $seasonThemeKeyLocal = $seasonThemeKey ?? app(\App\Services\SeasonThemeService::class)->activeKey();
    @endphp

    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 hero-overlay"></div>
            @if ($seasonThemeKeyLocal === 'ramadan')
                <div class="ramadan-hero-decor absolute inset-0 pointer-events-none overflow-hidden">
                    <svg class="ramadan-float absolute top-8 sm:top-10 {{ app()->getLocale() === 'ar' ? 'right-6 sm:right-10' : 'left-6 sm:left-10' }} w-14 h-14 sm:w-20 sm:h-20 text-emerald-200/50" viewBox="0 0 64 64" fill="none" aria-hidden="true" style="animation-delay: -0.8s;">
                        <path fill="currentColor" d="M41.6 12.1c-7.9 2.2-13.7 9.5-13.7 18 0 10.3 8.3 18.6 18.6 18.6 3.3 0 6.5-.9 9.3-2.5-3.2 4.7-8.6 7.8-14.7 7.8-9.8 0-17.7-7.9-17.7-17.7 0-8.7 6.3-16 14.6-17.5-1.4-1.1-3-2.1-4.8-2.7 2.8-1.5 5.9-2.3 9.1-2z"/>
                    </svg>

                    <svg class="ramadan-drift absolute top-14 sm:top-16 {{ app()->getLocale() === 'ar' ? 'left-6 sm:left-12' : 'right-6 sm:right-12' }} w-12 h-12 sm:w-16 sm:h-16 text-amber-200/45" viewBox="0 0 64 64" fill="none" aria-hidden="true" style="animation-delay: -2.3s;">
                        <path fill="currentColor" d="M32 10c8.8 0 16 7.2 16 16 0 12-10.8 21.8-16 28-5.2-6.2-16-16-16-28 0-8.8 7.2-16 16-16zm0 7a9 9 0 100 18 9 9 0 000-18z"/>
                        <path fill="currentColor" d="M24 28h16v2H24zm2 6h12v2H26z" opacity=".55"/>
                    </svg>

                    <svg class="ramadan-float hidden sm:block absolute bottom-12 {{ app()->getLocale() === 'ar' ? 'right-16' : 'left-16' }} w-14 h-14 text-emerald-100/35" viewBox="0 0 64 64" fill="none" aria-hidden="true" style="animation-delay: -1.6s;">
                        <path fill="currentColor" d="M41.6 12.1c-7.9 2.2-13.7 9.5-13.7 18 0 10.3 8.3 18.6 18.6 18.6 3.3 0 6.5-.9 9.3-2.5-3.2 4.7-8.6 7.8-14.7 7.8-9.8 0-17.7-7.9-17.7-17.7 0-8.7 6.3-16 14.6-17.5-1.4-1.1-3-2.1-4.8-2.7 2.8-1.5 5.9-2.3 9.1-2z"/>
                    </svg>

                    <span class="ramadan-twinkle absolute top-24 left-1/2 w-2 h-2 rounded-full bg-white/60" style="animation-delay: -0.4s;"></span>
                    <span class="ramadan-twinkle absolute top-12 left-1/3 w-1.5 h-1.5 rounded-full bg-white/55" style="animation-delay: -1.1s;"></span>
                    <span class="ramadan-twinkle hidden sm:block absolute top-40 right-1/3 w-1.5 h-1.5 rounded-full bg-white/50" style="animation-delay: -1.9s;"></span>
                    <span class="ramadan-twinkle hidden sm:block absolute bottom-28 left-1/4 w-2 h-2 rounded-full bg-white/55" style="animation-delay: -2.6s;"></span>
                    <span class="ramadan-twinkle absolute bottom-16 right-1/4 w-1.5 h-1.5 rounded-full bg-white/45" style="animation-delay: -3.2s;"></span>
                </div>
            @endif
        </div>
        <div class="relative max-w-7xl mx-auto px-4 lg:px-8 py-16 lg:py-24" x-data="{ i: 0 }" x-init="setInterval(() => { i = (i + 1) % {{ max(1, $sliders->count()) }} }, 7000)">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="text-white">
                    @if ($sliders->count())
                        @foreach ($sliders as $index => $slide)
                            <div x-show="i === {{ $index }}" x-cloak>
                                <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
                                    {{ $slide->title ?? (app()->getLocale() === 'ar' ? 'وجهتك الأولى للتسوق والترفيه في قلب المدينة' : 'Your first destination for shopping & entertainment') }}
                                </h1>
                                <p class="mt-4 text-white/80 text-lg">
                                    {{ $slide->subtitle ?? (app()->getLocale() === 'ar' ? 'اكتشف أحدث العروض وأفضل العلامات والخدمات المتكاملة.' : 'Explore the latest offers, top brands, and complete services.') }}
                                </p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a class="btn-gold w-full sm:w-auto" href="{{ $slide->cta_link ?: route('shops.index') }}">
                                        {{ $slide->cta_text ?: (app()->getLocale() === 'ar' ? 'استكشف المحلات' : 'Explore Shops') }}
                                    </a>
                                    @if ($slide->cta_link_2)
                                        <a class="btn-white w-full sm:w-auto" href="{{ $slide->cta_link_2 }}">
                                            {{ $slide->cta_text2 ?: (app()->getLocale() === 'ar' ? 'عروض اليوم' : "Today's Offers") }}
                                        </a>
                                    @elseif ($showOffers)
                                        <a class="btn-white w-full sm:w-auto" href="{{ route('offers.index') }}">
                                            {{ $slide->cta_text2 ?: (app()->getLocale() === 'ar' ? 'عروض اليوم' : "Today's Offers") }}
                                        </a>
                                    @endif

                                    @if ($showUnits)
                                        <a class="btn-white w-full sm:w-auto" href="{{ route('units.index') }}">
                                            {{ app()->getLocale() === 'ar' ? 'احجز وحدتك' : 'Book Your Unit' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
                            {{ app()->getLocale() === 'ar' ? 'وجهتك الأولى للتسوق والترفيه في قلب المدينة' : 'Your first destination for shopping & entertainment' }}
                        </h1>
                        <p class="mt-4 text-white/80 text-lg">
                            {{ app()->getLocale() === 'ar' ? 'اكتشف أحدث العروض وأفضل العلامات والخدمات المتكاملة.' : 'Explore the latest offers, top brands, and complete services.' }}
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a class="btn-gold w-full sm:w-auto" href="{{ route('shops.index') }}">{{ app()->getLocale() === 'ar' ? 'استكشف المحلات' : 'Explore Shops' }}</a>
                            @if ($showOffers)
                                <a class="btn-white w-full sm:w-auto" href="{{ route('offers.index') }}">{{ app()->getLocale() === 'ar' ? 'عروض اليوم' : "Today's Offers" }}</a>
                            @endif
                            @if ($showUnits)
                                <a class="btn-white w-full sm:w-auto" href="{{ route('units.index') }}">{{ app()->getLocale() === 'ar' ? 'احجز وحدتك' : 'Book Your Unit' }}</a>
                            @endif
                        </div>
                    @endif
                </div>

                @php
                    $availableUnitsCount = (string) \App\Models\Unit::query()
                        ->where('is_active', true)
                        ->where('status', 'available')
                        ->count();

                    $statsItems = [
                        [
                            'value' => (string) \App\Models\Setting::getValue('mall_stats_shops', (string) config('mall.stats.shops')),
                            'label_ar' => 'محل',
                            'label_en' => 'Shops',
                        ],
                        [
                            'value' => (string) \App\Models\Setting::getValue('mall_stats_restaurants', (string) config('mall.stats.restaurants')),
                            'label_ar' => 'مطعم',
                            'label_en' => 'Restaurants',
                        ],
                        [
                            'value' => (string) \App\Models\Setting::getValue('mall_stats_parking_spots', (string) config('mall.stats.parking_spots')),
                            'label_ar' => 'موقف',
                            'label_en' => 'Parking',
                        ],
                        [
                            'value' => (string) \App\Models\Setting::getValue('mall_stats_monthly_visitors', (string) config('mall.stats.monthly_visitors')),
                            'label_ar' => 'زائر شهرياً',
                            'label_en' => 'Monthly Visitors',
                        ],
                        [
                            'value' => $availableUnitsCount,
                            'label_ar' => 'وحدة متاحة',
                            'label_en' => 'Available Units',
                        ],
                    ];

                    $visibleStats = collect($statsItems)->filter(function ($item) {
                        $val = (string) ($item['value'] ?? '');
                        return $val !== '' && $val !== '0';
                    });
                @endphp
                @if ($visibleStats->count())
                <div class="glass rounded-2xl p-6 lg:p-8 text-white">
                    <div class="text-sm text-white/80">{{ app()->getLocale() === 'ar' ? 'أرقام سريعة' : 'Quick Stats' }}</div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        @foreach ($visibleStats as $stat)
                            <div class="rounded-xl bg-white/10 p-4">
                                <div class="text-2xl font-bold">{{ $stat['value'] }}</div>
                                <div class="text-sm text-white/80">{{ app()->getLocale() === 'ar' ? $stat['label_ar'] : $stat['label_en'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'عن المول' : 'About the Mall' }}</h2>
                    <p class="text-sm sm:text-base text-secondary-700 dark:text-secondary-200">
                        {{ app()->getLocale() === 'ar'
                            ? 'مول وسط البلد يجمع بين التسوق والترفيه والخدمات في تجربة عائلية راقية.'
                            : 'West Elbalad Mall brings shopping, entertainment, and services together in an elegant family experience.' }}
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="btn-outline w-full sm:w-auto" href="{{ route('about') }}">{{ app()->getLocale() === 'ar' ? 'اقرأ المزيد' : 'Learn More' }}</a>
                        <a class="btn-secondary w-full sm:w-auto" href="{{ route('contact.show') }}">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</a>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-primary-100 via-white to-gold-100 p-10 dark:from-primary-900/30 dark:via-secondary-950 dark:to-gold-900/20">
                    <div class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'اكتشف تجربة تسوق مختلفة' : 'Discover a different shopping experience' }}</div>
                    <div class="mt-3 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'أجواء أنيقة وخدمات متكاملة لكل أفراد الأسرة.' : 'Elegant vibes and complete services for the whole family.' }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'تصنيفات المحلات' : 'Shop Categories' }}</h2>
                    <p class="text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'تصفّح حسب التصنيف.' : 'Browse by category.' }}</p>
                </div>
                <a href="{{ route('shops.index') }}" class="text-sm text-primary-700 hover:text-primary-800">{{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}</a>
            </div>
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('shops.index', ['category' => $category->id]) }}" class="card p-4 sm:p-5 hover:-translate-y-0.5">
                        <div class="h-10 w-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold dark:bg-primary-900/30 dark:text-primary-200">
                            {{ mb_substr($category->name, 0, 1) }}
                        </div>
                        <div class="mt-4 font-semibold text-secondary-900 dark:text-secondary-50">{{ $category->name }}</div>
                        <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'استكشف المحلات' : 'Explore shops' }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'محلات مميزة' : 'Featured Shops' }}</h2>
                    <p class="text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'اختياراتنا لهذا الأسبوع.' : 'Our picks this week.' }}</p>
                </div>
                <a href="{{ route('shops.index') }}" class="text-sm text-primary-700 hover:text-primary-800">{{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}</a>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredShops as $shop)
                    <a href="{{ route('shop.direct', $shop) }}" class="card overflow-hidden hover:-translate-y-0.5">
                        <div class="relative h-32 overflow-visible">
                            <div class="absolute inset-0 bg-gray-100 dark:bg-secondary-900 overflow-hidden z-0">
                                @if ($shop->cover_url)
                                    <img class="w-full h-full object-cover" src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" loading="lazy" />
                                @else
                                    <div class="h-32 bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-gold-900/30"></div>
                                @endif
                            </div>
                            <div class="absolute -bottom-6 z-10 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                                @if ($shop->logo_url)
                                    <div class="h-14 w-14 rounded-2xl bg-white dark:bg-secondary-950 shadow-lg ring-1 ring-black/5 dark:ring-white/10 overflow-hidden flex items-center justify-center">
                                        <img class="w-full h-full object-contain p-2" src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" loading="lazy" />
                                    </div>
                                @else
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-500 to-gold-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                        {{ mb_substr($shop->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 sm:p-5 pt-10 sm:pt-11">
                            <div class="font-semibold text-secondary-900 dark:text-secondary-50 truncate">{{ $shop->name }}</div>
                            <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300 truncate">
                                {{ $shop->category?->name }}{{ $shop->floorRelation ? ' • ' . $shop->floorRelation->name : '' }}
                            </div>
                            @if ($shop->description)
                                <div class="mt-3 text-sm text-secondary-700 dark:text-secondary-200 line-clamp-2">{{ $shop->description }}</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'عروض حالية' : 'Current Offers' }}</h2>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($currentOffers as $offer)
                            <a href="{{ route('offers.show', $offer) }}" class="card p-4 sm:p-5">
                                <div class="text-sm text-primary-700 font-semibold">{{ $offer->discount_text }}</div>
                                <div class="mt-2 font-semibold text-secondary-900 dark:text-secondary-50">{{ $offer->title }}</div>
                                <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ $offer->start_date?->format('Y-m-d') }} → {{ $offer->end_date?->format('Y-m-d') }}</div>
                                @if ($offer->short)
                                    <div class="mt-3 text-sm text-secondary-700 dark:text-secondary-200 line-clamp-2">{{ $offer->short }}</div>
                                @endif
                            </a>
                        @empty
                            <div class="text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'لا توجد عروض حالياً.' : 'No current offers.' }}</div>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'فعاليات' : 'Events' }}</h2>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($currentEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="card p-4 sm:p-5">
                                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ $event->date_range }}</div>
                                <div class="mt-2 font-semibold text-secondary-900 dark:text-secondary-50">{{ $event->title }}</div>
                                @if ($event->location)
                                    <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ $event->location }}</div>
                                @endif
                                @if ($event->short)
                                    <div class="mt-3 text-sm text-secondary-700 dark:text-secondary-200 line-clamp-2">{{ $event->short }}</div>
                                @endif
                            </a>
                        @empty
                            <div class="text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'لا توجد فعاليات حالياً.' : 'No current events.' }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'الخدمات والمرافق' : 'Facilities & Services' }}</h2>
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($facilities as $facility)
                    <div class="card p-4 sm:p-5">
                        <div class="h-10 w-10 rounded-xl bg-secondary-100 flex items-center justify-center text-secondary-800 font-bold dark:bg-secondary-900 dark:text-secondary-100">
                            {{ mb_substr($facility->name, 0, 1) }}
                        </div>
                        <div class="mt-4 font-semibold text-secondary-900 dark:text-secondary-50">{{ $facility->name }}</div>
                        @if ($facility->short)
                            <div class="mt-1 text-sm text-secondary-700 dark:text-secondary-200">{{ $facility->short }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="card overflow-hidden">
                <div class="p-5 sm:p-6">
                    <h2 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الموقع وساعات العمل' : 'Location & Working Hours' }}</h2>
                    <div class="mt-4 text-secondary-700 dark:text-secondary-200">
                        <div>{{ \App\Models\Setting::getValue('mall_contact_address', app()->getLocale() === 'ar' ? config('mall.contact.address_ar') : config('mall.contact.address_en')) }}</div>
                        <div class="mt-2">{{ \App\Models\Setting::getValue('mall_working_hours', app()->getLocale() === 'ar' ? config('mall.working_hours.ar') : config('mall.working_hours.en')) }}</div>
                        <div class="mt-2">{{ \App\Models\Setting::getValue('mall_contact_phone', config('mall.contact.phone')) }}</div>
                    </div>
                </div>
                <div class="h-80 bg-gray-100 dark:bg-secondary-900">
                    <iframe class="w-full h-full" src="{{ \App\Models\Setting::getValue('mall_map_embed_url', config('mall.map.embed_url')) }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="card p-6">
                <h2 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تواصل سريع' : 'Quick Contact' }}</h2>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'أرسل لنا رسالة وسنرد عليك قريباً.' : "Send us a message and we'll get back to you." }}</p>
                <form class="mt-6 space-y-4" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                        <input class="form-input" name="name" value="{{ old('name') }}" required />
                        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                            <input class="form-input" name="email" value="{{ old('email') }}" required />
                            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</label>
                            <input class="form-input" name="phone" value="{{ old('phone') }}" />
                            @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</label>
                        <input class="form-input" name="subject" value="{{ old('subject') }}" />
                        @error('subject') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الرسالة' : 'Message' }}</label>
                        <textarea class="form-input" rows="4" name="message" required>{{ old('message') }}</textarea>
                        @error('message') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn-primary w-full" type="submit">{{ app()->getLocale() === 'ar' ? 'إرسال' : 'Send' }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
