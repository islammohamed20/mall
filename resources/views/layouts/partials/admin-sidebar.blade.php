<aside class="w-64 hidden lg:flex lg:flex-col flex-shrink-0 bg-white dark:bg-secondary-950 border-e border-gray-200 dark:border-secondary-800 transition-colors duration-300">
    {{-- Logo Area --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-secondary-800">
        <div class="flex items-center gap-3">
            @php $logoPath = \App\Models\Setting::getValue('mall_logo'); @endphp
            @if($logoPath)
                <img src="{{ asset('storage/'.$logoPath) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain">
            @else
                <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white">
                    <span class="font-bold text-lg">M</span>
                </div>
            @endif
            <div class="font-bold text-xl text-secondary-900 dark:text-white tracking-tight">
                {{ app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en') }}
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 min-h-0 overflow-y-auto px-4 py-6 space-y-8 sidebar-scroll">
        @include('layouts.partials.admin-nav-links')
    </nav>
</aside>
