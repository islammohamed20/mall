@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'Outbox البريد' : 'Email Outbox' }}</h1>
            <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'سجل الإيميلات التي تم إرسالها من النظام.' : 'Log of emails sent by the system.' }}</p>
        </div>

        <form class="admin-card p-4 grid grid-cols-1 sm:grid-cols-6 gap-3" method="GET" action="{{ route('admin.emails.outbox.index') }}">
            <div class="sm:col-span-3">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}</label>
                <input class="form-input" name="q" value="{{ request('q') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'العنوان أو البريد أو الكلاس' : 'subject, email, class...' }}" />
            </div>
            <div class="sm:col-span-1">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'من' : 'From' }}</label>
                <input class="form-input" type="date" name="from_date" value="{{ request('from_date') }}" />
            </div>
            <div class="sm:col-span-1">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</label>
                <input class="form-input" type="date" name="to_date" value="{{ request('to_date') }}" />
            </div>
            <div class="sm:col-span-1 flex flex-wrap items-end justify-end gap-2">
                <button class="btn-primary btn-sm" type="submit">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Filter' }}</button>
                <a class="btn-outline btn-sm" href="{{ route('admin.emails.outbox.index') }}">{{ app()->getLocale() === 'ar' ? 'مسح' : 'Reset' }}</a>
            </div>
        </form>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-secondary-900/50 text-secondary-600 dark:text-secondary-300">
                        <tr>
                            <th class="text-start px-4 py-3">ID</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'إلى' : 'To' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Subject' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Mailable' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'وقت الإرسال' : 'Sent at' }}</th>
                            <th class="text-start px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($emails as $email)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-secondary-900/40">
                                <td class="px-4 py-3 text-secondary-500">{{ $email->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-secondary-900 dark:text-secondary-50">{{ is_array($email->to) ? implode(', ', $email->to) : (string) $email->to }}</div>
                                </td>
                                <td class="px-4 py-3 font-medium text-secondary-900 dark:text-secondary-50">{{ $email->subject }}</td>
                                <td class="px-4 py-3 text-secondary-600 dark:text-secondary-300">{{ $email->mailable ?? '-' }}</td>
                                <td class="px-4 py-3 text-secondary-600 dark:text-secondary-300">{{ $email->sent_at?->format('Y-m-d H:i') ?? $email->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-end">
                                    <a class="btn-outline" href="{{ route('admin.emails.outbox.show', $email) }}">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-secondary-500">{{ app()->getLocale() === 'ar' ? 'لا يوجد بيانات.' : 'No results.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">{{ $emails->links() }}</div>
        </div>
    </div>
@endsection
