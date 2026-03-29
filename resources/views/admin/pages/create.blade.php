@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة صفحة' : 'Create Page' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.pages.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-4" method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان عربي' : 'Title (AR)' }} *</label>
                    <input class="form-input" name="title_ar" value="{{ old('title_ar') }}" required />
                    @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان English' : 'Title (EN)' }} *</label>
                    <input class="form-input" name="title_en" value="{{ old('title_en') }}" required />
                    @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <label class="form-label">Slug</label>
                <input class="form-input" name="slug" value="{{ old('slug') }}" placeholder="auto-generated" />
                @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى عربي' : 'Content (AR)' }}</label>
                <textarea class="form-input" rows="8" name="content_ar">{{ old('content_ar') }}</textarea>
                @error('content_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى English' : 'Content (EN)' }}</label>
                <textarea class="form-input" rows="8" name="content_en">{{ old('content_en') }}</textarea>
                @error('content_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Title عربي' : 'Meta Title (AR)' }}</label>
                    <input class="form-input" name="meta_title_ar" value="{{ old('meta_title_ar') }}" />
                    @error('meta_title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Title English' : 'Meta Title (EN)' }}</label>
                    <input class="form-input" name="meta_title_en" value="{{ old('meta_title_en') }}" />
                    @error('meta_title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Description عربي' : 'Meta Description (AR)' }}</label>
                    <textarea class="form-input" rows="2" name="meta_description_ar">{{ old('meta_description_ar') }}</textarea>
                    @error('meta_description_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Meta Description English' : 'Meta Description (EN)' }}</label>
                    <textarea class="form-input" rows="2" name="meta_description_en">{{ old('meta_description_en') }}</textarea>
                    @error('meta_description_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صورة مميزة' : 'Featured Image' }}</label>
                <input class="form-input" type="file" name="featured_image" accept="image/*" />
                @error('featured_image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="flex items-center gap-2">
                <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) />
                <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
            </div>
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
