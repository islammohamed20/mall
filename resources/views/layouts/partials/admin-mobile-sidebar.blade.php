<div class="lg:hidden" x-show="sidebarOpen" x-cloak>
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" @click="sidebarOpen = false"></div>
    <div class="fixed inset-y-0 start-0 w-[17rem] bg-secondary-900 dark:bg-secondary-950 shadow-2xl flex flex-col h-full z-50">
        <div class="px-5 py-5 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                @php $logoPath = \App\Models\Setting::getValue('mall_logo'); @endphp
                @if($logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-gray-50 dark:bg-white/10 p-1">
                @else
                    <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                @endif
                <div class="text-sm font-bold text-white truncate">{{ app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en') }}</div>
            </div>
            <button class="p-1.5 rounded-lg text-secondary-300 hover:text-white hover:bg-white/10 transition-colors" type="button" @click="sidebarOpen = false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="px-3 pb-4 space-y-6 flex-1 min-h-0 overflow-y-auto overscroll-contain sidebar-scroll">
            @include('layouts.partials.admin-nav-links')
        </nav>
    </div>
</div>
