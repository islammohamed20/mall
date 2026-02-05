@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'منشورات فيسبوك' : 'Facebook Posts' }}</h1>
                <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'اعتماد أو رفض المنشورات قبل عرضها للزوار.' : 'Approve or reject posts before showing them publicly.' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900 {{ $status === 'pending' ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}" href="{{ route('admin.facebook-posts.index', ['status' => 'pending']) }}">
                {{ app()->getLocale() === 'ar' ? 'قيد المراجعة' : 'Pending' }}
            </a>
            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900 {{ $status === 'approved' ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}" href="{{ route('admin.facebook-posts.index', ['status' => 'approved']) }}">
                {{ app()->getLocale() === 'ar' ? 'معتمد' : 'Approved' }}
            </a>
            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900 {{ $status === 'rejected' ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}" href="{{ route('admin.facebook-posts.index', ['status' => 'rejected']) }}">
                {{ app()->getLocale() === 'ar' ? 'مرفوض' : 'Rejected' }}
            </a>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                        <tr>
                            <th class="text-start px-5 py-3 font-semibold">#</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المحل' : 'Shop' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'المنشور' : 'Post' }}</th>
                            <th class="text-start px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'تاريخ' : 'Date' }}</th>
                            <th class="text-end px-5 py-3 font-semibold">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-secondary-800">
                        @forelse ($posts as $post)
                            <tr class="hover:bg-gray-50 dark:hover:bg-secondary-900 align-top">
                                <td class="px-5 py-3 text-secondary-600 dark:text-secondary-300">{{ $post->id }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ $post->shop?->name }}</div>
                                    <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ $post->fb_post_id }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-start gap-4">
                                        @if ($post->image_url)
                                            <div class="shrink-0 w-20 h-16 bg-gray-100 dark:bg-secondary-900 rounded-lg overflow-hidden">
                                                <img class="w-full h-full object-cover" src="{{ $post->image_url }}" alt="" loading="lazy" />
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            @if ($post->message)
                                                <div class="text-secondary-900 dark:text-secondary-100 line-clamp-3">{{ $post->message }}</div>
                                            @else
                                                <div class="text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'بدون نص' : 'No text' }}</div>
                                            @endif
                                            @if ($post->permalink_url)
                                                <a class="inline-block mt-2 text-primary-700 hover:text-primary-800 text-xs" href="{{ $post->permalink_url }}" target="_blank" rel="noreferrer">
                                                    {{ app()->getLocale() === 'ar' ? 'فتح على فيسبوك' : 'Open on Facebook' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-secondary-700 dark:text-secondary-200">
                                    {{ $post->posted_at?->format('Y-m-d H:i') ?? '-' }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end gap-2">
                                        @if ($post->status !== 'approved')
                                            <form method="POST" action="{{ route('admin.facebook-posts.approve', $post) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg border border-green-200 text-green-800 hover:bg-green-50 dark:border-green-900/40 dark:text-green-200 dark:hover:bg-green-900/20" type="submit">
                                                    {{ app()->getLocale() === 'ar' ? 'اعتماد' : 'Approve' }}
                                                </button>
                                            </form>
                                        @endif
                                        @if ($post->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.facebook-posts.reject', $post) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="px-3 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 dark:border-red-900/40 dark:text-red-200 dark:hover:bg-red-900/20" type="submit">
                                                    {{ app()->getLocale() === 'ar' ? 'رفض' : 'Reject' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-6 text-secondary-600 dark:text-secondary-300" colspan="5">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد منشورات.' : 'No posts found.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
