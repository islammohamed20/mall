@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تفاصيل الإيميل' : 'Email Details' }} #{{ $email->id }}</h1>
                <p class="mt-1 text-secondary-700 dark:text-secondary-200">{{ $email->subject }}</p>
            </div>
            <a class="btn-outline" href="{{ route('admin.emails.outbox.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <div class="admin-card p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</div>
                    <div class="mt-1 font-medium">{{ is_array($email->to) ? implode(', ', $email->to) : (string) $email->to }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</div>
                    <div class="mt-1 font-medium">{{ is_array($email->from) ? implode(', ', $email->from) : (string) $email->from }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-500">CC</div>
                    <div class="mt-1">{{ is_array($email->cc) ? implode(', ', $email->cc) : (string) ($email->cc ?? '-') }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-500">BCC</div>
                    <div class="mt-1">{{ is_array($email->bcc) ? implode(', ', $email->bcc) : (string) ($email->bcc ?? '-') }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-500">Message-ID</div>
                    <div class="mt-1 text-secondary-700 dark:text-secondary-200 break-all">{{ $email->message_id ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'وقت الإرسال' : 'Sent at' }}</div>
                    <div class="mt-1 text-secondary-700 dark:text-secondary-200">{{ $email->sent_at?->format('Y-m-d H:i:s') ?? '-' }}</div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-secondary-800">
                <div class="text-sm font-semibold text-secondary-900 dark:text-secondary-50 mb-2">HTML</div>
                @if ($email->html_body)
                    <div class="rounded-xl border border-gray-200 dark:border-secondary-800 overflow-hidden">
                        <iframe class="w-full h-[70vh] bg-white" sandbox="" srcdoc="{{ e($email->html_body) }}"></iframe>
                    </div>
                @else
                    <div class="text-sm text-secondary-500">-</div>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-secondary-800">
                <div class="text-sm font-semibold text-secondary-900 dark:text-secondary-50 mb-2">Text</div>
                @if ($email->text_body)
                    <pre class="rounded-xl bg-gray-50 dark:bg-secondary-900/50 p-4 overflow-x-auto text-xs">{{ $email->text_body }}</pre>
                @else
                    <div class="text-sm text-secondary-500">-</div>
                @endif
            </div>
        </div>
    </div>
@endsection
