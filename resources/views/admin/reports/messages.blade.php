@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 mb-2">
                    <a href="{{ route('admin.reports.dashboard') }}" class="hover:text-primary-600">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</a>
                    <span>/</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تقرير الرسائل' : 'Messages Report' }}
                </h1>
            </div>
        </div>

        {{-- Filters --}}
        <div class="admin-card p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</label>
                    <select name="status" class="input-field">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</option>
                        <option value="new" {{ $status == 'new' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}</option>
                        <option value="read" {{ $status == 'read' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مقروء' : 'Read' }}</option>
                        <option value="replied" {{ $status == 'replied' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'تم الرد' : 'Replied' }}</option>
                        <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مغلق' : 'Closed' }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                </button>
                <a href="{{ route('admin.reports.messages') }}" class="btn-secondary">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين' : 'Reset' }}
                </a>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'إجمالي الرسائل' : 'Total Messages' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($messagesSummary['total']) }}</p>
            </div>
            <div class="admin-card p-5 bg-gradient-to-br from-red-500 to-red-600 text-white">
                <p class="text-red-100 text-sm">{{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($messagesSummary['new']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'مقروء' : 'Read' }}</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($messagesSummary['read']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'تم الرد' : 'Replied' }}</p>
                <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($messagesSummary['replied']) }}</p>
            </div>
            <div class="admin-card p-5">
                <p class="text-secondary-500 text-sm">{{ app()->getLocale() === 'ar' ? 'مغلق' : 'Closed' }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-600 dark:text-gray-400 dark:text-gray-500">{{ number_format($messagesSummary['closed']) }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Messages Over Time --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'الرسائل (آخر 30 يوم)' : 'Messages (Last 30 Days)' }}</h3>
                <div class="h-72">
                    <canvas id="messagesOverTimeChart"></canvas>
                </div>
            </div>

            {{-- Messages by Status --}}
            <div class="admin-card p-5">
                <h3 class="font-semibold text-lg mb-4">{{ app()->getLocale() === 'ar' ? 'الرسائل حسب الحالة' : 'Messages by Status' }}</h3>
                <div class="h-72">
                    <canvas id="messagesByStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Messages List --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-secondary-700 dark:border-secondary-800 flex items-center justify-between">
                <h3 class="font-semibold text-lg">{{ app()->getLocale() === 'ar' ? 'قائمة الرسائل' : 'Messages List' }}</h3>
                <a href="{{ route('admin.messages.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    {{ app()->getLocale() === 'ar' ? 'إدارة الرسائل' : 'Manage Messages' }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'البريد' : 'Email' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'التاريخ' : 'Date' }}</th>
                            <th>{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                            <tr class="{{ $message->status === 'new' ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                <td>{{ $message->id }}</td>
                                <td class="font-medium">{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ Str::limit($message->subject, 40) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'new' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'read' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'replied' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'closed' => 'bg-gray-100 dark:bg-secondary-800 text-gray-800 dark:text-gray-200 dark:bg-gray-900/30 dark:text-gray-400 dark:text-gray-500',
                                        ];
                                        $statusLabels = [
                                            'new' => app()->getLocale() === 'ar' ? 'جديد' : 'New',
                                            'read' => app()->getLocale() === 'ar' ? 'مقروء' : 'Read',
                                            'replied' => app()->getLocale() === 'ar' ? 'تم الرد' : 'Replied',
                                            'closed' => app()->getLocale() === 'ar' ? 'مغلق' : 'Closed',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 rounded text-xs {{ $statusColors[$message->status] ?? 'bg-gray-100 dark:bg-secondary-800 text-gray-800 dark:text-gray-200' }}">
                                        {{ $statusLabels[$message->status] ?? $message->status }}
                                    </span>
                                </td>
                                <td class="text-secondary-500 text-sm">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-secondary-500">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد رسائل' : 'No messages found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($messages->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 dark:border-secondary-700 dark:border-secondary-800">
                    {{ $messages->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            // Messages Over Time Chart
            const timeCtx = document.getElementById('messagesOverTimeChart').getContext('2d');
            new Chart(timeCtx, {
                type: 'line',
                data: {
                    labels: @json($messagesOverTime->pluck('date')),
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "الرسائل" : "Messages" }}',
                        data: @json($messagesOverTime->pluck('count')),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Messages by Status Chart
            const statusCtx = document.getElementById('messagesByStatusChart').getContext('2d');
            const statusData = @json($messagesByStatus);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(s => {
                        const labels = {
                            'new': '{{ app()->getLocale() === "ar" ? "جديد" : "New" }}',
                            'read': '{{ app()->getLocale() === "ar" ? "مقروء" : "Read" }}',
                            'replied': '{{ app()->getLocale() === "ar" ? "تم الرد" : "Replied" }}',
                            'closed': '{{ app()->getLocale() === "ar" ? "مغلق" : "Closed" }}'
                        };
                        return labels[s.status] || s.status;
                    }),
                    datasets: [{
                        data: statusData.map(s => s.count),
                        backgroundColor: ['#ef4444', '#3b82f6', '#10b981', '#6b7280'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor }
                        }
                    }
                }
            });
        });
    </script>
@endsection
