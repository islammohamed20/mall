@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة فعالية' : 'Create Event' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.events.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-6" method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحل (اختياري)' : 'Shop (optional)' }}</label>
                <select class="form-input" name="shop_id">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'عام' : 'General' }}</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}" @selected((string) old('shop_id') === (string) $shop->id)>{{ $shop->name }}</option>
                    @endforeach
                </select>
                @error('shop_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان عربي' : 'Title (AR)' }}</label>
                    <input class="form-input" name="title_ar" value="{{ old('title_ar') }}" required />
                    @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان English' : 'Title (EN)' }}</label>
                    <input class="form-input" name="title_en" value="{{ old('title_en') }}" required />
                    @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Slug</label>
                <input class="form-input" name="slug" value="{{ old('slug') }}" />
                @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مختصر عربي' : 'Short (AR)' }}</label>
                    <input class="form-input" name="short_ar" value="{{ old('short_ar') }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مختصر English' : 'Short (EN)' }}</label>
                    <input class="form-input" name="short_en" value="{{ old('short_en') }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى عربي' : 'Content (AR)' }}</label>
                    <textarea class="form-input" rows="6" name="content_ar">{{ old('content_ar') }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى English' : 'Content (EN)' }}</label>
                    <textarea class="form-input" rows="6" name="content_en">{{ old('content_en') }}</textarea>
                </div>
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صورة البانر' : 'Banner image' }}</label>
                <input class="form-input" type="file" name="banner_image" accept="image/*" />
                @error('banner_image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تاريخ البداية' : 'Start date' }}</label>
                    <input class="form-input" type="date" name="start_date" value="{{ old('start_date') }}" required />
                    @error('start_date') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تاريخ النهاية (اختياري)' : 'End date (optional)' }}</label>
                    <input class="form-input" type="date" name="end_date" value="{{ old('end_date') }}" />
                    @error('end_date') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'وقت البداية (اختياري)' : 'Start time (optional)' }}</label>
                    <input class="form-input" type="time" name="start_time" value="{{ old('start_time') }}" />
                    @error('start_time') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'وقت النهاية (اختياري)' : 'End time (optional)' }}</label>
                    <input class="form-input" type="time" name="end_time" value="{{ old('end_time') }}" />
                    @error('end_time') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموقع عربي' : 'Location (AR)' }}</label>
                    <input class="form-input" name="location_ar" value="{{ old('location_ar') }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموقع English' : 'Location (EN)' }}</label>
                    <input class="form-input" name="location_en" value="{{ old('location_en') }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                    <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" />
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input type="hidden" name="is_active" value="0" />
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input type="hidden" name="is_featured" value="0" />
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', false)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                </div>
            </div>

            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
