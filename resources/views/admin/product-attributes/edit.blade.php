@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'تعديل خاصية' : 'Edit Attribute' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.product-attributes.index') }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-6" method="POST" action="{{ route('admin.product-attributes.update', $attribute) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم عربي' : 'Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar', $attribute->name_ar) }}" required>
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم English' : 'Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en', $attribute->name_en) }}" required>
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Slug</label>
                    <input class="form-input" name="slug" value="{{ old('slug', $attribute->slug) }}">
                    @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نوع الحقل' : 'Input type' }}</label>
                    <select class="form-input" name="input_type" required>
                        <option value="text" @selected(old('input_type', $attribute->input_type) === 'text')>Text</option>
                        <option value="select" @selected(old('input_type', $attribute->input_type) === 'select')>Select</option>
                        <option value="checkbox" @selected(old('input_type', $attribute->input_type) === 'checkbox')>Checkbox</option>
                        <option value="multi_select" @selected(old('input_type', $attribute->input_type) === 'multi_select')>Multi select</option>
                    </select>
                    @error('input_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'خيارات (اختياري، سطر لكل خيار)' : 'Options (optional, one per line)' }}</label>
                    <textarea class="form-input" rows="4" name="options">{{ old('options', $attribute->options) }}</textarea>
                    @error('options') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'قواعد التحقق (Laravel rules)' : 'Validation rules (Laravel)' }}</label>
                    <input class="form-input" name="validation_rules" value="{{ old('validation_rules', $attribute->validation_rules) }}">
                    @error('validation_rules') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ربط بالتصنيفات' : 'Attach to categories' }}</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach ($categories as $category)
                            <label class="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950"
                                    name="category_ids[]"
                                    value="{{ $category->id }}"
                                    @checked(in_array($category->id, old('category_ids', $selectedCategories)))
                                >
                                <span class="text-secondary-700 dark:text-secondary-200">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category_ids') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    @error('category_ids.*') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                        <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $attribute->sort_order) }}">
                        @error('sort_order') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="flex items-center gap-2 pt-7">
                        <input
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950"
                            type="checkbox"
                            name="is_required"
                            value="1"
                            @checked(old('is_required', $attribute->categories()->wherePivot('is_required', true)->exists()))
                        >
                        <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'حقل إجباري' : 'Required field' }}</span>
                    </div>
                    <div class="flex items-center gap-2 pt-7">
                        <input
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950"
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', $attribute->is_active))
                        >
                        <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                    </div>
                </div>
            </div>

            <button class="btn-primary w-full sm:w-auto" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>
@endsection

