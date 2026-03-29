@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-md mx-auto px-4 lg:px-8">
            <div class="card p-6 lg:p-10">
                <h1 class="text-2xl font-bold text-secondary-900">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</h1>
                <form class="mt-6 space-y-4" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <input class="form-input" type="email" name="email" value="{{ old('email') }}" required />
                        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input class="form-input" type="password" name="password" required />
                        @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm text-secondary-700">
                        <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                        <span>{{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}</span>
                    </label>
                    <button class="btn-primary w-full" type="submit">{{ app()->getLocale() === 'ar' ? 'دخول' : 'Login' }}</button>
                </form>

                <div class="mt-4 text-center">
                    <a class="text-sm text-primary-700 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('password.request') }}">
                        {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot your password?' }}
                    </a>
                </div>

                <div class="mt-4 text-sm text-secondary-700 dark:text-secondary-300">
                    {{ app()->getLocale() === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
                    <a class="text-primary-700 hover:text-primary-800" href="{{ route('register') }}">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection

