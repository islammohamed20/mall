@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-4xl mx-auto px-4 lg:px-8">
            <div class="text-sm text-secondary-600 dark:text-secondary-300">
                <a class="hover:text-primary-700" href="{{ route('events.index') }}">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</a>
            </div>
            <h1 class="mt-2 text-4xl font-bold text-secondary-900 dark:text-secondary-50">{{ $event->title }}</h1>
            <div class="mt-3 text-secondary-700 dark:text-secondary-200 flex flex-wrap gap-2">
                <span class="badge badge-info">{{ $event->date_range }}</span>
                @if ($event->location)
                    <span class="badge badge-warning">{{ $event->location }}</span>
                @endif
                @if ($event->shop)
                    <a class="badge badge-success" href="{{ route('shop.direct', $event->shop) }}">{{ $event->shop->name }}</a>
                @endif
            </div>

            @if ($event->short)
                <p class="mt-6 text-lg text-secondary-700 dark:text-secondary-200">{{ $event->short }}</p>
            @endif

            @if ($event->content)
                <div class="mt-8 card p-6 lg:p-10">
                    <div class="text-secondary-700 dark:text-secondary-200 whitespace-pre-line">{{ $event->content }}</div>
                </div>
            @endif
        </div>
    </section>
@endsection
