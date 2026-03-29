@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'مشتركي النشرة البريدية' : 'Newsletter Subscribers' }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.newsletter.export') }}" class="btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ app()->getLocale() === 'ar' ? 'تصدير CSV' : 'Export CSV' }}
            </a>
            <a href="{{ route('admin.newsletter.create') }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ app()->getLocale() === 'ar' ? 'إضافة مشترك' : 'Add Subscriber' }}
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'إجمالي المشتركين' : 'Total Subscribers' }}</div>
                    <div class="text-2xl font-bold text-secondary-900 dark:text-secondary-50 mt-1">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="p-3 rounded-full bg-primary-100 dark:bg-primary-900/30">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'مشتركين نشطين' : 'Active' }}</div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($stats['active']) }}</div>
                </div>
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'غير نشطين' : 'Inactive' }}</div>
                    <div class="text-2xl font-bold text-secondary-600 dark:text-secondary-400 mt-1">{{ number_format($stats['inactive']) }}</div>
                </div>
                <div class="p-3 rounded-full bg-secondary-100 dark:bg-secondary-800">
                    <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'اليوم' : 'Today' }}</div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ number_format($stats['today']) }}</div>
                </div>
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card p-4">
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}</label>
                <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'بريد إلكتروني أو اسم' : 'Email or name' }}" />
            </div>
            <div class="w-48">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</label>
                <select name="status" class="form-input">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.newsletter.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}</a>
            @endif
        </div>
    </form>

    <div class="card overflow-hidden">
        @if($subscribers->isEmpty())
            <div class="p-10 text-center">
                <svg class="w-16 h-16 mx-auto text-secondary-400 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="mt-4 text-lg font-medium text-secondary-700 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'لا يوجد مشتركين' : 'No subscribers' }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المصدر' : 'Source' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'تاريخ الاشتراك' : 'Subscribed At' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @foreach($subscribers as $sub)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                <td class="px-5 py-3 font-medium text-secondary-900 dark:text-white">{{ $sub->email }}</td>
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $sub->name ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full bg-secondary-100 text-secondary-700 dark:bg-secondary-800 dark:text-secondary-300">
                                        {{ $sub->source }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $sub->subscribed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    @if($sub->is_active && !$sub->unsubscribed_at)
                                        <span class="badge badge-success">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.newsletter.toggle-status', $sub) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-secondary-sm">
                                                {{ $sub->is_active ? (app()->getLocale() === 'ar' ? 'إلغاء' : 'Unsubscribe') : (app()->getLocale() === 'ar' ? 'تفعيل' : 'Activate') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-sm">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-secondary-700">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
