@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="card p-5 sm:p-6 lg:p-10">
                <h1 class="text-2xl sm:text-3xl font-bold text-secondary-900">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h1>
                <p class="mt-3 text-sm sm:text-base text-secondary-700">{{ app()->getLocale() === 'ar' ? 'اكتب لنا وسنرد عليك في أقرب وقت.' : "Write to us and we'll respond soon." }}</p>
                <form class="mt-6 sm:mt-8 space-y-4" method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                        <input class="form-input" name="name" value="{{ old('name') }}" required />
                        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                            <input class="form-input" name="email" value="{{ old('email') }}" required />
                            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</label>
                            <input class="form-input" name="phone" value="{{ old('phone') }}" />
                            @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الموضوع' : 'Subject' }}</label>
                        <input class="form-input" name="subject" value="{{ old('subject') }}" />
                        @error('subject') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الرسالة' : 'Message' }}</label>
                        <textarea class="form-input" rows="5" name="message" required>{{ old('message') }}</textarea>
                        @error('message') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn-primary w-full" type="submit">{{ app()->getLocale() === 'ar' ? 'إرسال' : 'Send' }}</button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="card p-5 sm:p-6 lg:p-10">
                    <div class="text-lg sm:text-xl font-bold text-secondary-900">{{ app()->getLocale() === 'ar' ? 'بيانات المول' : 'Mall Info' }}</div>
                    <div class="mt-4 text-secondary-700 space-y-2">
                        <div>{{ \App\Models\Setting::getValue('mall_contact_address', app()->getLocale() === 'ar' ? config('mall.contact.address_ar') : config('mall.contact.address_en')) }}</div>
                        <div>{{ \App\Models\Setting::getValue('mall_contact_phone', config('mall.contact.phone')) }}</div>
                        <div><a class="text-primary-700 hover:text-primary-800" href="mailto:{{ \App\Models\Setting::getValue('mall_contact_email', config('mall.contact.email')) }}">{{ \App\Models\Setting::getValue('mall_contact_email', config('mall.contact.email')) }}</a></div>
                        @php
                            $whatsappDigits = \App\Models\Setting::normalizeWhatsappPhone(\App\Models\Setting::getValue('mall_contact_whatsapp', config('mall.contact.whatsapp')));
                        @endphp
                        @if ($whatsappDigits)
                            <div><a class="text-primary-700 hover:text-primary-800" href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noreferrer">WhatsApp</a></div>
                        @endif
                    </div>
                </div>
                <div class="card overflow-hidden">
                    <div class="h-72 sm:h-96 bg-gray-100">
                        <iframe class="w-full h-full" src="{{ \App\Models\Setting::getValue('mall_map_embed_url', config('mall.map.embed_url')) }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
