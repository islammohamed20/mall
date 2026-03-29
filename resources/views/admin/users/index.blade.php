@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إدارة المستخدمين' : 'User Management' }}</h1>
        <a href="{{ route('admin.users.create') }}" class="btn-primary w-full sm:w-auto text-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ app()->getLocale() === 'ar' ? 'إضافة مستخدم' : 'Add User' }}
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm admin-table">
                <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                    <tr>
                        <th class="text-start px-2 sm:px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                        <th class="text-start px-2 sm:px-3 md:px-5 py-3 font-semibold whitespace-nowrap hidden sm:table-cell">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th class="text-start px-2 sm:px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}</th>
                        <th class="text-start px-2 sm:px-3 md:px-5 py-3 font-semibold whitespace-nowrap hidden lg:table-cell">{{ app()->getLocale() === 'ar' ? 'تاريخ التسجيل' : 'Registered At' }}</th>
                        <th class="text-end px-2 sm:px-3 md:px-5 py-3 font-semibold whitespace-nowrap">{{ app()->getLocale() === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                            <td class="px-2 sm:px-3 md:px-5 py-3 font-medium text-secondary-900 dark:text-white">
                                <div class="whitespace-nowrap">{{ $user->name }}</div>
                                <div class="text-xs text-secondary-500 dark:text-secondary-400 sm:hidden truncate">{{ $user->email }}</div>
                            </td>
                            <td class="px-2 sm:px-3 md:px-5 py-3 text-secondary-600 dark:text-secondary-300 hidden sm:table-cell">{{ $user->email }}</td>
                            <td class="px-2 sm:px-3 md:px-5 py-3 whitespace-nowrap">
                                <span class="badge badge-info text-xs">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-2 sm:px-3 md:px-5 py-3 text-secondary-600 dark:text-secondary-300 whitespace-nowrap hidden lg:table-cell">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-2 sm:px-3 md:px-5 py-3 whitespace-nowrap">
                                <div class="flex justify-end gap-1 sm:gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary-sm text-xs px-2 py-1">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-sm text-xs px-2 py-1">{{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-secondary-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
