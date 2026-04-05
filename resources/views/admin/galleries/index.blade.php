@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إدارة المعرض' : 'Gallery Management' }}</h1>
                <p class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'إدارة معارض الصور والفيديوهات' : 'Manage photo and video galleries' }}</p>
            </div>
            <a class="btn-primary" href="{{ route('admin.galleries.create') }}">{{ app()->getLocale() === 'ar' ? '+ إضافة معرض' : '+ Add Gallery' }}</a>
        </div>

        @if (session('status'))
            <div class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 admin-card">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-secondary-200 dark:border-secondary-700">
                        <th class="px-4 py-3 text-right text-sm font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المعرض' : 'Gallery' }}</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'العناصر' : 'Items' }}</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    @forelse ($galleries as $gallery)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($gallery->cover_image_url)
                                        <img class="h-12 w-16 object-cover rounded" src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title_ar }}">
                                    @else
                                        <div class="h-12 w-16 bg-secondary-200 dark:bg-secondary-700 rounded flex items-center justify-center">
                                            <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-secondary-900 dark:text-secondary-50">{{ $gallery->title_ar }}</div>
                                        <div class="text-sm text-secondary-500 dark:text-secondary-400">{{ $gallery->title_en }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($gallery->shop)
                                    <span class="text-sm text-secondary-700 dark:text-secondary-300">{{ $gallery->shop->name_ar }}</span>
                                @else
                                    <span class="text-sm text-secondary-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-secondary-700 dark:text-secondary-300">{{ $gallery->items->count() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1">
                                    @if ($gallery->is_featured)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                                    @endif
                                    @if ($gallery->is_active)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a class="text-primary-600 hover:text-primary-700 text-sm" href="{{ route('admin.galleries.show', $gallery) }}">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</a>
                                    <a class="text-primary-600 hover:text-primary-700 text-sm" href="{{ route('admin.galleries.edit', $gallery) }}">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-700 text-sm" type="submit">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-secondary-500 dark:text-secondary-400">
                                {{ app()->getLocale() === 'ar' ? 'لا توجد معارض حتى الآن' : 'No galleries yet' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($galleries->hasPages())
            <div class="mt-6">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
@endsection
