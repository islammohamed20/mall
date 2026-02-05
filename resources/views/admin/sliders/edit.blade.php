@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل السلايدر' : 'Edit Slider' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.sliders.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-4" method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان عربي' : 'Title (AR)' }}</label>
                    <input class="form-input" name="title_ar" value="{{ old('title_ar', $slider->title_ar) }}" />
                    @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان English' : 'Title (EN)' }}</label>
                    <input class="form-input" name="title_en" value="{{ old('title_en', $slider->title_en) }}" />
                    @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان الفرعي عربي' : 'Subtitle (AR)' }}</label>
                    <input class="form-input" name="subtitle_ar" value="{{ old('subtitle_ar', $slider->subtitle_ar) }}" />
                    @error('subtitle_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان الفرعي English' : 'Subtitle (EN)' }}</label>
                    <input class="form-input" name="subtitle_en" value="{{ old('subtitle_en', $slider->subtitle_en) }}" />
                    @error('subtitle_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نص الزر الأول عربي' : 'CTA Text (AR)' }}</label>
                    <input class="form-input" name="cta_text_ar" value="{{ old('cta_text_ar', $slider->cta_text_ar) }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نص الزر الأول English' : 'CTA Text (EN)' }}</label>
                    <input class="form-input" name="cta_text_en" value="{{ old('cta_text_en', $slider->cta_text_en) }}" />
                </div>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رابط الزر الأول' : 'CTA Link' }}</label>
                <input class="form-input" name="cta_link" value="{{ old('cta_link', $slider->cta_link) }}" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نص الزر الثاني عربي' : 'CTA 2 Text (AR)' }}</label>
                    <input class="form-input" name="cta_text_2_ar" value="{{ old('cta_text_2_ar', $slider->cta_text_2_ar) }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نص الزر الثاني English' : 'CTA 2 Text (EN)' }}</label>
                    <input class="form-input" name="cta_text_2_en" value="{{ old('cta_text_2_en', $slider->cta_text_2_en) }}" />
                </div>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رابط الزر الثاني' : 'CTA 2 Link' }}</label>
                <input class="form-input" name="cta_link_2" value="{{ old('cta_link_2', $slider->cta_link_2) }}" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الصورة' : 'Image' }}</label>
                    @if ($slider->image)
                        <div class="mb-2">
                            <img class="h-20 w-32 object-cover rounded" src="{{ asset('storage/' . $slider->image) }}" alt="">
                        </div>
                    @endif
                    <input class="form-input" type="file" name="image" accept="image/*" />
                    @error('image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صورة الموبايل' : 'Mobile Image' }}</label>
                    @if ($slider->image_mobile)
                        <div class="mb-2">
                            <img class="h-20 w-20 object-cover rounded" src="{{ asset('storage/' . $slider->image_mobile) }}" alt="">
                        </div>
                    @endif
                    <input class="form-input" type="file" name="image_mobile" accept="image/*" />
                    @error('image_mobile') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تاريخ البدء' : 'Start Date' }}</label>
                    <input class="form-input" type="date" name="start_date" value="{{ old('start_date', $slider->start_date?->format('Y-m-d')) }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تاريخ الانتهاء' : 'End Date' }}</label>
                    <input class="form-input" type="date" name="end_date" value="{{ old('end_date', $slider->end_date?->format('Y-m-d')) }}" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                    <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" />
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', $slider->is_active)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </div>
            </div>
            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
