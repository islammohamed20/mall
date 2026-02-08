@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? '🎨 ثيمات المناسبات' : '🎨 Seasonal Themes' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar' ? 'فعّل ثيم مناسبة لتغيير شكل الموقع بالكامل مع بانر تهنئة وزخارف متحركة وألوان مميزة.' : 'Activate a seasonal theme to transform the site with greeting banners, animated decorations, and themed colors.' }}
                </p>
            </div>

            @if ($activeKey)
                <form method="POST" action="{{ route('admin.themes.deactivate') }}" class="shrink-0">
                    @csrf
                    <button class="btn btn-sm bg-red-500 text-white hover:bg-red-600 focus:ring-red-500" type="submit">
                        {{ app()->getLocale() === 'ar' ? '⏹ إيقاف الثيم الحالي' : '⏹ Deactivate Current' }}
                    </button>
                </form>
            @endif
        </div>

        @if (session('status'))
            <div class="admin-card p-4 text-green-700 bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-900/30 dark:text-green-200">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="admin-card p-4 text-red-700 bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-900/30 dark:text-red-200">{{ session('error') }}</div>
        @endif

        {{-- Hijri Status --}}
        <div class="admin-card p-4 flex items-center justify-between gap-3">
            <div class="text-sm font-medium text-secondary-700 dark:text-secondary-200">
                {{ app()->getLocale() === 'ar' ? '📅 التاريخ الهجري اليوم' : '📅 Today\'s Hijri Date' }}
            </div>
            <div>
                @if (! $hijriSupported)
                    <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'Intl غير متوفر' : 'Intl not available' }}</span>
                @elseif ($hijriToday)
                    <span class="badge badge-info text-sm">{{ $hijriToday['day'] }}/{{ $hijriToday['month'] }}/{{ $hijriToday['year'] }} {{ app()->getLocale() === 'ar' ? 'هـ' : 'AH' }}</span>
                @else
                    <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'تعذر التحويل' : 'Conversion failed' }}</span>
                @endif
            </div>
        </div>

        {{-- Themes grouped by category --}}
        @foreach ($categories as $catKey => $catLabel)
            @if (!empty($groupedItems[$catKey]))
                <div class="space-y-3">
                    <h2 class="text-lg font-bold text-secondary-900 dark:text-secondary-50">{{ $catLabel }}</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($groupedItems[$catKey] as $item)
                            <div class="admin-card p-5 flex flex-col gap-3 {{ $item['is_active'] ? 'ring-2 ring-primary-500 dark:ring-primary-400' : '' }}">
                                {{-- Theme header --}}
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="text-lg font-bold text-secondary-900 dark:text-secondary-50">{{ $item['name'] }}</div>
                                        <div class="text-xs text-secondary-500 dark:text-secondary-400 mt-0.5">{{ $item['key'] }}</div>
                                    </div>
                                    @if ($item['is_active'])
                                        <span class="badge badge-success shrink-0">{{ app()->getLocale() === 'ar' ? '✅ مُفعّل' : '✅ Active' }}</span>
                                    @endif
                                </div>

                                {{-- Season info --}}
                                <div class="text-sm space-y-1">
                                    @if ($item['gregorian_range'])
                                        <div class="text-secondary-600 dark:text-secondary-300">
                                            📆 {{ $item['gregorian_range'] }}
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <span class="badge {{ $item['type'] === 'hijri_range' ? 'badge-warning' : 'badge-info' }}">
                                            {{ $item['type'] === 'hijri_range' ? (app()->getLocale() === 'ar' ? '🌙 هجري' : '🌙 Hijri') : (app()->getLocale() === 'ar' ? '📅 ميلادي' : '📅 Gregorian') }}
                                        </span>
                                        @if ($item['in_season'])
                                            <span class="badge badge-success">{{ app()->getLocale() === 'ar' ? '🟢 موسمه الآن' : '🟢 In season' }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action --}}
                                <div class="mt-auto pt-2">
                                    @if ($item['is_active'])
                                        <form method="POST" action="{{ route('admin.themes.deactivate') }}">
                                            @csrf
                                            <button class="w-full btn btn-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50" type="submit">
                                                {{ app()->getLocale() === 'ar' ? 'إيقاف' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.themes.activate') }}">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $item['key'] }}" />
                                            <button class="w-full btn btn-sm btn-primary" type="submit">
                                                {{ app()->getLocale() === 'ar' ? '🚀 تفعيل' : '🚀 Activate' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Note --}}
        <div class="admin-card p-4 text-sm text-secondary-600 dark:text-secondary-300 space-y-2">
            <p><strong>{{ app()->getLocale() === 'ar' ? 'ملاحظات:' : 'Notes:' }}</strong></p>
            <ul class="list-disc list-inside space-y-1">
                <li>{{ app()->getLocale() === 'ar' ? 'الثيمات لا تتفعل تلقائيًا — الأدمن فقط يفعّل/يوقف.' : 'Themes do not auto-activate — admin manually activates/deactivates.' }}</li>
                <li>{{ app()->getLocale() === 'ar' ? 'ثيم واحد فقط يمكن تفعيله في نفس الوقت.' : 'Only one theme can be active at a time.' }}</li>
                <li>{{ app()->getLocale() === 'ar' ? 'التاريخ الهجري يُحسب تلقائيًا (تقويم أم القرى) لعرض فترة المناسبة بالميلادي.' : 'Hijri dates are auto-calculated (Umm al-Qura calendar) to show Gregorian ranges.' }}</li>
                <li>{{ app()->getLocale() === 'ar' ? 'عند التفعيل: بانر تهنئة + زخارف متحركة + تغيير ألوان الموقع.' : 'When activated: greeting banner + animated decorations + color overrides.' }}</li>
            </ul>
        </div>
    </div>
@endsection
