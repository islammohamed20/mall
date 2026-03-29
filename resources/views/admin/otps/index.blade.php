@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'رموز التحقق (OTPs)' : 'OTPs' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'عرض كل رموز التحقق التي تم توليدها للمستخدمين.' : 'View all generated OTP verification codes.' }}</p>
            </div>
        </div>

        <form class="admin-card p-4 grid grid-cols-1 sm:grid-cols-6 gap-3" method="GET" action="{{ route('admin.otps.index') }}">
            <div class="sm:col-span-2">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</label>
                <input class="form-input" name="email" value="{{ request('email') }}" placeholder="user@example.com" />
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}</label>
                <select class="form-input" name="type">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                    <option value="register" @selected(request('type') === 'register')>{{ app()->getLocale() === 'ar' ? 'تسجيل' : 'Register' }}</option>
                    <option value="reset_password" @selected(request('type') === 'reset_password')>{{ app()->getLocale() === 'ar' ? 'استعادة كلمة المرور' : 'Reset password' }}</option>
                </select>
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'مستخدم' : 'Used' }}</label>
                <select class="form-input" name="used">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                    <option value="1" @selected(request('used') === '1')>{{ app()->getLocale() === 'ar' ? 'نعم' : 'Yes' }}</option>
                    <option value="0" @selected(request('used') === '0')>{{ app()->getLocale() === 'ar' ? 'لا' : 'No' }}</option>
                </select>
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'منتهي' : 'Expired' }}</label>
                <select class="form-input" name="expired">
                    <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                    <option value="1" @selected(request('expired') === '1')>{{ app()->getLocale() === 'ar' ? 'نعم' : 'Yes' }}</option>
                    <option value="0" @selected(request('expired') === '0')>{{ app()->getLocale() === 'ar' ? 'لا' : 'No' }}</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button class="btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Filter' }}</button>
                <a class="btn-outline" href="{{ route('admin.otps.index') }}">{{ app()->getLocale() === 'ar' ? 'مسح' : 'Reset' }}</a>
            </div>

            <div class="sm:col-span-3">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'من تاريخ' : 'From' }}</label>
                <input class="form-input" type="date" name="from" value="{{ request('from') }}" />
            </div>
            <div class="sm:col-span-3">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'إلى تاريخ' : 'To' }}</label>
                <input class="form-input" type="date" name="to" value="{{ request('to') }}" />
            </div>
        </form>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-secondary-900/50 text-secondary-600 dark:text-secondary-300">
                        <tr>
                            <th class="text-start px-4 py-3">ID</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'الكود' : 'Code' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'انتهاء' : 'Expires' }}</th>
                            <th class="text-start px-4 py-3">{{ app()->getLocale() === 'ar' ? 'إنشاء' : 'Created' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($otps as $otp)
                            @php
                                $isExpired = $otp->expires_at && $otp->expires_at->lte(now());
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-secondary-900/40">
                                <td class="px-4 py-3 text-secondary-500">{{ $otp->id }}</td>
                                <td class="px-4 py-3 font-medium text-secondary-900 dark:text-secondary-50">{{ $otp->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-info">{{ $otp->type }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono">{{ $otp->code }}</td>
                                <td class="px-4 py-3">
                                    @if ($otp->used)
                                        <span class="badge badge-success">{{ app()->getLocale() === 'ar' ? 'مستخدم' : 'Used' }}</span>
                                    @elseif ($isExpired)
                                        <span class="badge badge-warning">{{ app()->getLocale() === 'ar' ? 'منتهي' : 'Expired' }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-secondary-600 dark:text-secondary-300">{{ $otp->expires_at?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-secondary-600 dark:text-secondary-300">{{ $otp->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-secondary-500">{{ app()->getLocale() === 'ar' ? 'لا يوجد بيانات.' : 'No results.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $otps->links() }}
            </div>
        </div>
    </div>
@endsection
