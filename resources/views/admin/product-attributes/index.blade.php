@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الخصائص' : 'Attributes' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'إدارة خصائص المنتجات حسب التصنيفات.' : 'Manage product attributes per category.' }}</p>
            </div>
            <a class="btn-primary" href="{{ route('admin.product-attributes.create') }}">{{ app()->getLocale() === 'ar' ? 'إضافة' : 'Create' }}</a>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">#</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'حالة' : 'Status' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($attributes as $attribute)
                            <tr>
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $attribute->id }}</td>
                                <td class="px-5 py-3 text-secondary-900 dark:text-secondary-50">
                                    <div class="font-semibold">{{ $attribute->name_ar }}</div>
                                    <div class="text-xs text-secondary-500 dark:text-secondary-400">{{ $attribute->name_en }}</div>
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">
                                    {{ $attribute->input_type }}
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($attribute->categories as $category)
                                            <span class="px-2 py-1 rounded-full bg-gray-100 text-xs text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($attribute->is_active)
                                        <span class="px-2 py-1 rounded-full bg-green-100 text-xs text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                            {{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-gray-100 text-xs text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                                            {{ app()->getLocale() === 'ar' ? 'مخفي' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-end">
                                    <div class="inline-flex items-center gap-2">
                                        <a class="text-primary-700 hover:text-primary-800 text-sm" href="{{ route('admin.product-attributes.edit', $attribute) }}">
                                            {{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.product-attributes.destroy', $attribute) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'تأكيد الحذف؟' : 'Confirm delete?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                                {{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-6 text-center text-secondary-600 dark:text-secondary-300" colspan="6">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد خصائص بعد.' : 'No attributes yet.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $attributes->links() }}
            </div>
        </div>
    </div>
@endsection

