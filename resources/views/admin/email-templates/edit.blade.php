@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل القالب' : 'Edit Template' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.email-templates.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-6" method="POST" action="{{ route('admin.email-templates.update', $emailTemplate) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم عربي' : 'Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar', $emailTemplate->name_ar) }}" required>
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم English' : 'Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en', $emailTemplate->name_en) }}" required>
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع عربي' : 'Subject (AR)' }}</label>
                    <input class="form-input" name="subject_ar" value="{{ old('subject_ar', $emailTemplate->subject_ar) }}" required>
                    @error('subject_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع English' : 'Subject (EN)' }}</label>
                    <input class="form-input" name="subject_en" value="{{ old('subject_en', $emailTemplate->subject_en) }}" required>
                    @error('subject_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            @if($emailTemplate->variables && count($emailTemplate->variables) > 0)
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-2">{{ app()->getLocale() === 'ar' ? 'المتغيرات المتاحة:' : 'Available Variables:' }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($emailTemplate->variables as $variable)
                            <span class="px-2 py-1 bg-white dark:bg-secondary-800 text-blue-700 dark:text-blue-300 rounded text-sm font-mono border border-blue-200 dark:border-blue-800">
                                @{{ {{ $variable }} }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                        {{ app()->getLocale() === 'ar' ? 'يمكنك استخدام هذه المتغيرات في محتوى الرسالة.' : 'You can use these variables in the message body.' }}
                    </p>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى عربي' : 'Body (AR)' }}</label>
                    <textarea class="form-input font-mono text-sm" rows="10" name="body_ar" required>{{ old('body_ar', $emailTemplate->body_ar) }}</textarea>
                    @error('body_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المحتوى English' : 'Body (EN)' }}</label>
                    <textarea class="form-input font-mono text-sm" rows="10" name="body_en" required>{{ old('body_en', $emailTemplate->body_en) }}</textarea>
                    @error('body_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input
                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950"
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $emailTemplate->is_active))
                >
                <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-secondary-800 flex justify-end">
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </div>
@endsection
