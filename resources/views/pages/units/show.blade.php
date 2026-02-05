@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-5xl mx-auto px-4 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('units.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-200 hover:bg-gray-200 dark:hover:bg-secondary-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    {{ app()->getLocale() === 'ar' ? 'العودة للوحدات' : 'Back to Units' }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {{-- Image --}}
                <div>
                    @if ($unit->image_url)
                        <div class="rounded-2xl overflow-hidden bg-gray-100 dark:bg-secondary-900 aspect-[4/3]">
                            <img class="w-full h-full object-cover" src="{{ $unit->image_url }}" alt="{{ $unit->title }}" />
                        </div>
                    @else
                        <div class="rounded-2xl bg-gradient-to-br from-primary-200 to-gold-200 dark:from-primary-900/40 dark:to-yellow-900/30 aspect-[4/3] flex items-center justify-center">
                            <svg class="w-20 h-20 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $statusColors = [
                                'available' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'reserved'  => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'sold'      => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                'rented'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$unit->status] ?? '' }}">{{ $unit->status_label }}</span>
                        <span class="px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 text-xs font-bold">{{ $unit->price_type_label }}</span>
                        <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 text-xs font-bold">{{ $unit->type_label }}</span>
                    </div>

                    <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ $unit->title }}</h1>

                    {{-- Meta --}}
                    <div class="mt-3 flex flex-wrap gap-4 text-sm text-secondary-600 dark:text-secondary-300">
                        @if ($unit->floor)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                {{ $unit->floor->name }}
                            </div>
                        @endif
                        @if ($unit->unit_number)
                            <div>{{ app()->getLocale() === 'ar' ? 'وحدة' : 'Unit' }}: {{ $unit->unit_number }}</div>
                        @endif
                        @if ($unit->area)
                            <div>{{ number_format($unit->area) }} {{ app()->getLocale() === 'ar' ? 'م²' : 'sqm' }}</div>
                        @endif
                    </div>

                    {{-- Price --}}
                    @if ($unit->price)
                        <div class="mt-5 p-4 rounded-2xl bg-gradient-to-r from-primary-50 to-gold-50 dark:from-primary-900/20 dark:to-gold-900/20 border border-primary-100 dark:border-primary-800/30">
                            <div class="text-3xl font-bold text-primary-700 dark:text-primary-400">
                                {{ number_format($unit->price, 0) }} {{ $unit->currency }}
                                @if ($unit->price_type === 'rent')
                                    <span class="text-base font-normal text-secondary-500">/ {{ app()->getLocale() === 'ar' ? 'شهرياً' : 'monthly' }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Description --}}
                    @if ($unit->description)
                        <div class="mt-5">
                            <h3 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</h3>
                            <p class="mt-2 text-secondary-700 dark:text-secondary-200 whitespace-pre-line leading-relaxed">{{ $unit->description }}</p>
                        </div>
                    @endif

                    {{-- Features --}}
                    @if ($unit->features)
                        <div class="mt-5">
                            <h3 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المميزات' : 'Features' }}</h3>
                            <ul class="mt-2 space-y-2">
                                @foreach (explode("\n", trim($unit->features)) as $feature)
                                    @if (trim($feature))
                                        <li class="flex items-center gap-2 text-sm text-secondary-700 dark:text-secondary-200">
                                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            {{ trim($feature) }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Contact --}}
                    @if ($unit->contact_phone || $unit->contact_email || $unit->contact_whatsapp)
                        <div class="mt-6 space-y-3">
                            <h3 class="font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'للتواصل والاستفسار' : 'Contact & Inquiries' }}</h3>
                            <div class="flex flex-wrap gap-3">
                                @if ($unit->contact_phone)
                                    <a href="tel:{{ $unit->contact_phone }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 dark:bg-secondary-800 hover:bg-gray-200 dark:hover:bg-secondary-700 transition text-sm font-medium text-secondary-700 dark:text-secondary-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                        {{ $unit->contact_phone }}
                                    </a>
                                @endif
                                @if ($unit->contact_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $unit->contact_whatsapp) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-green-500 hover:bg-green-600 transition text-white text-sm font-medium">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        WhatsApp
                                    </a>
                                @endif
                                @if ($unit->contact_email)
                                    <a href="mailto:{{ $unit->contact_email }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-primary-500 hover:bg-primary-600 transition text-white text-sm font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                        {{ app()->getLocale() === 'ar' ? 'إرسال بريد' : 'Send Email' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related Units --}}
            @if ($relatedUnits->count())
                <div class="mt-12">
                    <h2 class="text-xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'وحدات مشابهة' : 'Similar Units' }}</h2>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($relatedUnits as $related)
                            <a href="{{ route('units.show', $related) }}" class="card overflow-hidden hover:-translate-y-0.5 transition">
                                <div class="aspect-[4/3] bg-gray-100 dark:bg-secondary-900">
                                    @if ($related->image_url)
                                        <img class="w-full h-full object-cover" src="{{ $related->image_url }}" alt="{{ $related->title }}" loading="lazy" />
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="text-sm font-bold text-secondary-900 dark:text-secondary-50 truncate">{{ $related->title }}</div>
                                    @if ($related->price)
                                        <div class="mt-1 text-primary-600 dark:text-primary-400 font-semibold">{{ number_format($related->price, 0) }} {{ $related->currency }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
