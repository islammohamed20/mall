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
        <title>{{ $title ?? \App\Models\Setting::getValue('mall_name', app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en')) }}</title>
        @php($faviconPath = \App\Models\Setting::getValue('mall_favicon'))
        @if ($faviconPath)
            <link rel="icon" href="{{ asset('storage/'.$faviconPath) }}">
        @endif
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,600,700|poppins:400,500,600,700" rel="stylesheet" />
        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="min-h-screen bg-gray-50 text-secondary-900 dark:bg-secondary-950 dark:text-secondary-100">
        @include('partials.navbar')
        @include('partials.flash')
        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
        @include('partials.footer')

        <script>
            (function () {
                const endpoint = '{{ route('visit.geo') }}';
                const key = 'geo:lastSentAt';

                function canUseGeo() {
                    return typeof navigator !== 'undefined'
                        && navigator.geolocation
                        && window.isSecureContext;
                }

                function alreadySentRecently() {
                    try {
                        const ts = localStorage.getItem(key);
                        if (!ts) return false;
                        return (Date.now() - Number(ts)) < (1000 * 60 * 60 * 24 * 7);
                    } catch (e) {
                        return false;
                    }
                }

                function markSent() {
                    try { localStorage.setItem(key, String(Date.now())); } catch (e) {}
                }

                function csrfToken() {
                    const el = document.querySelector('meta[name="csrf-token"]');
                    return el ? el.getAttribute('content') : '';
                }

                function sendGeo(position) {
                    const payload = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                        accuracy_m: position.coords.accuracy,
                    };

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(payload),
                        credentials: 'same-origin',
                    }).then(function () {
                        markSent();
                    }).catch(function () {});
                }

                if (!canUseGeo() || alreadySentRecently()) {
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    sendGeo,
                    function () {},
                    { enableHighAccuracy: false, timeout: 4000, maximumAge: 1000 * 60 * 60 * 6 }
                );
            })();
        </script>

        {{-- WhatsApp Floating Button --}}
        @php($whatsappNumber = \App\Models\Setting::getValue('mall_contact_whatsapp'))
        @if($whatsappNumber)
            <a 
                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="fixed bottom-6 ltr:left-6 rtl:right-6 z-50 p-4 rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition-all duration-200 hover:scale-110 group"
                aria-label="{{ app()->getLocale() === 'ar' ? 'تواصل عبر واتساب' : 'Chat on WhatsApp' }}"
                x-data="{ tooltip: false }"
                @mouseenter="tooltip = true"
                @mouseleave="tooltip = false"
            >
                {{-- WhatsApp Icon --}}
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                
                {{-- Tooltip --}}
                <div 
                    x-show="tooltip"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2"
                    class="absolute ltr:left-full rtl:right-full ltr:ml-3 rtl:mr-3 top-1/2 -translate-y-1/2 px-3 py-2 bg-secondary-800 text-white text-sm rounded-lg whitespace-nowrap shadow-lg"
                >
                    {{ app()->getLocale() === 'ar' ? 'تواصل معنا عبر واتساب' : 'Chat with us on WhatsApp' }}
                    <div class="absolute ltr:-left-1 rtl:-right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-secondary-800 transform rotate-45"></div>
                </div>
                
                {{-- Pulse Animation --}}
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-300"></span>
                </span>
            </a>
        @endif

        {{-- Scroll to Top Button --}}
        <button
            x-data="{ show: false }"
            x-init="window.addEventListener('scroll', () => { show = window.scrollY > 400 })"
            x-show="show"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 ltr:right-6 rtl:left-6 z-50 p-3 rounded-full bg-primary-600 text-white shadow-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 hover:scale-110"
            aria-label="{{ app()->getLocale() === 'ar' ? 'الذهاب للأعلى' : 'Scroll to top' }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </body>
</html>
