@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'وحدات للبيع والإيجار' : 'Units for Sale & Rent' }}
                </h1>
                <p class="mt-3 text-secondary-600 dark:text-secondary-300 max-w-xl mx-auto">
                    {{ app()->getLocale() === 'ar' ? 'استثمر في أفضل المواقع التجارية داخل المول' : 'Invest in premium commercial spaces inside the mall' }}
                </p>
            </div>

            @if ($units->count())
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($units as $unit)
                        <a href="{{ route('units.show', $unit) }}" class="card overflow-hidden hover:-translate-y-1 transition-transform duration-200 group">
                            {{-- Image --}}
                            <div class="relative aspect-[4/3] bg-gray-100 dark:bg-secondary-900">
                                @if ($unit->image_url)
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $unit->image_url }}" alt="{{ $unit->title }}" loading="lazy" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-yellow-900/30 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-500',
                                        'reserved'  => 'bg-yellow-500',
                                        'sold'      => 'bg-red-500',
                                        'rented'    => 'bg-blue-500',
                                    ];
                                @endphp
                                <div class="absolute top-3 start-3">
                                    <span class="px-3 py-1 rounded-full text-white text-xs font-bold {{ $statusColors[$unit->status] ?? 'bg-gray-500' }}">{{ $unit->status_label }}</span>
                                </div>

                                {{-- Price Type Badge --}}
                                <div class="absolute top-3 end-3">
                                    <span class="px-3 py-1 rounded-full bg-white/90 dark:bg-secondary-900/90 text-secondary-900 dark:text-secondary-100 text-xs font-bold shadow">{{ $unit->price_type_label }}</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-secondary-500 dark:text-secondary-400">
                                    <span>{{ $unit->type_label }}</span>
                                    @if ($unit->floor)
                                        <span>•</span>
                                        <span>{{ $unit->floor->name }}</span>
                                    @endif
                                    @if ($unit->unit_number)
                                        <span>•</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'وحدة' : 'Unit' }} {{ $unit->unit_number }}</span>
                                    @endif
                                </div>

                                <h3 class="mt-2 text-lg font-bold text-secondary-900 dark:text-secondary-50 truncate">{{ $unit->title }}</h3>

                                @if ($unit->area)
                                    <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">
                                        <span>{{ number_format($unit->area) }} {{ app()->getLocale() === 'ar' ? 'م²' : 'sqm' }}</span>
                                    </div>
                                @endif

                                @if ($unit->price)
                                    <div class="mt-3 text-xl font-bold text-primary-600 dark:text-primary-400">
                                        {{ number_format($unit->price, 0) }} {{ $unit->currency }}
                                        @if ($unit->price_type === 'rent')
                                            <span class="text-sm font-normal text-secondary-500">/ {{ app()->getLocale() === 'ar' ? 'شهر' : 'month' }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-3 text-lg font-semibold text-secondary-500">
                                        {{ app()->getLocale() === 'ar' ? 'اتصل للسعر' : 'Contact for price' }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">{{ $units->links() }}</div>
            @else
                <div class="mt-10 text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-secondary-300 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                    <p class="mt-4 text-secondary-500 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'لا توجد وحدات معروضة حالياً' : 'No units available at the moment' }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
