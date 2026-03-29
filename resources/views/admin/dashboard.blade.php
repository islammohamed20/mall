@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-white">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</h1>
                <p class="mt-2 text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'نظرة عامة سريعة على إحصائيات المتجر.' : 'Quick overview of your store statistics.' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-secondary-500 dark:text-secondary-400 bg-white dark:bg-secondary-900 px-3 py-1 rounded-full border border-gray-200 dark:border-secondary-700 shadow-sm">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Shops -->
            <div class="admin-card p-5 group hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mb-1">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</div>
                        <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['shops'] }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="admin-card p-5 group hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mb-1">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</div>
                        <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['categories'] }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Offers -->
            <div class="admin-card p-5 group hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mb-1">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</div>
                        <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['offers'] }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div class="admin-card p-5 group hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mb-1">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</div>
                        <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['events'] }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-pink-50 dark:bg-pink-900/20 flex items-center justify-center text-pink-600 dark:text-pink-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="admin-card p-5 group hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mb-1">{{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}</div>
                        <div class="text-2xl font-bold text-secondary-900 dark:text-white">{{ $stats['messages'] }}</div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/20 flex items-center justify-center text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Latest Messages -->
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-secondary-800 flex items-center justify-between bg-white dark:bg-secondary-950">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-900/20 flex items-center justify-center text-teal-600 dark:text-teal-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-secondary-900 dark:text-white">{{ app()->getLocale() === 'ar' ? 'أحدث الرسائل' : 'Latest Messages' }}</h3>
                    </div>
                    <a class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline" href="{{ route('admin.messages.index') }}">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800 bg-white dark:bg-secondary-950">
                    @forelse ($latestMessages as $m)
                        <a class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-secondary-900 transition-colors group" href="{{ route('admin.messages.show', $m) }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center text-secondary-600 dark:text-secondary-400 font-bold text-sm">
                                        {{ substr($m->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-secondary-900 dark:text-secondary-100 group-hover:text-primary-600 transition-colors">{{ $m->name }}</div>
                                        <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $m->email }}</div>
                                    </div>
                                </div>
                                <span class="badge {{ $m->status_color }}">{{ $m->status_label }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-12 flex flex-col items-center justify-center text-secondary-400 dark:text-secondary-500">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>{{ app()->getLocale() === 'ar' ? 'لا توجد رسائل.' : 'No messages.' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-secondary-800 flex items-center justify-between bg-white dark:bg-secondary-950">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 dark:bg-pink-900/20 flex items-center justify-center text-pink-600 dark:text-pink-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-secondary-900 dark:text-white">{{ app()->getLocale() === 'ar' ? 'فعاليات قادمة' : 'Upcoming Events' }}</h3>
                    </div>
                    <a class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline" href="{{ route('admin.events.index') }}">
                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}
                    </a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800 bg-white dark:bg-secondary-950">
                    @forelse ($upcomingEvents as $e)
                        <a href="{{ route('admin.events.edit', $e) }}" class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-secondary-900 transition-colors group">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-lg bg-primary-50 dark:bg-primary-900/20 flex flex-col items-center justify-center text-primary-700 dark:text-primary-400 border border-primary-100 dark:border-primary-900/50 flex-shrink-0 group-hover:scale-105 transition-transform">
                                    <span class="text-xs font-bold uppercase">{{ $e->start_date ? $e->start_date->translatedFormat('M') : 'N/A' }}</span>
                                    <span class="text-lg font-bold leading-none">{{ $e->start_date ? $e->start_date->translatedFormat('d') : '?' }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-secondary-900 dark:text-secondary-100 group-hover:text-primary-600 transition-colors truncate">{{ $e->title }}</div>
                                    <div class="mt-1 text-sm text-secondary-500 dark:text-secondary-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $e->date_range }}
                                    </div>
                                </div>
                                <div class="text-secondary-400 group-hover:text-primary-500 transition-colors">
                                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-12 flex flex-col items-center justify-center text-secondary-400 dark:text-secondary-500">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>{{ app()->getLocale() === 'ar' ? 'لا توجد فعاليات قادمة.' : 'No upcoming events.' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
