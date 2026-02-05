@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'رسائل التواصل من الموقع.' : 'Contact messages from the website.' }}</p>
            </div>
            <form class="flex gap-2" method="GET" action="{{ route('admin.messages.index') }}">
                <select class="form-input" name="status">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'كل الحالات' : 'All statuses' }}</option>
                    <option value="new" @selected(request('status') === 'new')>{{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}</option>
                    <option value="read" @selected(request('status') === 'read')>{{ app()->getLocale() === 'ar' ? 'مقروء' : 'Read' }}</option>
                    <option value="replied" @selected(request('status') === 'replied')>{{ app()->getLocale() === 'ar' ? 'تم الرد' : 'Replied' }}</option>
                    <option value="archived" @selected(request('status') === 'archived')>{{ app()->getLocale() === 'ar' ? 'مؤرشف' : 'Archived' }}</option>
                </select>
                <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}</button>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">#</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المرسل' : 'Sender' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'حالة' : 'Status' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @foreach ($messages as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900">
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $m->id }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $m->name }}</div>
                                    <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ $m->email }}</div>
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">{{ $m->subject }}</td>
                                <td class="px-5 py-3"><span class="badge {{ $m->status_color }}">{{ $m->status_label }}</span></td>
                                <td class="px-5 py-3 text-end">
                                    <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ route('admin.messages.show', $m) }}">{{ app()->getLocale() === 'ar' ? 'فتح' : 'Open' }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
@endsection
