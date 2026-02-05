@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل محل' : 'Edit Shop' }}</h1>
            </div>
            <div class="flex gap-2">
                <a class="btn-outline" href="{{ route('admin.shops.products.index', $shop) }}">{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</a>
                <a class="btn-outline" href="{{ route('admin.shops.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
            </div>
        </div>

        <form class="mt-8 admin-card p-6 space-y-6" method="POST" action="{{ route('admin.shops.update', $shop) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'التصنيف' : 'Category' }}</label>
                    <select class="form-input" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $shop->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Floor' }}</label>
                    <select class="form-input" name="floor_id">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'بدون' : 'None' }}</option>
                        @foreach ($floors as $floor)
                            <option value="{{ $floor->id }}" @selected((string) old('floor_id', $shop->floor_id) === (string) $floor->id)>{{ $floor->name }}</option>
                        @endforeach
                    </select>
                    @error('floor_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم عربي' : 'Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar', $shop->name_ar) }}" required />
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم English' : 'Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en', $shop->name_en) }}" required />
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Slug</label>
                    <input class="form-input" name="slug" value="{{ old('slug', $shop->slug) }}" />
                    @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الوحدة' : 'Unit number' }}</label>
                    <input class="form-input" name="unit_number" value="{{ old('unit_number', $shop->unit_number) }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</label>
                    <input class="form-input" name="phone" value="{{ old('phone', $shop->phone) }}" />
                </div>
                <div>
                    <label class="form-label">WhatsApp</label>
                    <input class="form-input" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input class="form-input" name="email" value="{{ old('email', $shop->email) }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموقع' : 'Website' }}</label>
                    <input class="form-input" name="website" value="{{ old('website', $shop->website) }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Facebook Page ID</label>
                    <input class="form-input" name="facebook_page_id" value="{{ old('facebook_page_id', $shop->facebook_page_id) }}" />
                </div>
                <div>
                    <label class="form-label">Facebook Page Access Token</label>
                    <input class="form-input" type="password" name="facebook_page_access_token" value="" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ساعات العمل عربي' : 'Opening hours (AR)' }}</label>
                    <input class="form-input" name="opening_hours_ar" value="{{ old('opening_hours_ar', $shop->opening_hours_ar) }}" />
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Opening hours EN' : 'Opening hours (EN)' }}</label>
                    <input class="form-input" name="opening_hours_en" value="{{ old('opening_hours_en', $shop->opening_hours_en) }}" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف عربي' : 'Description (AR)' }}</label>
                    <textarea class="form-input" rows="4" name="description_ar">{{ old('description_ar', $shop->description_ar) }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Description EN' : 'Description (EN)' }}</label>
                    <textarea class="form-input" rows="4" name="description_en">{{ old('description_en', $shop->description_en) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'لوجو المحل' : 'Shop logo' }}</label>
                    @if ($shop->logo_url)
                        <div class="mb-2">
                            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" class="h-16 object-contain" />
                        </div>
                    @endif
                    <input class="form-input" type="file" name="logo" accept="image/*" />
                    @error('logo') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صورة الغلاف' : 'Cover image' }}</label>
                    @if ($shop->cover_url)
                        <div class="mb-2">
                            <img src="{{ $shop->cover_url }}" alt="{{ $shop->name }}" class="h-24 w-full object-cover rounded-lg" />
                        </div>
                    @endif
                    <input class="form-input" type="file" name="cover_image" accept="image/*" />
                    @error('cover_image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                    <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $shop->sort_order) }}" />
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', $shop->is_active)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </div>
                <div class="flex items-center gap-2 pt-7">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $shop->is_featured)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-center gap-2 pt-1 sm:pt-7">
                    <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_open_now" value="1" @checked(old('is_open_now', $shop->is_open_now)) />
                    <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'مفتوح الآن' : 'Open now' }}</span>
                </div>
            </div>

            <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection
