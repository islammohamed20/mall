@php
    $favoritesCount = count(session('favorites', []));
    $cartCount = array_sum(session('cart', []));
@endphp
<header class="bg-white/80 backdrop-blur border-b border-gray-100 sticky top-0 z-40 dark:bg-secondary-950/80 dark:border-secondary-800" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            @php($logoPath = \App\Models\Setting::getValue('mall_logo'))
            @if ($logoPath)
                <img class="h-10 w-10 rounded-xl object-cover bg-gray-100 dark:bg-secondary-900" src="{{ asset('storage/'.$logoPath) }}" alt="" />
            @else
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary-500 to-gold-500"></div>
            @endif
            <div class="leading-tight hidden sm:block">
                <div class="font-bold text-secondary-900 dark:text-secondary-100">{{ \App\Models\Setting::getValue('mall_name', app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en')) }}</div>
                <div class="text-xs text-secondary-600 dark:text-secondary-300">{{ \App\Models\Setting::getValue('mall_slogan', app()->getLocale() === 'ar' ? config('mall.slogan.ar') : config('mall.slogan.en')) }}</div>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden lg:flex items-center gap-6 text-sm">
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('home') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('shops.*') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('shops.index') }}">{{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('offers.*') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('offers.index') }}">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('events.*') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('events.index') }}">{{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('units.*') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('units.index') }}">{{ app()->getLocale() === 'ar' ? 'الوحدات' : 'Units' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('about') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('about') }}">{{ app()->getLocale() === 'ar' ? 'عن المول' : 'About' }}</a>
            <a class="hover:text-primary-600 transition-colors {{ request()->routeIs('contact.*') ? 'text-primary-700 font-semibold' : 'text-secondary-800 dark:text-secondary-200' }}" href="{{ route('contact.show') }}">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
        </nav>

        {{-- Desktop Actions --}}
        <div class="hidden lg:flex items-center gap-2">
            {{-- Theme Toggle --}}
            <button
                type="button"
                class="p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors"
                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                x-init="window.addEventListener('theme-changed', e => dark = e.detail.dark)"
                @click="dark = !dark; window.__setTheme(dark ? 'dark' : 'light')"
                aria-label="{{ app()->getLocale() === 'ar' ? 'تبديل الثيم' : 'Toggle theme' }}"
            >
                <svg x-show="!dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M12 18.75V21M4.219 4.219l1.591 1.591M18.19 18.19l1.591 1.591M3 12h2.25M18.75 12H21M4.219 19.781l1.591-1.591M18.19 5.81l1.591-1.591M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                </svg>
            </button>

            {{-- Favorites Link --}}
            <a href="{{ route('favorites.index') }}" class="relative p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" aria-label="{{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Favorites' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                @if ($favoritesCount > 0)
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center px-1">{{ $favoritesCount > 99 ? '99+' : $favoritesCount }}</span>
                @endif
            </a>

            {{-- Cart Link --}}
            <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" aria-label="{{ app()->getLocale() === 'ar' ? 'سلة التسوق' : 'Shopping Cart' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                @if ($cartCount > 0)
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-primary-500 text-white text-[10px] font-bold flex items-center justify-center px-1">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                @endif
            </a>

            {{-- Language Switch --}}
            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
            </a>

            {{-- Auth Actions --}}
            @auth
                @if(auth()->user()?->is_admin)
                    <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" href="{{ route('admin.dashboard') }}">
                        {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Admin' }}
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-2 rounded-lg bg-secondary-900 text-white hover:bg-secondary-800 text-sm transition-colors" type="submit">
                        {{ app()->getLocale() === 'ar' ? 'خروج' : 'Logout' }}
                    </button>
                </form>
            @else
                <a class="px-3 py-2 rounded-lg bg-secondary-900 text-white hover:bg-secondary-800 text-sm transition-colors" href="{{ route('login') }}">
                    {{ app()->getLocale() === 'ar' ? 'دخول' : 'Login' }}
                </a>
            @endauth
        </div>

        {{-- Mobile Actions --}}
        <div class="flex lg:hidden items-center gap-1.5">
            {{-- Mobile Cart --}}
            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-800 transition-colors" aria-label="{{ app()->getLocale() === 'ar' ? 'سلة التسوق' : 'Shopping Cart' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                @if ($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-[16px] rounded-full bg-primary-500 text-white text-[9px] font-bold flex items-center justify-center px-0.5">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                @endif
            </a>

            {{-- Theme Toggle Mobile --}}
            <button
                type="button"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-800 transition-colors"
                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                x-init="window.addEventListener('theme-changed', e => dark = e.detail.dark)"
                @click="dark = !dark; window.__setTheme(dark ? 'dark' : 'light')"
                aria-label="{{ app()->getLocale() === 'ar' ? 'تبديل الثيم' : 'Toggle theme' }}"
            >
                <svg x-show="!dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M12 18.75V21M4.219 4.219l1.591 1.591M18.19 18.19l1.591 1.591M3 12h2.25M18.75 12H21M4.219 19.781l1.591-1.591M18.19 5.81l1.591-1.591M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                </svg>
            </button>

            {{-- Hamburger Menu Button --}}
            <button
                type="button"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-800 transition-colors"
                @click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen"
                aria-label="{{ app()->getLocale() === 'ar' ? 'فتح القائمة' : 'Open menu' }}"
            >
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-gray-100 dark:border-secondary-800 bg-white dark:bg-secondary-950"
        @click.outside="mobileMenuOpen = false"
    >
        {{-- Mobile Cart & Favorites Quick Access --}}
        <div class="max-w-7xl mx-auto px-4 pt-4 pb-2">
            <div class="flex gap-2">
                <a class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('favorites.*') ? 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-50 text-secondary-700 hover:bg-red-50 hover:text-red-600 dark:bg-secondary-900 dark:text-secondary-300 dark:hover:bg-red-900/30 dark:hover:text-red-400' }}" href="{{ route('favorites.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                        <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Favorites' }}
                    @if(count(session('favorites', [])) > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ count(session('favorites', [])) }}</span>
                    @endif
                </a>
                <a class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('cart.*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-gray-50 text-secondary-700 hover:bg-primary-50 hover:text-primary-600 dark:bg-secondary-900 dark:text-secondary-300 dark:hover:bg-primary-900/30 dark:hover:text-primary-400' }}" href="{{ route('cart.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'السلة' : 'Cart' }}
                    @if(count(session('cart', [])) > 0)
                        <span class="bg-primary-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ array_sum(session('cart', [])) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <nav class="max-w-7xl mx-auto px-4 py-2 space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('shops.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('shops.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('offers.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('offers.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('events.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('events.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('units.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('units.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'الوحدات' : 'Units' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('about') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'عن المول' : 'About' }}
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('contact.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : 'text-secondary-800 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-900' }}" href="{{ route('contact.show') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}
            </a>
        </nav>

        {{-- Mobile Menu Footer --}}
        <div class="border-t border-gray-100 dark:border-secondary-800 px-4 py-4">
            <div class="flex items-center justify-between gap-3">
                {{-- Language Switch --}}
                <a class="flex-1 text-center px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm font-medium dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                    {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                </a>

                {{-- Auth Actions --}}
                @auth
                    @if(auth()->user()?->is_admin)
                        <a class="flex-1 text-center px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm font-medium dark:border-secondary-700 dark:hover:bg-secondary-900 transition-colors" href="{{ route('admin.dashboard') }}">
                            {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Admin' }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button class="w-full px-4 py-2.5 rounded-xl bg-secondary-900 text-white hover:bg-secondary-800 text-sm font-medium transition-colors" type="submit">
                            {{ app()->getLocale() === 'ar' ? 'خروج' : 'Logout' }}
                        </button>
                    </form>
                @else
                    <a class="flex-1 text-center px-4 py-2.5 rounded-xl bg-secondary-900 text-white hover:bg-secondary-800 text-sm font-medium transition-colors" href="{{ route('login') }}">
                        {{ app()->getLocale() === 'ar' ? 'دخول' : 'Login' }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
