@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-end justify-between gap-6 flex-col lg:flex-row">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</h1>
                    <p class="mt-2 text-sm sm:text-base text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'اطلع على العروض الحالية والقادمة.' : 'Browse current and upcoming offers.' }}</p>
                </div>
                <div class="flex w-full sm:w-auto gap-2">
                    <a class="flex-1 sm:flex-none text-center px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors dark:border-secondary-700 dark:hover:bg-secondary-900 {{ request('upcoming') ? '' : 'bg-gray-100 dark:bg-secondary-900 font-semibold' }}" href="{{ route('offers.index') }}">
                        {{ app()->getLocale() === 'ar' ? 'الحالية' : 'Current' }}
                    </a>
                    <a class="flex-1 sm:flex-none text-center px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors dark:border-secondary-700 dark:hover:bg-secondary-900 {{ request('upcoming') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}" href="{{ route('offers.index', ['upcoming' => 1]) }}">
                        {{ app()->getLocale() === 'ar' ? 'القادمة' : 'Upcoming' }}
                    </a>
                </div>
            </div>

            <div class="mt-8 sm:mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @forelse ($offers as $offer)
                    <a href="{{ route('offers.show', $offer) }}" class="card overflow-hidden hover:-translate-y-0.5 group">
                        @if ($offer->image_url)
                            <div class="h-32 sm:h-36 bg-gray-100 dark:bg-secondary-900 overflow-hidden relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $offer->image_url }}" alt="{{ $offer->title }}" loading="lazy" />
                                @if ($offer->discount_text)
                                    <div class="absolute top-3 ltr:left-3 rtl:right-3 px-2.5 py-1 rounded-full bg-primary-500 text-white text-xs font-bold shadow-lg">
                                        {{ $offer->discount_text }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-32 sm:h-36 bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-gold-900/30 flex items-center justify-center relative">
                                <svg class="w-12 h-12 text-primary-400 dark:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6Z" />
                                </svg>
                                @if ($offer->discount_text)
                                    <div class="absolute top-3 ltr:left-3 rtl:right-3 px-2.5 py-1 rounded-full bg-primary-500 text-white text-xs font-bold shadow-lg">
                                        {{ $offer->discount_text }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="p-4 sm:p-5">
                            <div class="font-semibold text-sm sm:text-base text-secondary-900 dark:text-secondary-50 line-clamp-2">{{ $offer->title }}</div>
                            <div class="mt-2 flex items-center gap-2 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                </svg>
                                <span>{{ $offer->start_date?->format('Y-m-d') }} → {{ $offer->end_date?->format('Y-m-d') }}</span>
                            </div>
                            @if ($offer->shop)
                                <div class="mt-2 flex items-center gap-2 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    <span class="truncate">{{ $offer->shop->name }}</span>
                                </div>
                            @endif
                            @if ($offer->short)
                                <div class="mt-3 text-xs sm:text-sm text-secondary-700 dark:text-secondary-200 line-clamp-2">{{ $offer->short }}</div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6Z" />
                            </svg>
                            <p class="empty-state-text">{{ app()->getLocale() === 'ar' ? 'لا توجد عروض حالياً.' : 'No offers at the moment.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($offers->hasPages())
                <div class="mt-8 sm:mt-10">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
