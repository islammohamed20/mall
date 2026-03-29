@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الوحدات المعروضة' : 'Units for Sale/Rent' }}</h1>
        <a href="{{ route('admin.units.create') }}" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'إضافة وحدة' : 'Add Unit' }}</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-secondary-800 bg-gray-50 dark:bg-secondary-900">
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">#</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'الصورة' : 'Image' }}</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Title' }}</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th class="px-4 py-3 text-start font-semibold text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @forelse ($units as $unit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                            <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">{{ $unit->id }}</td>
                            <td class="px-4 py-3">
                                @if ($unit->image_url)
                                    <img class="w-12 h-12 rounded-lg object-cover" src="{{ $unit->image_url }}" alt="" />
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-secondary-800"></div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-secondary-900 dark:text-secondary-50">
                                {{ $unit->title }}
                                @if ($unit->unit_number)
                                    <span class="text-xs text-secondary-500 dark:text-secondary-400">({{ $unit->unit_number }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">{{ $unit->type_label }} · {{ $unit->price_type_label }}</td>
                            <td class="px-4 py-3 text-secondary-700 dark:text-secondary-200">
                                @if ($unit->price)
                                    {{ number_format($unit->price, 0) }} {{ $unit->currency }}
                                @else
                                    <span class="text-secondary-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'reserved'  => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'sold'      => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'rented'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$unit->status] ?? '' }}">{{ $unit->status_label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.units.edit', $unit) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    <form method="POST" action="{{ route('admin.units.destroy', $unit) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-secondary-500 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'لا توجد وحدات' : 'No units found' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $units->links() }}</div>
@endsection
