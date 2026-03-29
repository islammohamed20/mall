@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.email-campaigns.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ $emailCampaign->name }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</div>
            <div class="mt-2">
                @if($emailCampaign->status === 'sent')
                    <span class="badge badge-success">{{ $emailCampaign->status_label }}</span>
                @elseif($emailCampaign->status === 'draft')
                    <span class="badge badge-secondary">{{ $emailCampaign->status_label }}</span>
                @else
                    <span class="badge badge-warning">{{ $emailCampaign->status_label }}</span>
                @endif
            </div>
        </div>

        <div class="card p-5">
            <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'المستلمين' : 'Recipients' }}</div>
            <div class="text-2xl font-bold text-secondary-900 dark:text-secondary-50 mt-1">{{ number_format($emailCampaign->recipients_count) }}</div>
        </div>

        <div class="card p-5">
            <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'تم الإرسال' : 'Sent' }}</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($emailCampaign->sent_count) }}</div>
        </div>

        <div class="card p-5">
            <div class="text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'فشل' : 'Failed' }}</div>
            <div class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ number_format($emailCampaign->failed_count) }}</div>
        </div>
    </div>

    <div class="card p-6">
        <div class="space-y-4">
            <div>
                <div class="text-sm font-medium text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'الموضوع (عربي)' : 'Subject (AR)' }}</div>
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50 mt-1">{{ $emailCampaign->subject_ar }}</div>
            </div>

            <div>
                <div class="text-sm font-medium text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'المحتوى (عربي)' : 'Body (AR)' }}</div>
                <div class="mt-2 p-4 bg-secondary-50 dark:bg-secondary-900 rounded-lg whitespace-pre-wrap">{{ $emailCampaign->body_ar }}</div>
            </div>

            <div class="border-t border-gray-200 dark:border-secondary-700 pt-4">
                <div class="text-sm font-medium text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'الموضوع (English)' : 'Subject (EN)' }}</div>
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50 mt-1">{{ $emailCampaign->subject_en }}</div>
            </div>

            <div>
                <div class="text-sm font-medium text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'المحتوى (English)' : 'Body (EN)' }}</div>
                <div class="mt-2 p-4 bg-secondary-50 dark:bg-secondary-900 rounded-lg whitespace-pre-wrap">{{ $emailCampaign->body_en }}</div>
            </div>
        </div>
    </div>

    @if($emailCampaign->status === 'draft')
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.email-campaigns.edit', $emailCampaign) }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
            <form action="{{ route('admin.email-campaigns.send', $emailCampaign) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من إرسال الحملة؟' : 'Are you sure you want to send this campaign?' }}')">
                @csrf
                <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'إرسال الآن' : 'Send Now' }}</button>
            </form>
        </div>
    @endif

    @if($emailCampaign->sent_at)
        <div class="card p-4 bg-green-50 dark:bg-green-900/20">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <div class="font-semibold text-green-900 dark:text-green-100">{{ app()->getLocale() === 'ar' ? 'تم إرسال الحملة' : 'Campaign sent' }}</div>
                    <div class="text-sm text-green-800 dark:text-green-200 mt-0.5">{{ $emailCampaign->sent_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
