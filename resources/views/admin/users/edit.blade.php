@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل المستخدم' : 'Edit User' }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'عودة' : 'Back' }}</a>
    </div>

    <div class="card p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الدور' : 'Role' }}</label>
                    <select name="role" class="form-input" required>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }} <span class="text-xs text-secondary-400 font-normal">({{ app()->getLocale() === 'ar' ? 'اتركه فارغاً للإبقاء على الحالية' : 'Leave empty to keep current' }})</span></label>
                    <input type="password" name="password" class="form-input">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'تحديث' : 'Update' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
