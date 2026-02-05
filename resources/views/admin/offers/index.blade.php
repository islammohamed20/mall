@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'إدارة العروض.' : 'Manage offers.' }}</p>
            </div>
            <a class="btn-primary" href="{{ route('admin.offers.create') }}">{{ app()->getLocale() === 'ar' ? 'إضافة' : 'Create' }}</a>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">#</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'العرض' : 'Offer' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المدة' : 'Dates' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'حالة' : 'Status' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @foreach ($offers as $offer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $offer->id }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $offer->title }}</div>
                                    <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ $offer->slug }}</div>
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">
                                    {{ $offer->shop?->name ?? (app()->getLocale() === 'ar' ? 'عام' : 'General') }}
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">
                                    <div>{{ $offer->start_date?->format('Y-m-d') }} → {{ $offer->end_date?->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="badge {{ $offer->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $offer->is_active ? (app()->getLocale() === 'ar' ? 'نشط' : 'Active') : (app()->getLocale() === 'ar' ? 'موقف' : 'Inactive') }}
                                    </span>
                                    @if ($offer->is_featured)
                                        <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ route('admin.offers.edit', $offer) }}">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                        <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" x-data @submit.prevent="if (confirm('{{ app()->getLocale() === 'ar' ? 'تأكيد الحذف؟' : 'Confirm delete?' }}')) $el.submit()">
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
                {{ $offers->links() }}
            </div>
        </div>
    </div>
@endsection
