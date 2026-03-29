@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'حملات البريد الإلكتروني' : 'Email Campaigns' }}</h1>
        <a href="{{ route('admin.email-campaigns.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ app()->getLocale() === 'ar' ? 'حملة جديدة' : 'New Campaign' }}
        </a>
    </div>

    <div class="card overflow-hidden">
        @if($campaigns->isEmpty())
            <div class="p-10 text-center">
                <svg class="w-16 h-16 mx-auto text-secondary-400 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="mt-4 text-lg font-medium text-secondary-700 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'لا توجد حملات' : 'No campaigns' }}</p>
            </div>
        @else
            <table class="table-auto">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() === 'ar' ? 'اسم الحملة' : 'Campaign Name' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'المستلمين' : 'Recipients' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'تم الإرسال' : 'Sent' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'فشل' : 'Failed' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td class="font-medium">{{ $campaign->name }}</td>
                            <td>
                                @if($campaign->status === 'draft')
                                    <span class="badge badge-secondary">{{ $campaign->status_label }}</span>
                                @elseif($campaign->status === 'sending')
                                    <span class="badge badge-warning">{{ $campaign->status_label }}</span>
                                @elseif($campaign->status === 'sent')
                                    <span class="badge badge-success">{{ $campaign->status_label }}</span>
                                @else
                                    <span class="badge badge-danger">{{ $campaign->status_label }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($campaign->recipients_count) }}</td>
                            <td class="text-center text-green-600 dark:text-green-400">{{ number_format($campaign->sent_count) }}</td>
                            <td class="text-center text-red-600 dark:text-red-400">{{ number_format($campaign->failed_count) }}</td>
                            <td class="text-sm">{{ $campaign->sent_at?->format('Y-m-d H:i') ?? $campaign->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.email-campaigns.show', $campaign) }}" class="btn-secondary-sm">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</a>
                                    @if($campaign->status === 'draft')
                                        <a href="{{ route('admin.email-campaigns.edit', $campaign) }}" class="btn-secondary-sm">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                        <form action="{{ route('admin.email-campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من إرسال الحملة؟' : 'Are you sure you want to send this campaign?' }}')">
                                            @csrf
                                            <button type="submit" class="btn-primary-sm">{{ app()->getLocale() === 'ar' ? 'إرسال' : 'Send' }}</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.email-campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
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

            <div class="p-4 border-t border-gray-200 dark:border-secondary-700">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
