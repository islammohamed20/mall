@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl">
        <div class="flex items-end justify-between gap-4 flex-col sm:flex-row">
            <div>
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ $shop->name }}</div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</h1>
            </div>
            <div class="flex gap-2">
                <a class="btn-outline" href="{{ route('admin.shops.edit', $shop) }}">{{ app()->getLocale() === 'ar' ? 'رجوع للمحل' : 'Back to shop' }}</a>
                <a class="btn-outline" href="{{ route('admin.shops.products.export', $shop) }}">{{ app()->getLocale() === 'ar' ? 'تصدير Excel' : 'Export Excel' }}</a>
                <a class="btn-secondary" href="{{ route('admin.shops.products.import.form', $shop) }}">{{ app()->getLocale() === 'ar' ? 'استيراد Excel' : 'Import Excel' }}</a>
                <a class="btn-primary" href="{{ route('admin.shops.products.create', $shop) }}">{{ app()->getLocale() === 'ar' ? 'إضافة منتج' : 'Add product' }}</a>
            </div>
        </div>

        <div class="mt-8 admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                            <th class="px-4 py-3 text-left">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                <td class="px-4 py-3 font-medium text-secondary-900 dark:text-secondary-100">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">{{ $product->sku ?: '—' }}</td>
                                <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">
                                    @if (!is_null($product->price))
                                        {{ number_format((float) $product->price, 2) }} {{ $product->currency }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($product->is_active)
                                        <span class="badge badge-success">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a class="btn-outline" href="{{ route('admin.shops.products.edit', [$shop, $product]) }}">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                        <form method="POST" action="{{ route('admin.shops.products.destroy', [$shop, $product]) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'تأكيد الحذف؟' : 'Delete?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-secondary" type="submit">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-secondary-600 dark:text-secondary-300" colspan="5">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد منتجات بعد.' : 'No products yet.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
