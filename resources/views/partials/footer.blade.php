<footer class="bg-white border-t border-gray-100 dark:bg-secondary-950 dark:border-secondary-800">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 lg:py-12">
        {{-- Main Footer Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            {{-- Brand & Description --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    @php
                        $logoPath = \App\Models\Setting::getValue('mall_logo');
                    @endphp
                    @if ($logoPath)
                        <img class="h-10 w-10 rounded-xl object-cover bg-gray-100 dark:bg-secondary-900" src="{{ asset('storage/'.$logoPath) }}" alt="" />
                    @else
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary-500 to-gold-500"></div>
                    @endif
                    <div class="font-bold text-xl gradient-text">{{ \App\Models\Setting::getValue('mall_name', app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en')) }}</div>
                </a>
                <p class="mt-4 text-secondary-600 dark:text-secondary-300 text-sm leading-relaxed max-w-md">
                    {{ app()->getLocale() === 'ar'
                        ? 'تجربة تسوق عائلية تجمع أفضل المحلات والمطاعم والخدمات في قلب المدينة.'
                        : 'A family-friendly shopping experience with the best shops, dining, and services in the heart of the city.' }}
                </p>
                
                {{-- Social Links --}}
                <div class="mt-6 flex items-center gap-3">
                    @php
                        $facebook = \App\Models\Setting::getValue('mall_social_facebook');
                    @endphp
                    @if($facebook)
                    <a class="p-2.5 rounded-lg bg-secondary-100 hover:bg-primary-100 text-secondary-600 hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:hover:bg-primary-900/30 dark:text-secondary-300 dark:hover:text-primary-400" href="{{ $facebook }}" target="_blank" rel="noreferrer" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    @endif
                    @php
                        $instagram = \App\Models\Setting::getValue('mall_social_instagram');
                    @endphp
                    @if($instagram)
                    <a class="p-2.5 rounded-lg bg-secondary-100 hover:bg-primary-100 text-secondary-600 hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:hover:bg-primary-900/30 dark:text-secondary-300 dark:hover:text-primary-400" href="{{ $instagram }}" target="_blank" rel="noreferrer" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                    </a>
                    @endif
                    @php
                        $tiktok = \App\Models\Setting::getValue('mall_social_tiktok');
                    @endphp
                    @if($tiktok)
                    <a class="p-2.5 rounded-lg bg-secondary-100 hover:bg-primary-100 text-secondary-600 hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:hover:bg-primary-900/30 dark:text-secondary-300 dark:hover:text-primary-400" href="{{ $tiktok }}" target="_blank" rel="noreferrer" aria-label="TikTok">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                    </a>
                    @endif
                    @php
                        $twitter = \App\Models\Setting::getValue('mall_social_twitter');
                    @endphp
                    @if($twitter)
                    <a class="p-2.5 rounded-lg bg-secondary-100 hover:bg-primary-100 text-secondary-600 hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:hover:bg-primary-900/30 dark:text-secondary-300 dark:hover:text-primary-400" href="{{ $twitter }}" target="_blank" rel="noreferrer" aria-label="Twitter/X">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ app()->getLocale() === 'ar' ? 'روابط سريعة' : 'Quick Links' }}</div>
                <nav class="mt-4 space-y-3">
                    <a class="flex items-center gap-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors dark:text-secondary-300 dark:hover:text-primary-400" href="{{ route('shops.index') }}">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'المحلات' : 'Shops' }}
                    </a>
                    @if (($publicSections['offers'] ?? true))
                        <a class="flex items-center gap-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors dark:text-secondary-300 dark:hover:text-primary-400" href="{{ route('offers.index') }}">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}
                        </a>
                    @endif
                    @if (($publicSections['events'] ?? true))
                        <a class="flex items-center gap-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors dark:text-secondary-300 dark:hover:text-primary-400" href="{{ route('events.index') }}">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'الفعاليات' : 'Events' }}
                        </a>
                    @endif
                    <a class="flex items-center gap-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors dark:text-secondary-300 dark:hover:text-primary-400" href="{{ route('about') }}">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'عن المول' : 'About' }}
                    </a>
                    <a class="flex items-center gap-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors dark:text-secondary-300 dark:hover:text-primary-400" href="{{ route('contact.show') }}">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}
                    </a>
                </nav>
            </div>

            {{-- Contact Info --}}
            <div>
                <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ app()->getLocale() === 'ar' ? 'معلومات التواصل' : 'Contact Info' }}</div>
                <div class="mt-4 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-secondary-100 text-secondary-600 dark:bg-secondary-800 dark:text-secondary-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="text-sm text-secondary-600 dark:text-secondary-300">{{ \App\Models\Setting::getValue('mall_contact_address', app()->getLocale() === 'ar' ? config('mall.contact.address_ar') : config('mall.contact.address_en')) }}</div>
                    </div>
                    <a href="tel:{{ \App\Models\Setting::getValue('mall_contact_phone', config('mall.contact.phone')) }}" class="flex items-center gap-3 group">
                        <div class="p-2 rounded-lg bg-secondary-100 text-secondary-600 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:text-secondary-400 dark:group-hover:bg-primary-900/30 dark:group-hover:text-primary-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-sm text-secondary-600 group-hover:text-primary-600 transition-colors dark:text-secondary-300 dark:group-hover:text-primary-400" dir="ltr">{{ \App\Models\Setting::getValue('mall_contact_phone', config('mall.contact.phone')) }}</span>
                    </a>
                    <a href="mailto:{{ \App\Models\Setting::getValue('mall_contact_email', config('mall.contact.email')) }}" class="flex items-center gap-3 group">
                        <div class="p-2 rounded-lg bg-secondary-100 text-secondary-600 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors dark:bg-secondary-800 dark:text-secondary-400 dark:group-hover:bg-primary-900/30 dark:group-hover:text-primary-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm text-secondary-600 group-hover:text-primary-600 transition-colors dark:text-secondary-300 dark:group-hover:text-primary-400">{{ \App\Models\Setting::getValue('mall_contact_email', config('mall.contact.email')) }}</span>
                    </a>
                    @php
                        $whatsappDigits = \App\Models\Setting::normalizeWhatsappPhone(\App\Models\Setting::getValue('mall_contact_whatsapp', config('mall.contact.whatsapp')));
                    @endphp
                    @if ($whatsappDigits)
                        <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noreferrer" class="flex items-center gap-3 group">
                            <div class="p-2 rounded-lg bg-secondary-100 text-secondary-600 group-hover:bg-green-100 group-hover:text-green-600 transition-colors dark:bg-secondary-800 dark:text-secondary-400 dark:group-hover:bg-green-900/30 dark:group-hover:text-green-400 shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </div>
                            <span class="text-sm text-secondary-600 group-hover:text-green-600 transition-colors dark:text-secondary-300 dark:group-hover:text-green-400">WhatsApp</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @php
            $paymentMethods = \Illuminate\Support\Facades\Schema::hasTable('payment_methods')
                ? \App\Models\PaymentMethod::query()->publicAvailable()->ordered()->get()
                : collect();
        @endphp
        @if ($paymentMethods->isNotEmpty())
            <div class="mt-10 pt-8 border-t border-gray-100 dark:border-secondary-800">
                <div class="font-semibold text-secondary-900 dark:text-secondary-100">{{ app()->getLocale() === 'ar' ? 'طرق الدفع المتاحة' : 'Available Payment Methods' }}</div>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    @foreach ($paymentMethods as $method)
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-secondary-100 text-secondary-700 dark:bg-secondary-900 dark:text-secondary-200">
                            @if ($method->icon)
                                <span class="text-lg">{{ $method->icon }}</span>
                            @endif
                            <span class="text-sm font-medium">{{ $method->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-100 dark:border-secondary-800">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-secondary-500 dark:text-secondary-400">
                <div class="text-center sm:text-start">
                    © {{ date('Y') }} {{ \App\Models\Setting::getValue('mall_name', app()->getLocale() === 'ar' ? config('mall.name.ar') : config('mall.name.en')) }}. 
                    {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
                </div>
                <div class="flex items-center gap-4">
                    <a class="hover:text-primary-600 transition-colors dark:hover:text-primary-400" href="#">{{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
                    <a class="hover:text-primary-600 transition-colors dark:hover:text-primary-400" href="#">{{ app()->getLocale() === 'ar' ? 'الشروط والأحكام' : 'Terms of Service' }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
