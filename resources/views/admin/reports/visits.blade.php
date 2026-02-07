@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'الزيارات' : 'Visits' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير الزيارات' : 'Visits Report' }}
                </h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar' ? 'زيارات الموقع (مع دعم موقع الجهاز عند السماح)' : 'Website visits (with optional browser geolocation when allowed)' }}
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="input-field">
                </div>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
                <a href="{{ route('admin.reports.visits') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الزيارات' : 'Total Visits' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($summary['total_visits'] ?? 0) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'زوار فريدون' : 'Unique Visitors' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($summary['unique_visitors'] ?? 0) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'جلسات فريدة' : 'Unique Sessions' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($summary['unique_sessions'] ?? 0) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'زيارات مع موقع' : 'Visits With Location' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($summary['with_geo'] ?? 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Device breakdown --}}
            <div class="admin-card p-5">
                <h3 class="text-lg font-bold mb-4">{{ app()->getLocale() === 'ar' ? 'الأجهزة' : 'Devices' }}</h3>
                <div class="space-y-2">
                    @forelse($deviceBreakdown as $row)
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-secondary-700 dark:text-secondary-200">
                                {{ $row->device_type ?: (app()->getLocale() === 'ar' ? 'غير معروف' : 'Unknown') }}
                            </div>
                            <div class="text-sm font-semibold">{{ number_format($row->count) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'لا توجد بيانات بعد' : 'No data yet' }}</div>
                    @endforelse
                </div>
            </div>

            {{-- Top pages --}}
            <div class="admin-card p-5">
                <h3 class="text-lg font-bold mb-4">{{ app()->getLocale() === 'ar' ? 'أكثر الصفحات زيارة' : 'Top Pages' }}</h3>
                <div class="space-y-2">
                    @forelse($topPages as $row)
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm text-secondary-700 dark:text-secondary-200 truncate" title="{{ $row->path }}">{{ $row->path }}</div>
                            <div class="text-sm font-semibold shrink-0">{{ number_format($row->count) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'لا توجد بيانات بعد' : 'No data yet' }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Latest visits --}}
        <div class="admin-card p-5 overflow-hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">{{ app()->getLocale() === 'ar' ? 'آخر الزيارات' : 'Latest Visits' }}</h3>
                <div class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'قد تحتاج HTTPS ليعمل تحديد الموقع' : 'Geolocation typically requires HTTPS' }}</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-secondary-500">
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'الوقت' : 'Time' }}</th>
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'المسار' : 'Path' }}</th>
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'الجهاز' : 'Device' }}</th>
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'المنصة' : 'Platform' }}</th>
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'المتصفح' : 'Browser' }}</th>
                            <th class="py-2 pe-4">{{ app()->getLocale() === 'ar' ? 'الموقع' : 'Location' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse($latest as $visit)
                            <tr>
                                <td class="py-2 pe-4 whitespace-nowrap">{{ $visit->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="py-2 pe-4 max-w-[420px] truncate" title="{{ $visit->path }}">{{ $visit->path }}</td>
                                <td class="py-2 pe-4 whitespace-nowrap">{{ $visit->device_type ?? '-' }}</td>
                                <td class="py-2 pe-4 whitespace-nowrap">{{ $visit->platform ?? '-' }}</td>
                                <td class="py-2 pe-4 whitespace-nowrap">{{ $visit->browser ?? '-' }}</td>
                                <td class="py-2 pe-4 whitespace-nowrap">
                                    @if($visit->lat && $visit->lng)
                                        <span class="inline-flex items-center gap-1 text-green-700 dark:text-green-400">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            {{ number_format((float) $visit->lat, 3) }}, {{ number_format((float) $visit->lng, 3) }}
                                        </span>
                                    @else
                                        <span class="text-secondary-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد زيارات مسجلة بعد' : 'No visits recorded yet' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
