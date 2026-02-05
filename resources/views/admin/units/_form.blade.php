<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المعلومات الأساسية' : 'Basic Info' }}</h2>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان (عربي)' : 'Title (AR)' }} *</label>
            <input class="form-input" name="title_ar" value="{{ old('title_ar', $unit->title_ar ?? '') }}" required />
            @error('title_ar') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان (إنجليزي)' : 'Title (EN)' }} *</label>
            <input class="form-input" name="title_en" value="{{ old('title_en', $unit->title_en ?? '') }}" required />
            @error('title_en') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف (عربي)' : 'Description (AR)' }}</label>
            <textarea class="form-input" rows="3" name="description_ar">{{ old('description_ar', $unit->description_ar ?? '') }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الوصف (إنجليزي)' : 'Description (EN)' }}</label>
            <textarea class="form-input" rows="3" name="description_en">{{ old('description_en', $unit->description_en ?? '') }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الصورة' : 'Image' }}</label>
            @if (isset($unit) && $unit->image_url)
                <div class="mb-2"><img class="w-32 h-24 rounded-lg object-cover" src="{{ $unit->image_url }}" alt="" /></div>
            @endif
            <input class="form-input" type="file" name="image" accept="image/*" />
            @error('image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'التفاصيل' : 'Details' }}</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الوحدة' : 'Unit Number' }}</label>
                <input class="form-input" name="unit_number" value="{{ old('unit_number', $unit->unit_number ?? '') }}" />
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الطابق' : 'Floor' }}</label>
                <select class="form-input" name="floor_id">
                    <option value="">--</option>
                    @foreach ($floors as $floor)
                        <option value="{{ $floor->id }}" {{ old('floor_id', $unit->floor_id ?? '') == $floor->id ? 'selected' : '' }}>{{ $floor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المساحة (م²)' : 'Area (sqm)' }}</label>
                <input class="form-input" type="number" step="0.01" name="area" value="{{ old('area', $unit->area ?? '') }}" />
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'النوع' : 'Type' }} *</label>
                <select class="form-input" name="type" required>
                    <option value="shop" {{ old('type', $unit->type ?? 'shop') === 'shop' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'محل تجاري' : 'Shop' }}</option>
                    <option value="office" {{ old('type', $unit->type ?? '') === 'office' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مكتب' : 'Office' }}</option>
                    <option value="kiosk" {{ old('type', $unit->type ?? '') === 'kiosk' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'كشك' : 'Kiosk' }}</option>
                    <option value="storage" {{ old('type', $unit->type ?? '') === 'storage' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مخزن' : 'Storage' }}</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</label>
                <input class="form-input" type="number" step="0.01" name="price" value="{{ old('price', $unit->price ?? '') }}" />
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العملة' : 'Currency' }}</label>
                <input class="form-input" name="currency" value="{{ old('currency', $unit->currency ?? 'EGP') }}" />
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نوع السعر' : 'Price Type' }} *</label>
                <select class="form-input" name="price_type" required>
                    <option value="sale" {{ old('price_type', $unit->price_type ?? 'sale') === 'sale' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'بيع' : 'Sale' }}</option>
                    <option value="rent" {{ old('price_type', $unit->price_type ?? '') === 'rent' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'إيجار' : 'Rent' }}</option>
                </select>
            </div>
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }} *</label>
            <select class="form-input" name="status" required>
                <option value="available" {{ old('status', $unit->status ?? 'available') === 'available' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'متاح' : 'Available' }}</option>
                <option value="reserved" {{ old('status', $unit->status ?? '') === 'reserved' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'محجوز' : 'Reserved' }}</option>
                <option value="sold" {{ old('status', $unit->status ?? '') === 'sold' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مُباع' : 'Sold' }}</option>
                <option value="rented" {{ old('status', $unit->status ?? '') === 'rented' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مؤجّر' : 'Rented' }}</option>
            </select>
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المميزات (عربي)' : 'Features (AR)' }}</label>
            <textarea class="form-input" rows="3" name="features_ar" placeholder="{{ app()->getLocale() === 'ar' ? 'مميزة في كل سطر' : 'One feature per line' }}">{{ old('features_ar', $unit->features_ar ?? '') }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المميزات (إنجليزي)' : 'Features (EN)' }}</label>
            <textarea class="form-input" rows="3" name="features_en" placeholder="{{ app()->getLocale() === 'ar' ? 'مميزة في كل سطر' : 'One feature per line' }}">{{ old('features_en', $unit->features_en ?? '') }}</textarea>
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'بيانات التواصل' : 'Contact Info' }}</h2>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'هاتف التواصل' : 'Contact Phone' }}</label>
            <input class="form-input" name="contact_phone" value="{{ old('contact_phone', $unit->contact_phone ?? '') }}" />
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'بريد التواصل' : 'Contact Email' }}</label>
            <input class="form-input" type="email" name="contact_email" value="{{ old('contact_email', $unit->contact_email ?? '') }}" />
        </div>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'واتساب' : 'WhatsApp' }}</label>
            <input class="form-input" name="contact_whatsapp" value="{{ old('contact_whatsapp', $unit->contact_whatsapp ?? '') }}" />
        </div>
    </div>

    <div class="card p-6 space-y-4">
        <h2 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'خيارات أخرى' : 'Other Options' }}</h2>
        <div>
            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ترتيب العرض' : 'Sort Order' }}</label>
            <input class="form-input" type="number" name="sort_order" value="{{ old('sort_order', $unit->sort_order ?? 0) }}" />
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $unit->is_active ?? true) ? 'checked' : '' }} />
            <label for="is_active" class="text-sm text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'فعّال (ظاهر للزوار)' : 'Active (visible to visitors)' }}</label>
        </div>
    </div>
</div>
