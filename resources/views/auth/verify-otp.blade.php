@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-md mx-auto px-4 lg:px-8">
            <div class="card p-6 lg:p-10">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-4">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50">
                        {{ app()->getLocale() === 'ar' ? 'التحقق من البريد الإلكتروني' : 'Email Verification' }}
                    </h1>
                    <p class="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'أدخل الرمز المكون من 6 أرقام الذي أرسلناه إلى' : 'Enter the 6-digit code we sent to' }}
                        <span class="font-semibold text-secondary-900 dark:text-secondary-100 block mt-1">{{ $email }}</span>
                    </p>
                </div>

                <form class="mt-8 space-y-5" method="POST" action="{{ route('otp.verify.submit') }}" x-data="otpForm()">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="code" :value="fullCode">

                    {{-- OTP Input Boxes --}}
                    <div class="grid grid-cols-6 gap-2 sm:gap-3 max-w-xs mx-auto" dir="ltr">
                        @for ($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="1"
                                autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                                x-ref="otp{{ $i }}"
                                x-model="digits[{{ $i }}]"
                                @input="handleInput($event, {{ $i }})"
                                @keydown.backspace="handleBackspace($event, {{ $i }})"
                                @paste.prevent="handlePaste($event)"
                                class="w-full h-12 sm:h-14 text-center text-xl sm:text-2xl font-bold rounded-xl border-2 border-gray-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-secondary-50 shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-500/15 transition-all outline-none"
                            />
                        @endfor
                    </div>

                    @error('code')
                        <div class="text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</div>
                    @enderror

                    <button class="btn-primary w-full" type="submit" :disabled="fullCode.length < 6">
                        {{ app()->getLocale() === 'ar' ? 'تحقق' : 'Verify' }}
                    </button>
                </form>

                {{-- Resend OTP --}}
                <div class="mt-6 text-center" x-data="{ countdown: 60, canResend: false }" x-init="
                    let timer = setInterval(() => {
                        countdown--;
                        if (countdown <= 0) { canResend = true; clearInterval(timer); }
                    }, 1000)
                ">
                    <p class="text-sm text-secondary-600 dark:text-secondary-300">
                        {{ app()->getLocale() === 'ar' ? 'لم يصل الرمز؟' : "Didn't receive the code?" }}
                    </p>
                    <template x-if="!canResend">
                        <p class="mt-1 text-sm text-secondary-400 dark:text-secondary-500">
                            {{ app()->getLocale() === 'ar' ? 'إعادة الإرسال بعد' : 'Resend in' }}
                            <span x-text="countdown" class="font-bold text-primary-600 dark:text-primary-400"></span>
                            {{ app()->getLocale() === 'ar' ? 'ثانية' : 'seconds' }}
                        </p>
                    </template>
                    <template x-if="canResend">
                        <form method="POST" action="{{ route('otp.resend') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-semibold text-sm transition-colors">
                                {{ app()->getLocale() === 'ar' ? 'إعادة إرسال الرمز' : 'Resend Code' }}
                            </button>
                        </form>
                    </template>
                </div>

                <div class="mt-4 text-center">
                    <a class="text-sm text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-200 transition-colors" href="{{ $type === 'register' ? route('register') : route('password.request') }}">
                        ← {{ app()->getLocale() === 'ar' ? 'رجوع' : 'Go back' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function otpForm() {
            const normalizeDigits = (input) => {
                if (!input) return '';

                // Convert Arabic-Indic (٠١٢٣٤٥٦٧٨٩) and Extended Arabic-Indic (۰۱۲۳۴۵۶۷۸۹) digits to ASCII
                const arabicIndic = '٠١٢٣٤٥٦٧٨٩';
                const extendedArabicIndic = '۰۱۲۳۴۵۶۷۸۹';
                let out = '';

                for (const ch of String(input)) {
                    const idx1 = arabicIndic.indexOf(ch);
                    if (idx1 !== -1) {
                        out += String(idx1);
                        continue;
                    }
                    const idx2 = extendedArabicIndic.indexOf(ch);
                    if (idx2 !== -1) {
                        out += String(idx2);
                        continue;
                    }
                    out += ch;
                }

                return out;
            };

            return {
                digits: ['', '', '', '', '', ''],
                get fullCode() {
                    return this.digits.join('');
                },
                handleInput(event, index) {
                    const val = normalizeDigits(event.target.value).replace(/[^0-9]/g, '');
                    this.digits[index] = val.charAt(0) || '';
                    event.target.value = this.digits[index];
                    if (val && index < 5) {
                        this.$refs['otp' + (index + 1)].focus();
                    }
                },
                handleBackspace(event, index) {
                    if (!this.digits[index] && index > 0) {
                        this.$refs['otp' + (index - 1)].focus();
                    }
                },
                handlePaste(event) {
                    const text = normalizeDigits((event.clipboardData || window.clipboardData).getData('text')).replace(/[^0-9]/g, '');
                    for (let i = 0; i < 6 && i < text.length; i++) {
                        this.digits[i] = text.charAt(i);
                        if (this.$refs['otp' + i]) this.$refs['otp' + i].value = text.charAt(i);
                    }
                    const lastIndex = Math.min(text.length, 6) - 1;
                    if (lastIndex >= 0 && this.$refs['otp' + lastIndex]) {
                        this.$refs['otp' + lastIndex].focus();
                    }
                }
            };
        }
    </script>
@endsection
