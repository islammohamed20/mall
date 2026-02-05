@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-md mx-auto px-4 lg:px-8">
            <div class="card p-6 lg:p-10">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-4">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور' : 'Forgot Password' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني وسنرسل لك رمز تحقق لاستعادة كلمة المرور.' : 'Enter your email and we\'ll send you a verification code to reset your password.' }}
                    </p>
                </div>

                <form class="space-y-4" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <input class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus />
                        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn-primary w-full" type="submit">
                        {{ app()->getLocale() === 'ar' ? 'إرسال رمز التحقق' : 'Send Verification Code' }}
                    </button>
                </form>

                <div class="mt-6 text-sm text-center text-secondary-700 dark:text-secondary-300">
                    <a class="text-primary-700 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('login') }}">
                        ← {{ app()->getLocale() === 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Login' }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
