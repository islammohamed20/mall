@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'إدارة المحلات.' : 'Manage shops.' }}</p>
            </div>
            <a class="btn-primary" href="{{ route('admin.shops.create') }}">{{ app()->getLocale() === 'ar' ? 'إضافة' : 'Create' }}</a>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">#</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'التصنيف' : 'Category' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'حالة' : 'Status' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @foreach ($shops as $shop)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $shop->id }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $shop->name }}</div>
                                    <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ $shop->slug }}</div>
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">{{ $shop->category?->name }}</td>
                                <td class="px-5 py-3">
                                    <span class="badge {{ $shop->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $shop->is_active ? (app()->getLocale() === 'ar' ? 'نشط' : 'Active') : (app()->getLocale() === 'ar' ? 'موقف' : 'Inactive') }}
                                    </span>
                                    @if ($shop->is_featured)
                                        <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ route('admin.shops.edit', $shop) }}">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                        <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}" x-data @submit.prevent="if (confirm('{{ app()->getLocale() === 'ar' ? 'تأكيد الحذف؟' : 'Confirm delete?' }}')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 dark:border-red-900/40 dark:text-red-200 dark:hover:bg-red-900/20" type="submit">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $shops->links() }}
            </div>
        </div>
    </div>
@endsection
