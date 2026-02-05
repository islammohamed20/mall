@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إعدادات البريد الإلكتروني' : 'Email Settings' }}</h1>
            <p class="mt-2 text-secondary-700 dark:text-secondary-200">{{ app()->getLocale() === 'ar' ? 'إعداد خادم SMTP لإرسال البريد الإلكتروني من النظام.' : 'Configure SMTP server for sending emails from the system.' }}</p>
        </div>

        {{-- SMTP Settings Form --}}
        <form class="admin-card p-6 space-y-6" method="POST" action="{{ route('admin.email.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إعدادات SMTP' : 'SMTP Configuration' }}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نوع البريد' : 'Mail Driver' }}</label>
                        <select class="form-input" name="mail_mailer">
                            <option value="smtp" {{ $values['mail_mailer'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ $values['mail_mailer'] === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ $values['mail_mailer'] === 'log' ? 'selected' : '' }}>Log ({{ app()->getLocale() === 'ar' ? 'للاختبار' : 'Testing' }})</option>
                        </select>
                        @error('mail_mailer') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'التشفير' : 'Encryption' }}</label>
                        <select class="form-input" name="mail_encryption">
                            <option value="tls" {{ $values['mail_encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $values['mail_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ $values['mail_encryption'] === 'null' || $values['mail_encryption'] === '' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'بدون' : 'None' }}</option>
                        </select>
                        @error('mail_encryption') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'خادم SMTP' : 'SMTP Host' }}</label>
                        <input class="form-input" name="mail_host" value="{{ $values['mail_host'] }}" placeholder="smtp.gmail.com" />
                        @error('mail_host') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المنفذ' : 'Port' }}</label>
                        <input class="form-input" name="mail_port" value="{{ $values['mail_port'] }}" placeholder="587" />
                        @error('mail_port') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المستخدم' : 'Username' }}</label>
                        <input class="form-input" name="mail_username" value="{{ $values['mail_username'] }}" placeholder="your@email.com" />
                        @error('mail_username') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input class="form-input" type="password" name="mail_password" value="{{ $values['mail_password'] }}" placeholder="••••••••" />
                        @error('mail_password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'المُرسل' : 'Sender Identity' }}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد المُرسل منه' : 'From Address' }}</label>
                        <input class="form-input" name="mail_from_address" value="{{ $values['mail_from_address'] }}" placeholder="noreply@yourdomain.com" />
                        @error('mail_from_address') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">{{ app()->getLocale() === 'ar' ? 'اسم المُرسل' : 'From Name' }}</label>
                        <input class="form-input" name="mail_from_name" value="{{ $values['mail_from_name'] }}" placeholder="Mall Name" />
                        @error('mail_from_name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Common Providers Help --}}
            <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 p-4">
                <div class="flex items-center gap-2 text-blue-700 dark:text-blue-400 font-semibold text-sm mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ app()->getLocale() === 'ar' ? 'إعدادات شائعة' : 'Common Provider Settings' }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-blue-800 dark:text-blue-300">
                    <div class="p-3 bg-white/60 dark:bg-secondary-900/60 rounded-lg">
                        <div class="font-bold mb-1">Gmail</div>
                        <div>Host: smtp.gmail.com</div>
                        <div>Port: 587 | Encryption: TLS</div>
                        <div class="text-blue-600 dark:text-blue-500 mt-1">{{ app()->getLocale() === 'ar' ? 'يتطلب App Password' : 'Requires App Password' }}</div>
                    </div>
                    <div class="p-3 bg-white/60 dark:bg-secondary-900/60 rounded-lg">
                        <div class="font-bold mb-1">Outlook / Office 365</div>
                        <div>Host: smtp.office365.com</div>
                        <div>Port: 587 | Encryption: TLS</div>
                    </div>
                    <div class="p-3 bg-white/60 dark:bg-secondary-900/60 rounded-lg">
                        <div class="font-bold mb-1">Yahoo</div>
                        <div>Host: smtp.mail.yahoo.com</div>
                        <div>Port: 465 | Encryption: SSL</div>
                    </div>
                </div>
            </div>

            <button class="btn-primary" type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ app()->getLocale() === 'ar' ? 'حفظ الإعدادات' : 'Save Settings' }}
            </button>
        </form>

        {{-- Test Email Form --}}
        <form class="admin-card p-6 space-y-4" method="POST" action="{{ route('admin.email.test') }}">
            @csrf
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <div class="text-lg font-semibold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'إرسال بريد تجريبي' : 'Send Test Email' }}</div>
            </div>
            <p class="text-sm text-secondary-600 dark:text-secondary-300">{{ app()->getLocale() === 'ar' ? 'أرسل بريد تجريبي للتأكد من صحة الإعدادات.' : 'Send a test email to verify your settings are correct.' }}</p>
            <div class="flex gap-3">
                <input class="form-input flex-1" type="email" name="test_email" placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريد إلكتروني' : 'Enter email address' }}" required />
                <button class="btn-primary whitespace-nowrap" type="submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    {{ app()->getLocale() === 'ar' ? 'إرسال' : 'Send' }}
                </button>
            </div>
            @error('test_email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </form>
    </div>
@endsection
