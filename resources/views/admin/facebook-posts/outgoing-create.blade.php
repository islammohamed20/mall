@extends('layouts.admin')

@section('title', 'إنشاء منشور جديد')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">إنشاء منشور جديد</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">إنشاء منشور جديد للنشر على صفحة فيسبوك</p>
        </div>
        <a href="{{ route('admin.facebook-posts.outgoing.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-secondary-800 rounded-lg hover:bg-gray-200 dark:hover:bg-secondary-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            رجوع
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.facebook-posts.outgoing.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white dark:bg-secondary-950 rounded-xl shadow-sm border border-gray-200 dark:border-secondary-700" x-data="postForm()">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Shop Selection -->
            <div>
                <label for="shop_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المتجر *</label>
                <select name="shop_id" id="shop_id" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('shop_id') border-red-500 @enderror">
                    <option value="">اختر المتجر...</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
                @error('shop_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500">سيتم النشر على صفحة فيسبوك المرتبطة بهذا المتجر</p>
            </div>

            <!-- Post Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع المنشور *</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition"
                           :class="postType === 'text' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 dark:border-secondary-700 hover:border-gray-300 dark:border-secondary-600'">
                        <input type="radio" name="post_type" value="text" x-model="postType" class="sr-only" {{ old('post_type', 'text') === 'text' ? 'checked' : '' }}>
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="postType === 'text' ? 'text-blue-600' : 'text-gray-400 dark:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-medium" :class="postType === 'text' ? 'text-blue-700' : 'text-gray-600 dark:text-gray-400 dark:text-gray-500'">نص فقط</span>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition"
                           :class="postType === 'photo' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 dark:border-secondary-700 hover:border-gray-300 dark:border-secondary-600'">
                        <input type="radio" name="post_type" value="photo" x-model="postType" class="sr-only" {{ old('post_type') === 'photo' ? 'checked' : '' }}>
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="postType === 'photo' ? 'text-blue-600' : 'text-gray-400 dark:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-medium" :class="postType === 'photo' ? 'text-blue-700' : 'text-gray-600 dark:text-gray-400 dark:text-gray-500'">صورة</span>
                        </div>
                    </label>
                    
                    <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition"
                           :class="postType === 'link' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 dark:border-secondary-700 hover:border-gray-300 dark:border-secondary-600'">
                        <input type="radio" name="post_type" value="link" x-model="postType" class="sr-only" {{ old('post_type') === 'link' ? 'checked' : '' }}>
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="postType === 'link' ? 'text-blue-600' : 'text-gray-400 dark:text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="text-sm font-medium" :class="postType === 'link' ? 'text-blue-700' : 'text-gray-600 dark:text-gray-400 dark:text-gray-500'">رابط</span>
                        </div>
                    </label>
                </div>
                @error('post_type')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div x-show="postType !== 'photo' || true">
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span x-text="postType === 'photo' ? 'وصف الصورة' : 'نص المنشور'">نص المنشور</span>
                    <span x-show="postType !== 'photo'" class="text-red-500">*</span>
                </label>
                <textarea name="message" id="message" rows="5"
                          x-ref="message"
                          @input="updateCharCount()"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror"
                          placeholder="اكتب نص المنشور هنا...">{{ old('message') }}</textarea>
                <div class="flex justify-between mt-1">
                    @error('message')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500">الحد الأقصى: 63,206 حرف</p>
                    @enderror
                    <span class="text-xs text-gray-400 dark:text-gray-500" x-text="charCount + ' حرف'">0 حرف</span>
                </div>
            </div>

            <!-- Image Upload (for photo posts) -->
            <div x-show="postType === 'photo'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الصورة *</label>
                <div class="border-2 border-dashed border-gray-300 dark:border-secondary-600 rounded-lg p-6 text-center hover:border-blue-400 transition"
                     :class="imagePreview ? 'border-blue-400' : ''">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" @change="previewImage($event)">
                    
                    <template x-if="!imagePreview">
                        <label for="image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">انقر لرفع صورة</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">PNG, JPG, GIF حتى 10MB</p>
                        </label>
                    </template>
                    
                    <template x-if="imagePreview">
                        <div class="relative">
                            <img :src="imagePreview" alt="Preview" class="max-h-64 mx-auto rounded-lg">
                            <button type="button" @click="removeImage()" 
                                    class="absolute top-2 left-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link URL (for link posts) -->
            <div x-show="postType === 'link'" x-cloak>
                <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رابط URL *</label>
                <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('link_url') border-red-500 @enderror"
                       placeholder="https://example.com">
                @error('link_url')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500">سيظهر الرابط مع معاينة تلقائية على فيسبوك</p>
            </div>

            <!-- Publish Option -->
            <div class="bg-gray-50 dark:bg-secondary-900 rounded-lg p-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="publish_now" value="1" x-model="publishNow"
                           class="w-5 h-5 text-blue-600 border-gray-300 dark:border-secondary-600 rounded focus:ring-blue-500">
                    <div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">نشر فوري</span>
                        <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">سيتم إرسال المنشور للنشر على فيسبوك فوراً</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-secondary-900 border-t border-gray-200 dark:border-secondary-700 flex justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.facebook-posts.outgoing.index') }}" 
               class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-secondary-950 border border-gray-300 dark:border-secondary-600 rounded-lg hover:bg-gray-50 dark:hover:bg-secondary-800 dark:bg-secondary-900 transition">
                إلغاء
            </a>
            <button type="submit" 
                    class="px-6 py-2 text-white rounded-lg transition"
                    :class="publishNow ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'">
                <span x-show="!publishNow">حفظ كمسودة</span>
                <span x-show="publishNow">نشر الآن</span>
            </button>
        </div>
    </form>

    <!-- Tips -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="font-medium text-blue-800 mb-2">نصائح للنشر على فيسبوك:</h3>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• المنشورات التي تحتوي على صور تحصل على تفاعل أعلى بنسبة 2.3 مرة</li>
            <li>• يُفضل أن يكون طول النص بين 40-80 حرف للحصول على أفضل تفاعل</li>
            <li>• استخدم الوسوم (هاشتاج) بحكمة - 1-2 وسم كافية</li>
            <li>• أفضل أوقات النشر: 1-4 مساءً خلال أيام الأسبوع</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
function postForm() {
    return {
        postType: '{{ old('post_type', 'text') }}',
        publishNow: {{ old('publish_now') ? 'true' : 'false' }},
        imagePreview: null,
        charCount: 0,
        
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeImage() {
            this.imagePreview = null;
            document.getElementById('image').value = '';
        },
        
        updateCharCount() {
            this.charCount = this.$refs.message.value.length;
        }
    }
}
</script>
@endpush
@endsection
