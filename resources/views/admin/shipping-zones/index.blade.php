@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'مناطق الشحن' : 'Shipping Zones' }}</h1>
        <a href="{{ route('admin.shipping-zones.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ app()->getLocale() === 'ar' ? 'إضافة منطقة' : 'Add Zone' }}
        </a>
    </div>

    <div class="card overflow-hidden">
        @if($zones->isEmpty())
            <div class="p-10 text-center">
                <svg class="w-16 h-16 mx-auto text-secondary-400 dark:text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="mt-4 text-lg font-medium text-secondary-700 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'لا توجد مناطق شحن' : 'No shipping zones' }}</p>
            </div>
        @else
            <table class="table-auto">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() === 'ar' ? 'الترتيب' : 'Order' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'اسم المنطقة' : 'Zone Name' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'المحافظة' : 'Governorate' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'تكلفة الشحن' : 'Shipping Cost' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'مدة التوصيل' : 'Delivery Time' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zones as $zone)
                        <tr>
                            <td class="text-center">{{ $zone->order }}</td>
                            <td class="font-medium">{{ $zone->name }}</td>
                            <td>{{ $zone->governorate ?? '-' }}</td>
                            <td class="font-semibold">{{ number_format($zone->shipping_cost, 2) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</td>
                            <td>{{ $zone->estimated_days }} {{ app()->getLocale() === 'ar' ? 'أيام' : 'days' }}</td>
                            <td>
                                @if($zone->is_active)
                                    <span class="badge badge-success">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.shipping-zones.edit', $zone) }}" class="btn-secondary-sm">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    <form action="{{ route('admin.shipping-zones.destroy', $zone) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4 border-t border-gray-200 dark:border-secondary-700">
                {{ $zones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
