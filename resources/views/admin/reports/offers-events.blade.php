@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'العروض والفعاليات' : 'Offers & Events' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير العروض والفعاليات' : 'Offers & Events Report' }}
                </h1>
            </div>
        </div>

        {{-- Offers Summary --}}
        <div class="admin-card p-5">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'إحصائيات العروض' : 'Offers Statistics' }}
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-secondary-900 rounded-lg">
                    <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي العروض' : 'Total Offers' }}</p>
                    <p class="mt-1 text-2xl font-bold">{{ $offersSummary['total'] }}</p>
                </div>
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-green-600 text-sm">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ $offersSummary['active'] }}</p>
                </div>
                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-red-600 text-sm">{{ app()->getLocale() === 'ar' ? 'منتهي' : 'Expired' }}</p>
                    <p class="mt-1 text-2xl font-bold text-red-600">{{ $offersSummary['expired'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-blue-600 text-sm">{{ app()->getLocale() === 'ar' ? 'خاص بمحل' : 'Shop Specific' }}</p>
                    <p class="mt-1 text-2xl font-bold text-blue-600">{{ $offersSummary['by_shop'] }}</p>
                </div>
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <p class="text-purple-600 text-sm">{{ app()->getLocale() === 'ar' ? 'عام' : 'General' }}</p>
                    <p class="mt-1 text-2xl font-bold text-purple-600">{{ $offersSummary['general'] }}</p>
                </div>
            </div>
        </div>

        {{-- Events Summary --}}
        <div class="admin-card p-5">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'إحصائيات الفعاليات' : 'Events Statistics' }}
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-secondary-900 rounded-lg">
                    <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الفعاليات' : 'Total Events' }}</p>
                    <p class="mt-1 text-2xl font-bold">{{ $eventsSummary['total'] }}</p>
                </div>
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-green-600 text-sm">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ $eventsSummary['active'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-blue-600 text-sm">{{ app()->getLocale() === 'ar' ? 'قادم' : 'Upcoming' }}</p>
                    <p class="mt-1 text-2xl font-bold text-blue-600">{{ $eventsSummary['upcoming'] }}</p>
                </div>
                <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <p class="text-orange-600 text-sm">{{ app()->getLocale() === 'ar' ? 'جاري الآن' : 'Ongoing' }}</p>
                    <p class="mt-1 text-2xl font-bold text-orange-600">{{ $eventsSummary['ongoing'] }}</p>
                </div>
                <div class="p-4 bg-gray-100 dark:bg-secondary-800 rounded-lg">
                    <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'سابق' : 'Past' }}</p>
                    <p class="mt-1 text-2xl font-bold">{{ $eventsSummary['past'] }}</p>
                </div>
            </div>
        </div>

        {{-- Active Offers & Upcoming Events --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Active Offers --}}
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800 flex items-center justify-between">
                    <h3 class="font-semibold">{{ app()->getLocale() === 'ar' ? 'العروض النشطة' : 'Active Offers' }}</h3>
                    <a href="{{ route('admin.offers.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800 max-h-96 overflow-y-auto">
                    @forelse ($activeOffers as $offer)
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium">{{ app()->getLocale() === 'ar' ? $offer->title_ar : $offer->title_en }}</p>
                                    @if($offer->shop)
                                        <p class="text-sm text-secondary-500 mt-1">
                                            {{ app()->getLocale() === 'ar' ? $offer->shop->name_ar : $offer->shop->name_en }}
                                        </p>
                                    @else
                                        <p class="text-sm text-purple-600 mt-1">
                                            {{ app()->getLocale() === 'ar' ? 'عرض عام' : 'General Offer' }}
                                        </p>
                                    @endif
                                </div>
                                @if($offer->discount_percentage)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded text-sm font-semibold">
                                        {{ $offer->discount_percentage }}%
                                    </span>
                                @endif
                            </div>
                            @if($offer->end_date)
                                <p class="text-xs text-secondary-400 mt-2">
                                    {{ app()->getLocale() === 'ar' ? 'ينتهي:' : 'Ends:' }} {{ $offer->end_date->format('Y-m-d') }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-secondary-500">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد عروض نشطة' : 'No active offers' }}
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Events --}}
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800 flex items-center justify-between">
                    <h3 class="font-semibold">{{ app()->getLocale() === 'ar' ? 'الفعاليات القادمة' : 'Upcoming Events' }}</h3>
                    <a href="{{ route('admin.events.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800 max-h-96 overflow-y-auto">
                    @forelse ($upcomingEvents as $event)
                        <div class="px-5 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex flex-col items-center justify-center text-indigo-600">
                                    <span class="text-xs font-semibold">{{ $event->start_date->format('M') }}</span>
                                    <span class="text-lg font-bold leading-none">{{ $event->start_date->format('d') }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium">{{ app()->getLocale() === 'ar' ? $event->title_ar : $event->title_en }}</p>
                                    <p class="text-sm text-secondary-500 mt-1">
                                        {{ $event->start_date->format('Y-m-d') }}
                                        @if($event->end_date)
                                            - {{ $event->end_date->format('Y-m-d') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-secondary-500">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد فعاليات قادمة' : 'No upcoming events' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Offers by Shop --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'العروض حسب المحل' : 'Offers by Shop' }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'العروض النشطة' : 'Active Offers' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($offersByShop as $index => $shop)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-medium">{{ app()->getLocale() === 'ar' ? $shop->name_ar : $shop->name_en }}</td>
                                <td>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded">
                                        {{ $shop->offers_count }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد عروض للمحلات' : 'No shop offers' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
