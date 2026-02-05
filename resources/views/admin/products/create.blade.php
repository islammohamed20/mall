@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ $shop->name }}</div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إضافة منتج' : 'Create Product' }}</h1>
            </div>
            <a class="btn-outline" href="{{ route('admin.shops.products.index', $shop) }}">{{ app()->getLocale() === 'ar' ? 'رجوع' : 'Back' }}</a>
        </div>

        <form class="mt-8 admin-card p-6 space-y-6" method="POST" action="{{ route('admin.shops.products.store', $shop) }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم عربي' : 'Name (AR)' }}</label>
                    <input class="form-input" name="name_ar" value="{{ old('name_ar') }}" required />
                    @error('name_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم English' : 'Name (EN)' }}</label>
                    <input class="form-input" name="name_en" value="{{ old('name_en') }}" required />
                    @error('name_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Slug</label>
                    <input class="form-input" name="slug" value="{{ old('slug') }}" />
                    @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">SKU</label>
                    <input class="form-input" name="sku" value="{{ old('sku') }}" />
                    @error('sku') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</label>
                    <input class="form-input" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" />
                    @error('price') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'السعر قبل الخصم' : 'Old price' }}</label>
                    <input class="form-input" type="number" step="0.01" min="0" name="old_price" value="{{ old('old_price') }}" />
                    @error('old_price') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العملة' : 'Currency' }}</label>
                    <input class="form-input" name="currency" value="{{ old('currency', 'EGP') }}" />
                    @error('currency') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف عربي' : 'Description (AR)' }}</label>
                    <textarea class="form-input" rows="4" name="description_ar">{{ old('description_ar') }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'Description EN' : 'Description (EN)' }}</label>
                    <textarea class="form-input" rows="4" name="description_en">{{ old('description_en') }}</textarea>
                </div>
            </div>

            @if (isset($attributes) && $attributes->count())
                <div class="border-t border-gray-100 pt-4 mt-2">
                    <div class="text-sm font-semibold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'خصائص المنتج حسب التصنيف' : 'Category specific attributes' }}
                    </div>
                    <div class="mt-4 space-y-4">
                        @foreach ($attributes as $attribute)
                            @php
                                $options = collect(preg_split('/\r\n|\r|\n/', (string) $attribute->options))->filter();
                            @endphp
                            <div>
                                <label class="form-label">
                                    {{ app()->getLocale() === 'ar' ? $attribute->name_ar : $attribute->name_en }}
                                </label>

                                @if ($attribute->input_type === 'text')
                                    <input
                                        class="form-input"
                                        name="attributes[{{ $attribute->id }}]"
                                        value="{{ old('attributes.'.$attribute->id) }}"
                                    >
                                @elseif ($attribute->input_type === 'select')
                                    <select
                                        class="form-input"
                                        name="attributes[{{ $attribute->id }}]"
                                    >
                                        <option value="">{{ app()->getLocale() === 'ar' ? 'اختر...' : 'Select...' }}</option>
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}" @selected(old('attributes.'.$attribute->id) == $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($attribute->input_type === 'multi_select')
                                    @php
                                        $selected = collect(old('attributes.'.$attribute->id, []));
                                    @endphp
                                    <select
                                        class="form-input"
                                        name="attributes[{{ $attribute->id }}][]"
                                        multiple
                                    >
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}" @selected($selected->contains($option))>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($attribute->input_type === 'checkbox')
                                    <div class="flex items-center gap-2 mt-2">
                                        <input
                                            type="checkbox"
                                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950"
                                            name="attributes[{{ $attribute->id }}]"
                                            value="1"
                                            @checked(old('attributes.'.$attribute->id))
                                        >
                                        <span class="text-sm text-secondary-700 dark:text-secondary-200">
                                            {{ app()->getLocale() === 'ar' ? 'مفعل' : 'Enabled' }}
                                        </span>
                                    </div>
                                @endif

                                @error('attributes.'.$attribute->id)
                                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'صور المنتج' : 'Product images' }}</label>
                    <div
                        class="mt-1 border-2 border-dashed border-gray-300 rounded-xl px-4 py-6 text-center cursor-pointer hover:border-primary-400 hover:bg-primary-50/40 dark:border-secondary-700 dark:hover:border-primary-500 dark:hover:bg-secondary-900"
                        data-dropzone="product-images-upload"
                    >
                        <div class="text-sm text-secondary-700 dark:text-secondary-200">
                            {{ app()->getLocale() === 'ar' ? 'اسحب الصور هنا أو اضغط للاختيار' : 'Drag images here or click to select' }}
                        </div>
                        <div class="mt-1 text-xs text-secondary-500 dark:text-secondary-400">
                            {{ app()->getLocale() === 'ar' ? 'يمكنك اختيار أكثر من صورة في نفس الوقت' : 'You can select multiple images at once' }}
                        </div>
                        <input class="hidden" type="file" name="images[]" accept="image/*" multiple data-dropzone-input>
                    </div>
                    <div class="mt-2 text-xs text-secondary-600 dark:text-secondary-300 break-words" data-dropzone-preview></div>
                    @error('images') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    @error('images.*') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب' : 'Sort order' }}</label>
                        <input class="form-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" />
                    </div>
                    <div class="flex items-center gap-2 pt-7">
                        <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-secondary-700 dark:bg-secondary-950" type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) />
                        <span class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                    </div>
                </div>
            </div>

            <button class="btn-primary w-full sm:w-auto" type="submit">{{ app()->getLocale() === 'ar' ? 'حفظ' : 'Save' }}</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var zones = document.querySelectorAll('[data-dropzone="product-images-upload"]');
            zones.forEach(function (zone) {
                var input = zone.querySelector('[data-dropzone-input]');
                var preview = zone.parentElement.querySelector('[data-dropzone-preview]');

                zone.addEventListener('click', function () {
                    if (input) {
                        input.click();
                    }
                });

                zone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    zone.classList.add('border-primary-400', 'bg-primary-50/40');
                });

                zone.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    zone.classList.remove('border-primary-400', 'bg-primary-50/40');
                });

                zone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    zone.classList.remove('border-primary-400', 'bg-primary-50/40');
                    if (!input || !e.dataTransfer || !e.dataTransfer.files || !e.dataTransfer.files.length) {
                        return;
                    }
                    input.files = e.dataTransfer.files;
                    updatePreview();
                });

                if (input) {
                    input.addEventListener('change', updatePreview);
                }

                function updatePreview() {
                    if (!preview || !input || !input.files) {
                        return;
                    }
                    if (!input.files.length) {
                        preview.textContent = '';
                        return;
                    }
                    var names = [];
                    for (var i = 0; i < input.files.length; i++) {
                        names.push(input.files[i].name);
                    }
                    preview.textContent = names.join(', ');
                }
            });
        });
    </script>
@endsection
