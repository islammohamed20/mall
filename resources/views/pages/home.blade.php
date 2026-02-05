@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 hero-overlay"></div>
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
                                    <a class="btn-white w-full sm:w-auto" href="{{ $slide->cta_link_2 ?: route('offers.index') }}">
                                        {{ $slide->cta_text2 ?: (app()->getLocale() === 'ar' ? 'عروض اليوم' : "Today's Offers") }}
                                    </a>
                                    <a class="btn-white w-full sm:w-auto" href="{{ route('units.index') }}">
                                        {{ app()->getLocale() === 'ar' ? 'احجز وحدتك' : 'Book Your Unit' }}
                                    </a>
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
                            <a class="btn-white w-full sm:w-auto" href="{{ route('offers.index') }}">{{ app()->getLocale() === 'ar' ? 'عروض اليوم' : "Today's Offers" }}</a>
                            <a class="btn-white w-full sm:w-auto" href="{{ route('units.index') }}">{{ app()->getLocale() === 'ar' ? 'احجز وحدتك' : 'Book Your Unit' }}</a>
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
                        <div class="h-32 bg-gradient-to-br from-primary-200 to-gold-200"></div>
                        <div class="p-4 sm:p-5">
                            <div class="font-semibold text-secondary-900 dark:text-secondary-50">{{ $shop->name }}</div>
                            <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">
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
