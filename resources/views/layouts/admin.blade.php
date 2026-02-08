<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            (function () {
                const storageKey = 'theme';
                const root = document.documentElement;

                function getStoredTheme() {
                    try {
                        return localStorage.getItem(storageKey);
                    } catch (e) {
                        return null;
                    }
                }

                function getSystemTheme() {
                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                function resolveTheme() {
                    return getStoredTheme() || getSystemTheme();
                }

                function applyTheme(theme) {
                    const isDark = theme === 'dark';
                    root.classList.toggle('dark', isDark);
                    root.style.colorScheme = isDark ? 'dark' : 'light';
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme, dark: isDark } }));
                }

                window.__setTheme = function (theme) {
                    try {
                        localStorage.setItem(storageKey, theme);
                    } catch (e) {}
                    applyTheme(theme);
                };

                window.__toggleTheme = function () {
                    const isDark = root.classList.contains('dark');
                    window.__setTheme(isDark ? 'light' : 'dark');
                };

                applyTheme(resolveTheme());
            })();
        </script>
        <title>{{ $title ?? 'Admin' }} - {{ app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en') }}</title>
        @php
            $faviconPath = \App\Models\Setting::getValue('mall_favicon');
        @endphp
        @if ($faviconPath)
            <link rel="icon" href="{{ asset('storage/'.$faviconPath) }}">
        @endif
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,600,700|poppins:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gray-100 text-secondary-900 dark:bg-secondary-950 dark:text-secondary-100 {{ $seasonThemeBodyClass ?? '' }}">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', value => document.body.classList.toggle('overflow-hidden', value))">
            <aside class="w-64 bg-white border-e border-gray-200 hidden lg:block dark:bg-secondary-950 dark:border-secondary-800">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-secondary-800">
                    <div class="text-lg font-bold gradient-text">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Admin Panel' }}</div>
                    <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en') }}</div>
                </div>
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Dashboard' }}
                    </a>
                    
                    {{-- Reports Section --}}
                    <div class="pt-3 pb-1">
                        <p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</p>
                    </div>
                    <a href="{{ route('admin.reports.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.reports.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'التقارير والتحليلات' : 'Reports & Analytics' }}
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}
                    </a>
                    
                    {{-- Content Section --}}
                    <div class="pt-3 pb-1">
                        <p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'المحتوى' : 'Content' }}</p>
                    </div>
                    <a href="{{ route('admin.shop-categories.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.shop-categories.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'تصنيفات المحلات' : 'Shop Categories' }}
                    </a>
                    <a href="{{ route('admin.product-attributes.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.product-attributes.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'خصائص المنتجات' : 'Product Attributes' }}
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.shops.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}
                    </a>
                    <a href="{{ route('admin.offers.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.offers.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.events.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.sliders.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'السلايدر' : 'Sliders' }}
                    </a>
                    <a href="{{ route('admin.floors.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.floors.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'الأدوار' : 'Floors' }}
                    </a>
                    <a href="{{ route('admin.facilities.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facilities.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'المرافق والخدمات' : 'Facilities' }}
                    </a>
                    <a href="{{ route('admin.payment-methods.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.payment-methods.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'طرق الدفع' : 'Payment Methods' }}
                    </a>
                    <a href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'الصفحات' : 'Pages' }}
                    </a>
                    <a href="{{ route('admin.units.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.units.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'الوحدات المعروضة' : 'Units for Sale/Rent' }}
                    </a>
                    <a href="{{ route('admin.facebook-posts.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facebook-posts.index') || request()->routeIs('admin.facebook-posts.approve') || request()->routeIs('admin.facebook-posts.reject') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'منشورات فيسبوك (الواردة)' : 'Facebook Posts (Incoming)' }}
                    </a>
                    <a href="{{ route('admin.facebook-posts.outgoing.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facebook-posts.outgoing.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold' : '' }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'النشر على فيسبوك' : 'Publish to Facebook' }}
                    </a>
                    
                    {{-- Settings Section --}}
                    <div class="pt-3 pb-1">
                        <p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'الإعدادات' : 'Settings' }}</p>
                    </div>
                    <a href="{{ route('admin.settings.edit') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'إعدادات الموقع' : 'Site Settings' }}
                    </a>
                    <a href="{{ route('admin.themes.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.themes.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'ثيمات المناسبات' : 'Seasonal Themes' }}
                    </a>
                    <a href="{{ route('admin.email.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.email.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Settings' }}
                    </a>
                    <a href="{{ route('admin.emails.outbox.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.emails.outbox.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'Outbox البريد' : 'Email Outbox' }}
                    </a>
                    <a href="{{ route('admin.otps.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.otps.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'رموز التحقق (OTPs)' : 'OTPs' }}
                    </a>
                    <a href="{{ route('admin.messages.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.messages.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                        {{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}
                    </a>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col">
                <header class="bg-white border-b border-gray-200 dark:bg-secondary-950 dark:border-secondary-800">
                    <div class="px-4 lg:px-8 h-16 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button type="button" class="lg:hidden px-3 py-2 rounded-lg border border-gray-200 dark:border-secondary-700" @click="sidebarOpen = !sidebarOpen">
                                {{ app()->getLocale() === 'ar' ? 'القائمة' : 'Menu' }}
                            </button>
                            <div class="text-sm text-secondary-700 dark:text-secondary-200">
                                {{ auth()->user()?->name }}
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900"
                                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                                x-init="window.addEventListener('theme-changed', e => dark = e.detail.dark)"
                                @click="dark = !dark; window.__setTheme(dark ? 'dark' : 'light')"
                            >
                                <span class="sr-only">{{ app()->getLocale() === 'ar' ? 'تبديل الثيم' : 'Toggle theme' }}</span>
                                <svg x-show="!dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M12 18.75V21M4.219 4.219l1.591 1.591M18.19 18.19l1.591 1.591M3 12h2.25M18.75 12H21M4.219 19.781l1.591-1.591M18.19 5.81l1.591-1.591M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                                </svg>
                            </button>

                            <button
                                type="button"
                                class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900"
                                title="{{ app()->getLocale() === 'ar'
                                    ? 'اختصارات لوحة التحكم:\nCtrl/⌘ + S: حفظ\nCtrl/⌘ + Enter: تنفيذ/حفظ\nCtrl/⌘ + Shift + D: مسح الحقول\nCtrl/⌘ + Shift + X: مسح الحقول (بديل)\nCtrl/⌘ + Shift + T: تبديل الثيم\nEsc: إغلاق القائمة'
                                    : 'Admin shortcuts:\nCtrl/⌘ + S: Save\nCtrl/⌘ + Enter: Submit\nCtrl/⌘ + Shift + D: Clear fields\nCtrl/⌘ + Shift + X: Clear fields (alt)\nCtrl/⌘ + Shift + T: Toggle theme\nEsc: Close menu' }}"
                                onclick="alert(this.title)"
                            >
                                <span class="sr-only">{{ app()->getLocale() === 'ar' ? 'الاختصارات' : 'Shortcuts' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm9.75-4.5a.75.75 0 0 0-.75.75v.008a.75.75 0 0 0 1.5 0V8.25a.75.75 0 0 0-.75-.75Zm0 3a.75.75 0 0 0-.75.75v5.25a.75.75 0 0 0 1.5 0v-5.25a.75.75 0 0 0-.75-.75Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ route('home') }}">
                                {{ app()->getLocale() === 'ar' ? 'الموقع' : 'Website' }}
                            </a>
                            <a class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-secondary-700 dark:hover:bg-secondary-900" href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                                {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="px-3 py-2 rounded-lg bg-secondary-900 text-white hover:bg-secondary-800" type="submit">
                                    {{ app()->getLocale() === 'ar' ? 'خروج' : 'Logout' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <div class="lg:hidden" x-show="sidebarOpen" x-cloak>
                    <div class="fixed inset-0 bg-black/40" @click="sidebarOpen = false"></div>
                    <div class="fixed inset-y-0 start-0 w-72 bg-white shadow-lg dark:bg-secondary-950 flex flex-col h-full">
                        <div class="px-6 py-5 border-b border-gray-200 dark:border-secondary-800 flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold gradient-text">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Admin Panel' }}</div>
                                <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en') }}</div>
                            </div>
                            <button class="px-3 py-2 rounded-lg border border-gray-200 dark:border-secondary-700" type="button" @click="sidebarOpen = false">
                                {{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}
                            </button>
                        </div>
                        <nav class="p-4 space-y-1 flex-1 min-h-0 overflow-y-auto overscroll-contain">
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Dashboard' }}
                            </a>

                            {{-- Reports --}}
                            <div class="pt-3 pb-1"><p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'التقارير' : 'Reports' }}</p></div>
                            <a href="{{ route('admin.reports.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.reports.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                {{ app()->getLocale() === 'ar' ? 'التقارير والتحليلات' : 'Reports & Analytics' }}
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                {{ app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders' }}
                            </a>

                            {{-- Content --}}
                            <div class="pt-3 pb-1"><p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'المحتوى' : 'Content' }}</p></div>
                            <a href="{{ route('admin.shop-categories.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.shop-categories.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'تصنيفات المحلات' : 'Shop Categories' }}
                            </a>
                            <a href="{{ route('admin.product-attributes.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.product-attributes.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'خصائص المنتجات' : 'Product Attributes' }}
                            </a>
                            <a href="{{ route('admin.shops.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.shops.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}
                            </a>
                            <a href="{{ route('admin.offers.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.offers.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.events.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}
                            </a>
                            <a href="{{ route('admin.sliders.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.sliders.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'السلايدر' : 'Sliders' }}
                            </a>
                            <a href="{{ route('admin.floors.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.floors.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'الأدوار' : 'Floors' }}
                            </a>
                            <a href="{{ route('admin.facilities.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facilities.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'المرافق والخدمات' : 'Facilities' }}
                            </a>
                            <a href="{{ route('admin.payment-methods.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.payment-methods.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'طرق الدفع' : 'Payment Methods' }}
                            </a>
                            <a href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'الصفحات' : 'Pages' }}
                            </a>
                            <a href="{{ route('admin.units.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.units.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                                {{ app()->getLocale() === 'ar' ? 'الوحدات المعروضة' : 'Units for Sale/Rent' }}
                            </a>
                            <a href="{{ route('admin.facebook-posts.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facebook-posts.index') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'منشورات فيسبوك (الواردة)' : 'Facebook Posts (Incoming)' }}
                            </a>
                            <a href="{{ route('admin.facebook-posts.outgoing.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.facebook-posts.outgoing.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold' : '' }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                {{ app()->getLocale() === 'ar' ? 'النشر على فيسبوك' : 'Publish to Facebook' }}
                            </a>

                            {{-- Settings --}}
                            <div class="pt-3 pb-1"><p class="px-3 text-xs font-semibold text-secondary-400 uppercase tracking-wider">{{ app()->getLocale() === 'ar' ? 'الإعدادات' : 'Settings' }}</p></div>
                            <a href="{{ route('admin.settings.edit') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'إعدادات الموقع' : 'Site Settings' }}
                            </a>
                            <a href="{{ route('admin.themes.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.themes.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'ثيمات المناسبات' : 'Seasonal Themes' }}
                            </a>
                            <a href="{{ route('admin.email.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.email.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Settings' }}
                            </a>
                            <a href="{{ route('admin.emails.outbox.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.emails.outbox.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'Outbox البريد' : 'Email Outbox' }}
                            </a>
                            <a href="{{ route('admin.otps.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.otps.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'رموز التحقق (OTPs)' : 'OTPs' }}
                            </a>
                            <a href="{{ route('admin.messages.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-secondary-900 {{ request()->routeIs('admin.messages.*') ? 'bg-gray-100 dark:bg-secondary-900 font-semibold' : '' }}">
                                {{ app()->getLocale() === 'ar' ? 'الرسائل' : 'Messages' }}
                            </a>
                        </nav>
                    </div>
                </div>

                @include('partials.flash')

                @php
                    $popupsEnabled = \App\Models\Setting::getValue('admin_popup_enabled') !== '0';
                    $toastItems = collect();

                    if ($popupsEnabled && \Illuminate\Support\Facades\Schema::hasTable('admin_notifications')) {
                        $toastItems = \App\Models\AdminNotification::query()
                            ->whereNull('read_at')
                            ->latest('id')
                            ->limit(3)
                            ->get()
                            ->map(function ($n) {
                                return [
                                    'id' => $n->id,
                                    'level' => $n->level ?? 'info',
                                    'title' => $n->title ?? $n->type,
                                    'body' => $n->body,
                                    'read_url' => route('admin.notifications.read', $n),
                                ];
                            })
                            ->values();
                    }
                @endphp

                @if ($popupsEnabled ?? false)
                    <div
                        x-data="{
                            items: @js($toastItems),
                            status: @js(session('status')),
                            locale: @js(app()->getLocale()),
                            init() {
                                if (this.status) {
                                    this.items.unshift({
                                        id: 'session',
                                        level: 'success',
                                        title: this.locale === 'ar' ? 'تم' : 'Done',
                                        body: this.status,
                                        read_url: null,
                                    });
                                }
                            },
                            levelClass(level) {
                                switch (level) {
                                    case 'success': return 'border-green-200 bg-green-50 text-green-900 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-100';
                                    case 'warning': return 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-100';
                                    case 'error': return 'border-red-200 bg-red-50 text-red-900 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-100';
                                    default: return 'border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-100';
                                }
                            },
                            async dismiss(item) {
                                this.items = this.items.filter(i => i.id !== item.id);
                                if (!item.read_url) return;

                                const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                                try {
                                    await fetch(item.read_url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': token || '',
                                            'Accept': 'application/json',
                                        },
                                    });
                                } catch (e) {}
                            }
                        }"
                        x-init="init()"
                        class="fixed top-4 end-4 z-50 w-[22rem] max-w-[calc(100vw-2rem)] space-y-3"
                    >
                        <template x-for="item in items" :key="item.id">
                            <div class="rounded-xl border shadow-lg p-4" :class="levelClass(item.level)">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="font-semibold" x-text="item.title"></div>
                                        <div class="mt-1 text-sm opacity-90 break-words" x-text="item.body"></div>
                                    </div>
                                    <button type="button" class="shrink-0 opacity-70 hover:opacity-100" @click="dismiss(item)">
                                        <span class="sr-only">Dismiss</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                @endif

                <main class="flex-1 p-4 lg:p-8">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            (function () {
                const locale = '{{ app()->getLocale() }}';
                window.__adminShortcutsLoaded = true;

                function isEditable(el) {
                    if (!el) return false;
                    const tag = (el.tagName || '').toLowerCase();
                    return tag === 'input' || tag === 'textarea' || tag === 'select' || el.isContentEditable;
                }

                function getCurrentForm() {
                    const active = document.activeElement;
                    if (active && active.form) {
                        return active.form;
                    }

                    const main = document.querySelector('main');
                    if (!main) return null;

                    const forms = Array.from(main.querySelectorAll('form'));
                    if (forms.length === 0) return null;

                    return forms[0];
                }

                function submitForm(form) {
                    if (!form) {
                        return;
                    }

                    const submit = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submit) {
                        submit.click();
                        return;
                    }

                    form.submit();
                }

                function clearForm(form) {
                    if (!form) return;

                    const ok = window.confirm(
                        locale === 'ar'
                            ? 'هل تريد مسح جميع الحقول في هذه الصفحة؟'
                            : 'Clear all fields on this form?'
                    );
                    if (!ok) return;

                    const elements = Array.from(form.elements || []);
                    for (const el of elements) {
                        const name = el.getAttribute && el.getAttribute('name');
                        if (name === '_token' || name === '_method') {
                            continue;
                        }

                        const tag = (el.tagName || '').toLowerCase();
                        const type = ((el.getAttribute && el.getAttribute('type')) || '').toLowerCase();

                        if (tag === 'input') {
                            if (type === 'hidden' || type === 'submit' || type === 'button' || type === 'reset') {
                                continue;
                            }
                            if (type === 'checkbox' || type === 'radio') {
                                el.checked = false;
                                continue;
                            }
                            el.value = '';
                            continue;
                        }

                        if (tag === 'textarea') {
                            el.value = '';
                            continue;
                        }

                        if (tag === 'select') {
                            const hasEmpty = Array.from(el.options || []).some(o => (o.value ?? '') === '');
                            if (hasEmpty) {
                                el.value = '';
                            } else {
                                el.selectedIndex = 0;
                            }
                        }
                    }
                }

                function closeMobileSidebar() {
                    const overlay = document.querySelector('.lg\\:hidden [x-show="sidebarOpen"] .fixed.inset-0');
                    if (overlay) {
                        overlay.click();
                    }
                }

                window.addEventListener('keydown', function (e) {
                    // Esc closes sidebar
                    if (e.key === 'Escape') {
                        closeMobileSidebar();
                        return;
                    }

                    const isCtrlOrCmd = e.ctrlKey || e.metaKey;
                    if (!isCtrlOrCmd) return;

                    const key = (e.key || '').toLowerCase();
                    const code = (e.code || '').toLowerCase();

                    // Ctrl/Cmd + S => submit current form
                    if ((key === 's' || code === 'keys') && !e.shiftKey && !e.altKey) {
                        e.preventDefault();
                        submitForm(getCurrentForm());
                        return;
                    }

                    // Ctrl/Cmd + Enter => submit current form
                    if (key === 'enter' && !e.shiftKey && !e.altKey) {
                        e.preventDefault();
                        submitForm(getCurrentForm());
                        return;
                    }

                    // Ctrl/Cmd + Shift + D => clear form fields
                    if ((key === 'd' || code === 'keyd') && e.shiftKey && !e.altKey) {
                        e.preventDefault();
                        clearForm(getCurrentForm());
                        return;
                    }

                    // Ctrl/Cmd + Shift + X => clear form fields (alternative)
                    if ((key === 'x' || code === 'keyx') && e.shiftKey && !e.altKey) {
                        e.preventDefault();
                        clearForm(getCurrentForm());
                        return;
                    }

                    // Ctrl/Cmd + Shift + T => toggle theme
                    if ((key === 't' || code === 'keyt') && e.shiftKey && !e.altKey) {
                        e.preventDefault();
                        if (window.__toggleTheme) {
                            window.__toggleTheme();
                        }
                        return;
                    }
                }, { capture: true });
            })();
        </script>
    </body>
</html>
