@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.shipping-zones.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة منطقة شحن' : 'Add Shipping Zone' }}</h1>
    </div>

    <form action="{{ route('admin.shipping-zones.store') }}" method="POST" class="card p-6">
        @csrf
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المنطقة (عربي)' : 'Zone Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar') }}" required />
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المنطقة (English)' : 'Zone Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en') }}" required />
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحافظة (عربي)' : 'Governorate (AR)' }}</label>
                    <input class="form-input" name="governorate_ar" value="{{ old('governorate_ar') }}" />
                    @error('governorate_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحافظة (English)' : 'Governorate (EN)' }}</label>
                    <input class="form-input" name="governorate_en" value="{{ old('governorate_en') }}" />
                    @error('governorate_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تكلفة الشحن' : 'Shipping Cost' }}</label>
                    <input type="number" step="0.01" class="form-input" name="shipping_cost" value="{{ old('shipping_cost', 0) }}" required />
                    @error('shipping_cost') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مدة التوصيل (أيام)' : 'Estimated Delivery (days)' }}</label>
                    <input type="number" class="form-input" name="estimated_days" value="{{ old('estimated_days', 3) }}" required />
                    @error('estimated_days') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الترتيب' : 'Order' }}</label>
                    <input type="number" class="form-input" name="order" value="{{ old('order', 0) }}" />
                    @error('order') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox" />
                        <span class="text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
                <a href="{{ route('admin.shipping-zones.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'إلغاء' : 'Cancel' }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
