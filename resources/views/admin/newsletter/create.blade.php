@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.newsletter.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة مشترك' : 'Add Subscriber' }}</h1>
    </div>

    <form action="{{ route('admin.newsletter.store') }}" method="POST" class="card p-6">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                <input type="email" class="form-input" name="email" value="{{ old('email') }}" required />
                @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                <input class="form-input" name="name" value="{{ old('name') }}" />
                @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox" />
                    <span class="text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
                <a href="{{ route('admin.newsletter.index') }}" class="btn-secondary">{{ app()->getLocale() === 'ar' ? 'إلغاء' : 'Cancel' }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
