@extends('layouts.admin')

@section('content')
<div class="max-w-2xl space-y-6 admin-content">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة مستخدم جديد' : 'Add New User' }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary w-full sm:w-auto text-center">{{ app()->getLocale() === 'ar' ? 'عودة' : 'Back' }}</a>
    </div>

    <div class="card p-3 sm:p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 sm:space-y-6 admin-form">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                    <input type="text" name="name" class="form-input text-sm" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" class="form-input text-sm" value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
                    <input type="text" name="phone" class="form-input text-sm" value="{{ old('phone') }}">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}</label>
                    <select name="role" class="form-input text-sm" required>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                    <input type="password" name="password" class="form-input text-sm" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label text-sm">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                    <input type="password" name="password_confirmation" class="form-input text-sm" required>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary w-full sm:w-auto">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
