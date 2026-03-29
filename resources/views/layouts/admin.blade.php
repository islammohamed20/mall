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
        <script>
            (function () {
                try {
                    if (localStorage.getItem('cookie:consent')) {
                        document.documentElement.classList.add('cookie-consent-accepted');
                    }
                } catch (e) {}
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
    <body class="min-h-screen bg-gray-50 text-secondary-900 dark:bg-secondary-950 dark:text-secondary-100 {{ $seasonThemeBodyClass ?? '' }}">
        <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', value => { document.body.classList.toggle('overflow-hidden', value); if (window.innerWidth < 1024 && value) { document.documentElement.style.overflow = 'hidden'; } else { document.documentElement.style.overflow = ''; } })">
            {{-- Desktop Sidebar --}}
            @include('layouts.partials.admin-sidebar')

            <div class="flex-1 flex flex-col min-h-0">
                {{-- Header --}}
                @include('layouts.partials.admin-header')

                {{-- Mobile Sidebar --}}
                @include('layouts.partials.admin-mobile-sidebar')

                @php
                    $popupsEnabled = true; // Enable by default or control via settings
                    $toastItems = collect([]);
                    if (session('status')) {
                        // handled in init
                    }
                    
                    // Add other notifications if needed
                    if (auth()->check()) {
                         $toastItems = auth()->user()->unreadNotifications
                            ->take(5)
                            ->map(function ($n) {
                                return [
                                    'id' => $n->id,
                                    'level' => 'info', // or derive from notification type
                                    'title' => $n->data['title'] ?? 'Notification',
                                    'body' => $n->data['body'] ?? '',
                                    'read_url' => route('admin.notifications.read', $n->id),
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

                <main class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-2 sm:p-4 lg:p-8">
                    <div class="max-w-full">
                        @yield('content')
                    </div>
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
        <div id="cookie-consent-banner" class="fixed bottom-4 left-4 right-4 z-50">
            <div class="max-w-3xl mx-auto rounded-2xl bg-white/95 dark:bg-secondary-900/95 backdrop-blur border border-gray-200 dark:border-secondary-800 shadow-lg p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <div class="text-sm text-secondary-700 dark:text-secondary-200 leading-relaxed">
                    {{ app()->getLocale() === 'ar'
                        ? 'نستخدم ملفات الارتباط (Cookies) لتحسين تجربة الاستخدام وتشغيل بعض الخصائص الأساسية مثل تسجيل الدخول والسلة. بالضغط على "موافق" أنت توافق على استخدامها.'
                        : 'We use cookies to improve your experience and enable essential features like login and cart. By clicking \"Accept\" you consent to their use.' }}
                </div>
                <div class="flex items-center gap-2 sm:shrink-0">
                    <button type="button" id="cookie-consent-accept" class="btn-primary px-4 py-2 text-sm">
                        {{ app()->getLocale() === 'ar' ? 'موافق' : 'Accept' }}
                    </button>
                    <button type="button" id="cookie-consent-close" class="px-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-secondary-700 text-secondary-700 dark:text-secondary-200 hover:bg-gray-50 dark:hover:bg-secondary-800 transition">
                        {{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}
                    </button>
                </div>
            </div>
        </div>
        <script>
            (function () {
                const key = 'cookie:consent';

                function hasConsent() {
                    try {
                        return !!localStorage.getItem(key);
                    } catch (e) {
                        return false;
                    }
                }

                const banner = document.getElementById('cookie-consent-banner');
                if (!banner) return;

                if (hasConsent()) {
                    banner.remove();
                    return;
                }

                const acceptBtn = document.getElementById('cookie-consent-accept');
                const closeBtn = document.getElementById('cookie-consent-close');

                function hide() {
                    banner.style.display = 'none';
                }

                if (acceptBtn) {
                    acceptBtn.addEventListener('click', function () {
                        try {
                            localStorage.setItem(key, 'accepted');
                        } catch (e) {}
                        document.documentElement.classList.add('cookie-consent-accepted');
                        hide();
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', hide);
                }
            })();
        </script>
    </body>
</html>
