@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تفاصيل الرسالة' : 'Message Details' }}</h1>
                <div class="mt-2 flex items-center gap-2">
                    <span class="badge {{ $contactMessage->status_color }}">{{ $contactMessage->status_label }}</span>
                    <span class="text-sm text-secondary-600 dark:text-secondary-300">{{ $contactMessage->created_at?->format('Y-m-d H:i') }}</span>
                </div>
            </div>
            <a class="btn-outline" href="{{ route('admin.messages.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <div class="admin-card p-6 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</div>
                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $contactMessage->name }}</div>
                </div>
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</div>
                    <div class="font-semibold text-secondary-900 dark:text-secondary-100"><a class="text-primary-700 hover:text-primary-800" href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</div>
                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $contactMessage->phone }}</div>
                </div>
                <div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</div>
                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $contactMessage->subject }}</div>
                </div>
            </div>
            <div>
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'الرسالة' : 'Message' }}</div>
                <div class="mt-2 text-secondary-800 dark:text-secondary-200 whitespace-pre-line">{{ $contactMessage->message }}</div>
            </div>
        </div>

        <form class="admin-card p-6 flex flex-col sm:flex-row gap-3 items-start sm:items-end" method="POST" action="{{ route('admin.messages.status', $contactMessage) }}">
            @csrf
            @method('PATCH')
            <div class="w-full sm:w-72">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تغيير الحالة' : 'Change status' }}</label>
                <select class="form-input" name="status" required>
                    <option value="new" @selected($contactMessage->status === 'new')>{{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}</option>
                    <option value="read" @selected($contactMessage->status === 'read')>{{ app()->getLocale() === 'ar' ? 'مقروء' : 'Read' }}</option>
                    <option value="replied" @selected($contactMessage->status === 'replied')>{{ app()->getLocale() === 'ar' ? 'تم الرد' : 'Replied' }}</option>
                    <option value="archived" @selected($contactMessage->status === 'archived')>{{ app()->getLocale() === 'ar' ? 'مؤرشف' : 'Archived' }}</option>
                </select>
            </div>
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
