@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إدارة صفحة عن المول' : 'Manage About Page' }}</h1>
                <p class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'تحكم في محتوى صفحة "عن المول"' : 'Control the content of the "About Mall" page' }}</p>
            </div>
            <a class="btn-outline" href="{{ route('about') }}" target="_blank">{{ app()->getLocale() === 'ar' ? 'معاينة الصفحة' : 'Preview Page' }}</a>
        </div>

        @if (session('status'))
            <div class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-8 admin-card p-6 space-y-4" method="POST" action="{{ route('admin.pages.about.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان عربي' : 'Title (AR)' }} *</label>
                    <input class="form-input" name="title_ar" value="{{ old('title_ar', $page->title_ar) }}" required />
                    @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان English' : 'Title (EN)' }} *</label>
                    <input class="form-input" name="title_en" value="{{ old('title_en', $page->title_en) }}" required />
                    @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى عربي' : 'Content (AR)' }} *</label>
                <textarea class="form-input font-arabic" rows="12" name="content_ar" required>{{ old('content_ar', $page->content_ar) }}</textarea>
                <p class="mt-1 text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'اكتب محتوى صفحة عن المول باللغة العربية' : 'Write about page content in Arabic' }}</p>
                @error('content_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى English' : 'Content (EN)' }} *</label>
                <textarea class="form-input" rows="12" name="content_en" required>{{ old('content_en', $page->content_en) }}</textarea>
                <p class="mt-1 text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'اكتب محتوى صفحة عن المول باللغة الإنجليزية' : 'Write about page content in English' }}</p>
                @error('content_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="border-t border-secondary-200 dark:border-secondary-700 pt-4 mt-6">
                <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-3">{{ app()->getLocale() === 'ar' ? 'إعدادات SEO' : 'SEO Settings' }}</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Title عربي' : 'Meta Title (AR)' }}</label>
                        <input class="form-input" name="meta_title_ar" value="{{ old('meta_title_ar', $page->meta_title_ar) }}" />
                        @error('meta_title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Title English' : 'Meta Title (EN)' }}</label>
                        <input class="form-input" name="meta_title_en" value="{{ old('meta_title_en', $page->meta_title_en) }}" />
                        @error('meta_title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Description عربي' : 'Meta Description (AR)' }}</label>
                        <textarea class="form-input" rows="3" name="meta_description_ar">{{ old('meta_description_ar', $page->meta_description_ar) }}</textarea>
                        @error('meta_description_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Description English' : 'Meta Description (EN)' }}</label>
                        <textarea class="form-input" rows="3" name="meta_description_en">{{ old('meta_description_en', $page->meta_description_en) }}</textarea>
                        @error('meta_description_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            
            <div class="border-t border-secondary-200 dark:border-secondary-700 pt-4 mt-6">
                <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-3">{{ app()->getLocale() === 'ar' ? 'صورة مميزة' : 'Featured Image' }}</h3>
                @if ($page->featured_image)
                    <div class="mb-3">
                        <img class="h-40 w-auto object-cover rounded-lg shadow" src="{{ asset('storage/' . $page->featured_image) }}" alt="">
                    </div>
                @endif
                <input class="form-input" type="file" name="featured_image" accept="image/*" />
                <p class="mt-1 text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'رفع صورة جديدة (اختياري)' : 'Upload new image (optional)' }}</p>
                @error('featured_image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div class="flex items-center gap-2 pt-4">
                <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active)) />
                <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'تفعيل الصفحة' : 'Activate Page' }}</span>
            </div>
            
            <div class="pt-4">
                <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .font-arabic {
        font-family: 'Cairo', 'Tajawal', sans-serif;
        line-height: 2;
    }
</style>
@endpush
