@extends('layouts.admin')

@section('title', 'تفاصيل المنشور')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">تفاصيل المنشور</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">عرض تفاصيل المنشور ومعلومات النشر</p>
        </div>
        <a href="{{ route('admin.facebook-posts.outgoing.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-secondary-800 rounded-lg hover:bg-gray-200 dark:hover:bg-secondary-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            رجوع
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Post Card -->
    <div class="bg-white dark:bg-secondary-950 rounded-xl shadow-sm border border-gray-200 dark:border-secondary-700 overflow-hidden">
        <!-- Status Banner -->
        <div class="px-6 py-3 border-b border-gray-200 dark:border-secondary-700
            @if($post->status === 'published') bg-green-50
            @elseif($post->status === 'failed') bg-red-50
            @elseif($post->status === 'pending' || $post->status === 'publishing') bg-yellow-50
            @else bg-gray-50 dark:bg-secondary-900
            @endif">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {!! $post->status_badge !!}
                    @if($post->retry_count > 0)
                        <span class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">
                            ({{ $post->retry_count }} محاولات)
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    @if(!$post->isPublished())
                        <a href="{{ route('admin.facebook-posts.outgoing.edit', $post) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-blue-600 bg-white dark:bg-secondary-950 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            تعديل
                        </a>
                    @endif
                    
                    @if($post->status === 'draft')
                        <form action="{{ route('admin.facebook-posts.outgoing.publish', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                نشر الآن
                            </button>
                        </form>
                    @endif
                    
                    @if($post->canRetry())
                        <form action="{{ route('admin.facebook-posts.outgoing.retry', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                إعادة المحاولة
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Shop Info -->
            <div class="flex items-center gap-4 pb-4 border-b border-gray-200 dark:border-secondary-700">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $post->shop->name ?? 'غير محدد' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">
                        @if($post->post_type === 'text')
                            منشور نصي
                        @elseif($post->post_type === 'photo')
                            منشور صورة
                        @else
                            منشور رابط
                        @endif
                    </p>
                </div>
            </div>

            <!-- Image -->
            @if($post->image)
                <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-secondary-700">
                    <img src="{{ $post->image_url }}" alt="" class="w-full max-h-96 object-contain bg-gray-100 dark:bg-secondary-800">
                </div>
            @endif

            <!-- Message -->
            @if($post->message)
                <div class="bg-gray-50 dark:bg-secondary-900 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @if($post->post_type === 'photo')
                            وصف الصورة
                        @else
                            نص المنشور
                        @endif
                    </h4>
                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $post->message }}</p>
                </div>
            @endif

            <!-- Link -->
            @if($post->link_url)
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الرابط</h4>
                    <a href="{{ $post->link_url }}" target="_blank" class="text-blue-600 hover:underline break-all">
                        {{ $post->link_url }}
                    </a>
                </div>
            @endif

            <!-- Error Message -->
            @if($post->error_message)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-red-700 mb-1">رسالة الخطأ</h4>
                    <p class="text-red-600 text-sm">{{ $post->error_message }}</p>
                </div>
            @endif

            <!-- Facebook Link -->
            @if($post->facebook_permalink)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-green-700 mb-2">المنشور على فيسبوك</h4>
                    <a href="{{ $post->facebook_permalink }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-green-600 hover:underline">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        عرض المنشور على فيسبوك
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    
                    @if($post->isPublished())
                        <form action="{{ route('admin.facebook-posts.outgoing.delete-from-facebook', $post) }}" method="POST" class="mt-3"
                              onsubmit="return confirm('هل أنت متأكد من حذف المنشور من فيسبوك؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                حذف من فيسبوك
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Meta Info -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-secondary-700">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">تاريخ الإنشاء</span>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                </div>
                @if($post->published_at)
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">تاريخ النشر</span>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $post->published_at->format('Y/m/d H:i') }}</p>
                    </div>
                @endif
                @if($post->user)
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">أنشأه</span>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $post->user->name }}</p>
                    </div>
                @endif
                @if($post->facebook_post_id)
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">Facebook Post ID</span>
                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm font-mono">{{ $post->facebook_post_id }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-secondary-900 border-t border-gray-200 dark:border-secondary-700 flex justify-between">
            <form action="{{ route('admin.facebook-posts.outgoing.destroy', $post) }}" method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنشور؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-red-600 hover:text-red-800 transition">
                    حذف المنشور
                </button>
            </form>
            
            <a href="{{ route('admin.facebook-posts.outgoing.index') }}" 
               class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-secondary-950 border border-gray-300 dark:border-secondary-600 rounded-lg hover:bg-gray-50 dark:hover:bg-secondary-800 dark:bg-secondary-900 transition">
                العودة للقائمة
            </a>
        </div>
    </div>
</div>
@endsection
