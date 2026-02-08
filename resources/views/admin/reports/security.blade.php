@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تقارير الأمان' : 'Security Reports' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'ملخص للأحداث الأمنية (محاولات دخول فاشلة، OTP غير صحيح...).' : 'Summary of security events (failed logins, OTP failures, etc.).' }}</p>
            </div>
        </div>

        <form class="admin-card p-4 grid grid-cols-1 sm:grid-cols-6 gap-3" method="GET" action="{{ route('admin.reports.security') }}">
            <div class="sm:col-span-3">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}</label>
                <input class="form-input" name="q" value="{{ request('q') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'email أو type أو IP' : 'email, type or IP' }}" />
            </div>
            <div class="sm:col-span-1">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                <input class="form-input" type="date" name="from" value="{{ request('from') }}" />
            </div>
            <div class="sm:col-span-1">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                <input class="form-input" type="date" name="to" value="{{ request('to') }}" />
            </div>
            <div class="sm:col-span-1 flex items-end gap-2">
                <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Filter' }}</button>
                <a class="btn-outline" href="{{ route('admin.reports.security') }}">{{ app()->getLocale() === 'ar' ? 'مسح' : 'Reset' }}</a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'محاولات الدخول الفاشلة' : 'Failed logins' }}</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['login_failed'] ?? 0 }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'OTP غير صحيح' : 'OTP verify failed' }}</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['otp_verify_failed'] ?? 0 }}</div>
            </div>
            <div class="admin-card p-4">
                <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'تسجيل دخول أدمن' : 'Admin logins' }}</div>
                <div class="mt-1 text-2xl font-bold">{{ $summary['admin_login'] ?? 0 }}</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-secondary-900/50 text-secondary-600 dark:text-secondary-300">
                        <tr>
                            <th class="text-start px-4 py-3">ID</th>
                            <th class="text-start px-4 py-3">Type</th>
                            <th class="text-start px-4 py-3">Email</th>
                            <th class="text-start px-4 py-3">IP</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'وقت' : 'Time' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($events as $event)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-secondary-900/40">
                                <td class="px-4 py-3 text-secondary-500">{{ $event->id }}</td>
                                <td class="px-4 py-3"><span class="badge badge-info">{{ $event->type }}</span></td>
                                <td class="px-4 py-3 text-secondary-900 dark:text-secondary-50">{{ $event->email ?? '-' }}</td>
                                <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">{{ $event->ip ?? '-' }}</td>
                                <td class="px-4 py-3 text-secondary-600 dark:text-secondary-300">{{ $event->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-secondary-500">{{ app()->getLocale() === 'ar' ? 'لا يوجد بيانات.' : 'No results.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $events->links() }}</div>
        </div>
    </div>
@endsection
