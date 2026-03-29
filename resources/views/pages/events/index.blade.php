@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-end justify-between gap-6 flex-col lg:flex-row">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</h1>
                    <p class="mt-2 text-sm sm:text-base text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'فعاليات حالية وقادمة.' : 'Current and upcoming events.' }}</p>
                </div>
                <div class="flex w-full sm:w-auto gap-2">
                    <a class="flex-1 sm:flex-none text-center px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors dark:border-secondary-700 dark:hover:bg-secondary-900 {{ request('upcoming') ? '' : 'bg-gray-100 dark:bg-secondary-900 font-semibold' }}" href="{{ route('events.index') }}">
                        {{ app()->getLocale() === 'ar' ? 'الحالية' : 'Current' }}
                    </a>
                    <a class="flex-1 sm:flex-none text-center px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors dark:border-secondary-700 dark:hover:bg-secondary-900 {{ request('upcoming') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}" href="{{ route('events.index', ['upcoming' => 1]) }}">
                        {{ app()->getLocale() === 'ar' ? 'القادمة' : 'Upcoming' }}
                    </a>
                </div>
            </div>

            <div class="mt-8 sm:mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @forelse ($events as $event)
                    <a href="{{ route('events.show', $event) }}" class="card overflow-hidden hover:-translate-y-0.5 group">
                        @if ($event->image_url)
                            <div class="h-32 sm:h-36 bg-gray-100 dark:bg-secondary-900 overflow-hidden">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $event->image_url }}" alt="{{ $event->title }}" loading="lazy" />
                            </div>
                        @else
                            <div class="h-32 sm:h-36 bg-gradient-to-br from-secondary-200 to-primary-200 dark:from-secondary-800 dark:to-primary-900/40 flex items-center justify-center">
                                <svg class="w-12 h-12 text-secondary-400 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4 sm:p-5">
                            <div class="flex items-center gap-2 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                                </svg>
                                <span>{{ $event->date_range }}</span>
                            </div>
                            <div class="mt-2 font-semibold text-sm sm:text-base text-secondary-900 dark:text-secondary-50 line-clamp-2">{{ $event->title }}</div>
                            @if ($event->location)
                                <div class="mt-2 flex items-center gap-2 text-xs sm:text-sm text-secondary-600 dark:text-secondary-300">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                            @endif
                            @if ($event->short)
                                <div class="mt-3 text-xs sm:text-sm text-secondary-700 dark:text-secondary-200 line-clamp-2">{{ $event->short }}</div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <p class="empty-state-text">{{ app()->getLocale() === 'ar' ? 'لا توجد فعاليات حالياً.' : 'No events at the moment.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($events->hasPages())
                <div class="mt-8 sm:mt-10">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
