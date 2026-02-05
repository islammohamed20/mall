@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-md mx-auto px-4 lg:px-8">
            <div class="card p-6 lg:p-10">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'تعيين كلمة مرور جديدة' : 'Set New Password' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'أدخل كلمة المرور الجديدة.' : 'Enter your new password.' }}
                    </p>
                </div>

                <form class="space-y-4" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                        <input class="form-input" type="password" name="password" required autofocus />
                        @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                        <input class="form-input" type="password" name="password_confirmation" required />
                    </div>
                    <button class="btn-primary w-full" type="submit">
                        {{ app()->getLocale() === 'ar' ? 'تغيير كلمة المرور' : 'Reset Password' }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
