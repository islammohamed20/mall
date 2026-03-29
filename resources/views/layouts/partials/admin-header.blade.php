<header class="bg-white dark:bg-secondary-950 border-b border-gray-100 dark:border-secondary-800 flex-shrink-0 h-16 z-30 relative">
    <div class="h-full px-3 sm:px-6 flex items-center justify-between gap-2 sm:gap-4">
        <!-- Left Section: Mobile Menu & Search -->
        <div class="flex items-center gap-4 flex-1">
            <button type="button" class="lg:hidden p-2 text-secondary-500 hover:bg-gray-50 dark:hover:bg-secondary-900 rounded-lg transition-colors" @click="sidebarOpen = !sidebarOpen">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <!-- Search Bar -->
            <div class="hidden lg:flex items-center max-w-md w-full">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-secondary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="{{ app()->getLocale() === 'ar' ? 'بحث...' : 'Search...' }}" class="w-full bg-gray-50 dark:bg-secondary-900/50 border-none rounded-xl py-2 ps-10 pe-4 text-sm focus:ring-2 focus:ring-primary-500/20 text-secondary-900 dark:text-white placeholder-secondary-400 transition-all">
                </div>
            </div>
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center gap-1 sm:gap-2 lg:gap-3">
            <!-- Website Link -->
            <a href="{{ route('home') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 text-sm font-medium text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" target="_blank" title="{{ app()->getLocale() === 'ar' ? 'عرض الموقع' : 'View Website' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>

            <!-- Theme Toggle -->
            <button type="button" class="p-2 text-secondary-500 hover:bg-gray-50 dark:hover:bg-secondary-900 rounded-lg transition-colors"
                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                x-init="window.addEventListener('theme-changed', e => dark = e.detail.dark)"
                @click="dark = !dark; window.__setTheme(dark ? 'dark' : 'light')"
                title="{{ app()->getLocale() === 'ar' ? 'تبديل الثيم' : 'Toggle theme' }}">
                <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="dark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            <!-- Language Switch -->
            <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="p-2 text-secondary-500 hover:bg-gray-50 dark:hover:bg-secondary-900 rounded-lg transition-colors font-bold text-sm w-9 h-9 flex items-center justify-center">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
            </a>

            <!-- Notifications -->
            <button class="relative p-2 text-secondary-500 hover:bg-gray-50 dark:hover:bg-secondary-900 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="absolute top-2.5 end-2.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-secondary-950"></span>
            </button>

            <div class="h-8 w-px bg-gray-200 dark:bg-secondary-800 mx-1 hidden sm:block"></div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                    <div class="text-end hidden sm:block">
                        <div class="text-sm font-semibold text-secondary-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ auth()->user()?->name }}</div>
                        <div class="text-xs text-secondary-500">Admin</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold border border-gray-100 dark:border-secondary-800 shadow-sm group-hover:ring-2 ring-primary-500/20 transition-all">
                        {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                    </div>
                </button>

                <!-- Dropdown -->
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute end-0 mt-2 w-48 bg-white dark:bg-secondary-900 rounded-xl shadow-xl border border-gray-100 dark:border-secondary-800 py-1 z-50">
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 text-start transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'تسجيل الخروج' : 'Logout' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
