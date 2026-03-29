@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.email-campaigns.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'حملة بريد إلكتروني جديدة' : 'New Email Campaign' }}</h1>
    </div>

    <div class="card p-6 bg-blue-50 dark:bg-blue-900/20">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <div class="font-semibold text-blue-900 dark:text-blue-100">{{ app()->getLocale() === 'ar' ? 'سيتم إرسال الحملة إلى:' : 'Campaign will be sent to:' }}</div>
                <div class="text-blue-800 dark:text-blue-200 mt-1">{{ number_format($subscribersCount) }} {{ app()->getLocale() === 'ar' ? 'مشترك نشط' : 'active subscribers' }}</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.email-campaigns.store') }}" method="POST" class="card p-6">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم الحملة (داخلي)' : 'Campaign Name (internal)' }}</label>
                <input class="form-input" name="name" value="{{ old('name') }}" required />
                @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع (عربي)' : 'Subject (AR)' }}</label>
                    <input class="form-input" name="subject_ar" value="{{ old('subject_ar') }}" required />
                    @error('subject_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع (English)' : 'Subject (EN)' }}</label>
                    <input class="form-input" name="subject_en" value="{{ old('subject_en') }}" required />
                    @error('subject_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى (عربي)' : 'Body (AR)' }}</label>
                <textarea class="form-input" name="body_ar" rows="10" required>{{ old('body_ar') }}</textarea>
                @error('body_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى (English)' : 'Body (EN)' }}</label>
                <textarea class="form-input" name="body_en" rows="10" required>{{ old('body_en') }}</textarea>
                @error('body_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'حفظ كمسودة' : 'Save as Draft' }}</button>
                <a href="{{ route('admin.email-campaigns.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'إلغاء' : 'Cancel' }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
