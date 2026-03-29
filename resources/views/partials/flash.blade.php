{{-- Success Messages --}}
@if (session('success') || session('status'))
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4">
        <div 
            x-data="{ open: true }" 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-init="setTimeout(() => open = false, 5000)"
            class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 flex items-start gap-3 dark:bg-green-900/20 dark:border-green-800"
        >
            <div class="shrink-0 p-1 rounded-full bg-green-100 text-green-600 dark:bg-green-800/50 dark:text-green-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 text-sm text-green-800 dark:text-green-200">{{ session('success') ?? session('status') }}</div>
            <button type="button" class="shrink-0 p-1 rounded-lg text-green-600 hover:bg-green-100 transition-colors dark:text-green-400 dark:hover:bg-green-800/50" @click="open = false" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif

{{-- Error Messages --}}
@if (session('error'))
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4">
        <div 
            x-data="{ open: true }" 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-3 dark:bg-red-900/20 dark:border-red-800"
        >
            <div class="shrink-0 p-1 rounded-full bg-red-100 text-red-600 dark:bg-red-800/50 dark:text-red-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1 text-sm text-red-800 dark:text-red-200">{{ session('error') }}</div>
            <button type="button" class="shrink-0 p-1 rounded-lg text-red-600 hover:bg-red-100 transition-colors dark:text-red-400 dark:hover:bg-red-800/50" @click="open = false" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif

{{-- Warning Messages --}}
@if (session('warning'))
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4">
        <div 
            x-data="{ open: true }" 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-init="setTimeout(() => open = false, 7000)"
            class="rounded-xl bg-yellow-50 border border-yellow-200 px-4 py-3 flex items-start gap-3 dark:bg-yellow-900/20 dark:border-yellow-800"
        >
            <div class="shrink-0 p-1 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-800/50 dark:text-yellow-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 text-sm text-yellow-800 dark:text-yellow-200">{{ session('warning') }}</div>
            <button type="button" class="shrink-0 p-1 rounded-lg text-yellow-600 hover:bg-yellow-100 transition-colors dark:text-yellow-400 dark:hover:bg-yellow-800/50" @click="open = false" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif

{{-- Info Messages --}}
@if (session('info'))
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4">
        <div 
            x-data="{ open: true }" 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-init="setTimeout(() => open = false, 5000)"
            class="rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 flex items-start gap-3 dark:bg-blue-900/20 dark:border-blue-800"
        >
            <div class="shrink-0 p-1 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-800/50 dark:text-blue-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 text-sm text-blue-800 dark:text-blue-200">{{ session('info') }}</div>
            <button type="button" class="shrink-0 p-1 rounded-lg text-blue-600 hover:bg-blue-100 transition-colors dark:text-blue-400 dark:hover:bg-blue-800/50" @click="open = false" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-4">
        <div 
            x-data="{ open: true }" 
            x-show="open" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 dark:bg-red-900/20 dark:border-red-800"
        >
            <div class="flex items-start gap-3">
                <div class="shrink-0 p-1 rounded-full bg-red-100 text-red-600 dark:bg-red-800/50 dark:text-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-200">{{ app()->getLocale() === 'ar' ? 'يرجى تصحيح الأخطاء التالية:' : 'Please fix the following errors:' }}</h4>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="shrink-0 p-1 rounded-lg text-red-600 hover:bg-red-100 transition-colors dark:text-red-400 dark:hover:bg-red-800/50" @click="open = false" aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
