@extends('layouts.admin')

@section('title', 'النشر على فيسبوك')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">النشر على فيسبوك</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">إدارة المنشورات المرسلة لصفحات فيسبوك</p>
        </div>
        <a href="{{ route('admin.facebook-posts.outgoing.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            منشور جديد
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

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary-950 rounded-xl shadow-sm border border-gray-200 dark:border-secondary-700 p-4">
        <form method="GET" action="{{ route('admin.facebook-posts.outgoing.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="بحث في النص..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select name="shop_id" class="px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل المتاجر</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل الحالات</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="publishing" {{ request('status') == 'publishing' ? 'selected' : '' }}>جاري النشر</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>تم النشر</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                </select>
            </div>
            <div>
                <select name="post_type" class="px-4 py-2 border border-gray-300 dark:border-secondary-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل الأنواع</option>
                    <option value="text" {{ request('post_type') == 'text' ? 'selected' : '' }}>نص</option>
                    <option value="photo" {{ request('post_type') == 'photo' ? 'selected' : '' }}>صورة</option>
                    <option value="link" {{ request('post_type') == 'link' ? 'selected' : '' }}>رابط</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-secondary-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-secondary-700 transition">
                تصفية
            </button>
            @if(request()->hasAny(['search', 'shop_id', 'status', 'post_type']))
                <a href="{{ route('admin.facebook-posts.outgoing.index') }}" class="px-4 py-2 text-gray-500 dark:text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-200 dark:text-gray-300">
                    إعادة تعيين
                </a>
            @endif
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
            $allPosts = \App\Models\FacebookPostOutgoing::query();
            $draftCount = (clone $allPosts)->where('status', 'draft')->count();
            $pendingCount = (clone $allPosts)->where('status', 'pending')->count();
            $publishingCount = (clone $allPosts)->where('status', 'publishing')->count();
            $publishedCount = (clone $allPosts)->where('status', 'published')->count();
            $failedCount = (clone $allPosts)->where('status', 'failed')->count();
        @endphp
        <div class="bg-white dark:bg-secondary-950 rounded-lg shadow-sm border border-gray-200 dark:border-secondary-700 p-4 text-center">
            <div class="text-2xl font-bold text-gray-600 dark:text-gray-400 dark:text-gray-500">{{ $draftCount }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">مسودة</div>
        </div>
        <div class="bg-white dark:bg-secondary-950 rounded-lg shadow-sm border border-gray-200 dark:border-secondary-700 p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">في الانتظار</div>
        </div>
        <div class="bg-white dark:bg-secondary-950 rounded-lg shadow-sm border border-gray-200 dark:border-secondary-700 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $publishingCount }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">جاري النشر</div>
        </div>
        <div class="bg-white dark:bg-secondary-950 rounded-lg shadow-sm border border-gray-200 dark:border-secondary-700 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $publishedCount }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">تم النشر</div>
        </div>
        <div class="bg-white dark:bg-secondary-950 rounded-lg shadow-sm border border-gray-200 dark:border-secondary-700 p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $failedCount }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">فشل</div>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="bg-white dark:bg-secondary-950 rounded-xl shadow-sm border border-gray-200 dark:border-secondary-700 overflow-hidden">
        @if($posts->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">لا توجد منشورات</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">ابدأ بإنشاء منشور جديد للنشر على فيسبوك</p>
                <div class="mt-6">
                    <a href="{{ route('admin.facebook-posts.outgoing.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        إنشاء منشور
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-secondary-900">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">المنشور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">المتجر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">النوع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-secondary-950 divide-y divide-gray-200">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-800 dark:bg-secondary-900">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($post->image)
                                            <img src="{{ $post->image_url }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 dark:text-gray-100 truncate max-w-xs" title="{{ $post->message }}">
                                                {{ Str::limit($post->message, 50) ?: '(بدون نص)' }}
                                            </p>
                                            @if($post->facebook_permalink)
                                                <a href="{{ $post->facebook_permalink }}" target="_blank" 
                                                   class="text-xs text-blue-600 hover:underline">
                                                    عرض على فيسبوك ←
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $post->shop->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->post_type === 'text')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 dark:bg-secondary-800 text-gray-700 dark:text-gray-300">نص</span>
                                    @elseif($post->post_type === 'photo')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-100 text-purple-700">صورة</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">رابط</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $post->status_badge !!}
                                    @if($post->isFailed() && $post->error_message)
                                        <p class="text-xs text-red-500 mt-1 truncate max-w-[150px]" title="{{ $post->error_message }}">
                                            {{ Str::limit($post->error_message, 30) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">
                                    @if($post->published_at)
                                        <div>نُشر: {{ $post->published_at->format('Y/m/d H:i') }}</div>
                                    @endif
                                    <div>أُنشئ: {{ $post->created_at->format('Y/m/d H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.facebook-posts.outgoing.show', $post) }}" 
                                           class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 dark:text-gray-400 dark:text-gray-500" title="عرض">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        @if(!$post->isPublished())
                                            <a href="{{ route('admin.facebook-posts.outgoing.edit', $post) }}" 
                                               class="text-blue-400 hover:text-blue-600" title="تعديل">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        @if($post->status === 'draft')
                                            <form action="{{ route('admin.facebook-posts.outgoing.publish', $post) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-400 hover:text-green-600" title="نشر الآن">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($post->canRetry())
                                            <form action="{{ route('admin.facebook-posts.outgoing.retry', $post) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-400 hover:text-yellow-600" title="إعادة المحاولة">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.facebook-posts.outgoing.destroy', $post) }}" method="POST" class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المنشور؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600" title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-secondary-700">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
