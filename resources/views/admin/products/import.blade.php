@extends('layouts.admin')

@section('content')
    <div class="max-w-xl">
        <div class="flex items-end justify-between gap-4">
            <div>
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ $shop->name }}</div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'استيراد المنتجات من Excel' : 'Import products from Excel' }}
                </h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.shops.products.index', $shop) }}">
                {{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}
            </a>
        </div>

        <div class="mt-8 admin-card p-6 space-y-4">
            <p class="text-sm text-secondary-700 dark:text-secondary-200">
                {{ app()->getLocale() === 'ar'
                    ? 'قم برفع ملف CSV (يمكن فتحه في Excel) يحتوي على الأعمدة: id, name_ar, name_en, slug, sku, price, old_price, currency, description_ar, description_en, is_active, sort_order.'
                    : 'Upload a CSV file (Excel compatible) with columns: id, name_ar, name_en, slug, sku, price, old_price, currency, description_ar, description_en, is_active, sort_order.' }}
            </p>

            <form method="POST" action="{{ route('admin.shops.products.import', $shop) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ملف CSV' : 'CSV file' }}</label>
                    <input class="form-input" type="file" name="file" accept=".csv,text/csv" required />
                    @error('file') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <button class="btn-primary" type="submit">
                    {{ app()->getLocale() === 'ar' ? 'استيراد' : 'Import' }}
                </button>
            </form>
        </div>
    </div>
@endsection

