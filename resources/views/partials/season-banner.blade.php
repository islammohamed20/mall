{{-- Season Theme Greeting Banner --}}
@if (!empty($seasonBanner) && !empty($seasonBanner['greeting']))
    <div
        x-data="{ dismissed: false }"
        x-show="!dismissed"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 max-h-16"
        x-transition:leave-end="opacity-0 max-h-0"
        class="season-banner bg-gradient-to-r {{ $seasonBanner['banner_colors'] ?? 'from-primary-500 to-gold-500' }} text-white overflow-hidden"
    >
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-2.5 flex items-center justify-between gap-3">
            @if (app()->getLocale() === 'ar')
                <button
                    type="button"
                    class="shrink-0 p-1 rounded-lg hover:bg-white/20 transition-colors"
                    @click="dismissed = true"
                    aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
            <div class="flex-1 text-center">
                <p class="season-banner-text text-sm sm:text-base font-semibold tracking-wide">
                    {{ $seasonBanner['greeting'] }}
                </p>
            </div>
            @if (app()->getLocale() !== 'ar')
                <button
                    type="button"
                    class="shrink-0 p-1 rounded-lg hover:bg-white/20 transition-colors"
                    @click="dismissed = true"
                    aria-label="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
@endif
