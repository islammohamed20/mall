@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ $gallery->title_ar }}</h1>
                <p class="mt-1 text-sm text-secondary-600 dark:text-secondary-400">{{ $gallery->title_en }}</p>
            </div>
            <div class="flex gap-2">
                <a class="btn-outline" href="{{ route('gallery.show', $gallery->slug) }}" target="_blank">{{ app()->getLocale() === 'ar' ? 'معاينة' : 'Preview' }}</a>
                <a class="btn-outline" href="{{ route('admin.galleries.edit', $gallery) }}">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</a>
                <a class="btn-outline" href="{{ route('admin.galleries.index') }}">{{ app()->getLocale() === 'ar' ? 'قائمة المعارض' : 'All Galleries' }}</a>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-4 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <!-- Gallery Info -->
        <div class="mt-8 admin-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-2">{{ app()->getLocale() === 'ar' ? 'معلومات المعرض' : 'Gallery Info' }}</h3>
                    <div class="space-y-2 text-sm">
                        @if ($gallery->shop)
                            <div><strong>{{ app()->getLocale() === 'ar' ? 'المحل:' : 'Shop:' }}</strong> {{ $gallery->shop->name_ar }}</div>
                        @endif
                        <div><strong>{{ app()->getLocale() === 'ar' ? 'عدد العناصر:' : 'Items:' }}</strong> {{ $gallery->items->count() }}</div>
                        <div><strong>{{ app()->getLocale() === 'ar' ? 'الحالة:' : 'Status:' }}</strong> 
                            @if ($gallery->is_featured)
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">{{ app()->getLocale() === 'ar' ? 'مميز' : 'Featured' }}</span>
                            @endif
                            @if ($gallery->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ app()->getLocale() === 'ar' ? 'نشط' : 'Active' }}</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">{{ app()->getLocale() === 'ar' ? 'غير نشط' : 'Inactive' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if ($gallery->cover_image_url)
                    <div>
                        <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-2">{{ app()->getLocale() === 'ar' ? 'صورة الغلاف' : 'Cover Image' }}</h3>
                        <img class="w-full h-48 object-cover rounded" src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title_ar }}">
                    </div>
                @endif
            </div>

            @if ($gallery->description_ar || $gallery->description_en)
                <div class="mt-4">
                    <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-2">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</h3>
                    @if ($gallery->description_ar)
                        <p class="text-sm text-secondary-700 dark:text-secondary-300 mb-2">{{ $gallery->description_ar }}</p>
                    @endif
                    @if ($gallery->description_en)
                        <p class="text-sm text-secondary-600 dark:text-secondary-400">{{ $gallery->description_en }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Upload New Items -->
        <div class="mt-8 admin-card p-6">
            <h3 class="font-semibold text-secondary-900 dark:text-secondary-50 mb-4">{{ app()->getLocale() === 'ar' ? 'إضافة صور أو فيديوهات' : 'Add Photos or Videos' }}</h3>
            <form method="POST" action="{{ route('admin.galleries.items.add', $gallery) }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اختر ملفات (صور أو فيديوهات)' : 'Choose Files (Images or Videos)' }}</label>
                    <input class="form-input" type="file" name="items[]" accept="image/*,video/*" multiple required />
                    <p class="mt-1 text-xs text-secondary-500">{{ app()->getLocale() === 'ar' ? 'يمكنك اختيار عدة ملفات في نفس الوقت' : 'You can select multiple files at once' }}</p>
                    @error('items') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <button class="mt-4 btn-primary" type="submit">{{ app()->getLocale() === 'ar' ? 'رفع الملفات' : 'Upload Files' }}</button>
            </form>
        </div>

        <!-- Gallery Items -->
        @if ($gallery->items->count() > 0)
            <div class="mt-8">
                <h3 class="text-xl font-bold text-secondary-900 dark:text-secondary-50 mb-4">{{ app()->getLocale() === 'ar' ? 'عناصر المعرض' : 'Gallery Items' }} ({{ $gallery->items->count() }})</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($gallery->items->sortBy('sort_order') as $item)
                        <div class="admin-card p-2 group relative">
                            @if ($item->type === 'image')
                                <img class="w-full h-40 object-cover rounded" src="{{ $item->thumbnail_url ?? $item->file_url }}" alt="{{ $item->title_ar }}">
                            @else
                                <div class="relative">
                                    <video class="w-full h-40 object-cover rounded" src="{{ $item->file_url }}"></video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded">
                                        <svg class="h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.galleries.items.delete', [$gallery, $item]) }}" class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full" type="submit">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                            
                            @if ($item->width && $item->height)
                                <p class="mt-1 text-xs text-secondary-500 text-center">{{ $item->width }} × {{ $item->height }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-8 admin-card p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-4 text-secondary-600 dark:text-secondary-400">{{ app()->getLocale() === 'ar' ? 'لا توجد عناصر في هذا المعرض بعد' : 'No items in this gallery yet' }}</p>
                <p class="mt-1 text-sm text-secondary-500">{{ app()->getLocale() === 'ar' ? 'استخدم النموذج أعلاه لإضافة صور أو فيديوهات' : 'Use the form above to add photos or videos' }}</p>
            </div>
        @endif
    </div>
@endsection
