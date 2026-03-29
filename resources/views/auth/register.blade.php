@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-md mx-auto px-4 lg:px-8">
            <div class="card p-6 lg:p-10">
                <h1 class="text-2xl font-bold text-secondary-900">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}</h1>
                <form class="mt-6 space-y-4" method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                        <input class="form-input" name="name" value="{{ old('name') }}" required />
                        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                        <input class="form-input" type="email" name="email" value="{{ old('email') }}" required />
                        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
                        <input class="form-input" name="phone" value="{{ old('phone') }}" required />
                        @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input class="form-input" type="password" name="password" required />
                        @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                        <input class="form-input" type="password" name="password_confirmation" required />
                    </div>
                    <button class="btn-primary w-full" type="submit">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Create account' }}</button>
                </form>

                <div class="mt-6 text-sm text-secondary-700">
                    {{ app()->getLocale() === 'ar' ? 'لديك حساب؟' : 'Already have an account?' }}
                    <a class="text-primary-700 hover:text-primary-800" href="{{ route('login') }}">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection

