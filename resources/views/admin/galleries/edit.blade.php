@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل المعرض' : 'Edit Gallery' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.galleries.show', $gallery) }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-4" method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان عربي' : 'Title (AR)' }} *</label>
                    <input class="form-input" name="title_ar" value="{{ old('title_ar', $gallery->title_ar) }}" required />
                    @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان English' : 'Title (EN)' }} *</label>
                    <input class="form-input" name="title_en" value="{{ old('title_en', $gallery->title_en) }}" required />
                    @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف عربي' : 'Description (AR)' }}</label>
                <textarea class="form-input" rows="4" name="description_ar">{{ old('description_ar', $gallery->description_ar) }}</textarea>
                @error('description_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف English' : 'Description (EN)' }}</label>
                <textarea class="form-input" rows="4" name="description_en">{{ old('description_en', $gallery->description_en) }}</textarea>
                @error('description_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحل (اختياري)' : 'Shop (Optional)' }}</label>
                <select class="form-input" name="shop_id">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'بدون محل' : 'No shop' }}</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}" @selected(old('shop_id', $gallery->shop_id) == $shop->id)>{{ $shop->name_ar }} - {{ $shop->name_en }}</option>
                    @endforeach
                </select>
                @error('shop_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صورة الغلاف' : 'Cover Image' }}</label>
                @if ($gallery->cover_image_url)
                    <div class="mb-2">
                        <img class="h-32 w-48 object-cover rounded" src="{{ $gallery->cover_image_url }}" alt="">
                    </div>
                @endif
                <input class="form-input" type="file" name="cover_image" accept="image/*" />
                @error('cover_image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب العرض' : 'Sort Order' }}</label>
                <input class="form-input" type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" />
                @error('sort_order') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $gallery->is_featured)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', $gallery->is_active)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </div>
            </div>
            
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
        </form>
    </div>
@endsection
