@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نظرة عامة سريعة على المحتوى.' : 'Quick overview of your content.' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</div>
                <div class="mt-2 text-2xl font-bold">{{ $stats['shops'] }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</div>
                <div class="mt-2 text-2xl font-bold">{{ $stats['categories'] }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</div>
                <div class="mt-2 text-2xl font-bold">{{ $stats['offers'] }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</div>
                <div class="mt-2 text-2xl font-bold">{{ $stats['events'] }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}</div>
                <div class="mt-2 text-2xl font-bold">{{ $stats['messages'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800 flex items-center justify-between">
                    <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'أحدث الرسائل' : 'Latest Messages' }}</div>
                    <a class="text-sm text-primary-700 hover:text-primary-800" href="{{ route('admin.messages.index') }}">{{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @forelse ($latestMessages as $m)
                        <a class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-secondary-900" href="{{ route('admin.messages.show', $m) }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $m->name }}</div>
                                <span class="badge {{ $m->status_color }}">{{ $m->status_label }}</span>
                            </div>
                            <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ $m->email }}</div>
                        </a>
                    @empty
                        <div class="px-5 py-6 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'لا توجد رسائل.' : 'No messages.' }}</div>
                    @endforelse
                </div>
            </div>

            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-800 flex items-center justify-between">
                    <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'فعاليات قادمة' : 'Upcoming Events' }}</div>
                    <a class="text-sm text-primary-700 hover:text-primary-800" href="{{ route('events.index', ['upcoming' => 1]) }}">{{ app()->getLocale() === 'ar' ? 'عرض بالموقع' : 'View on website' }}</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @forelse ($upcomingEvents as $e)
                        <div class="px-5 py-4">
                            <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $e->title }}</div>
                            <div class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ $e->date_range }}</div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'لا توجد فعاليات قادمة.' : 'No upcoming events.' }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
