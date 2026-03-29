@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إدارة العملاء' : 'Customer Management' }}</h1>
        <a href="{{ route('admin.customers.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ app()->getLocale() === 'ar' ? 'إضافة عميل' : 'Add Customer' }}
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                    <tr>
                        <th class="text-start px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                        <th class="text-start px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th class="text-start px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}</th>
                        <th class="text-start px-3 md:px-5 py-3 font-semibold whitespace-nowrap hidden md:table-cell">{{ app()->getLocale() === 'ar' ? 'تاريخ التسجيل' : 'Registered At' }}</th>
                        <th class="text-end px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                            <td class="px-3 md:px-5 py-3 font-medium text-secondary-900 dark:text-white whitespace-nowrap">{{ $customer->name }}</td>
                            <td class="px-3 md:px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $customer->email }}</td>
                            <td class="px-3 md:px-5 py-3 text-secondary-600 dark:text-secondary-300 whitespace-nowrap">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-3 md:px-5 py-3 text-secondary-600 dark:text-secondary-300 whitespace-nowrap hidden md:table-cell">{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td class="px-3 md:px-5 py-3 whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-secondary-sm">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
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
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-secondary-700">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
