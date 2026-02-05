@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل تصنيف' : 'Edit Category' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.shop-categories.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-4" method="POST" action="{{ route('admin.shop-categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم عربي' : 'Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required />
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم English' : 'Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en', $category->name_en) }}" required />
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Slug</label>
                    <input class="form-input" name="slug" value="{{ old('slug', $category->slug) }}" />
                    @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'أيقونة' : 'Icon' }}</label>
                    <input class="form-input" name="icon" value="{{ old('icon', $category->icon) }}" />
                    @error('icon') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                    <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" />
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </div>
            </div>
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
