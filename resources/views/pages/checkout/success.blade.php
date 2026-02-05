@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="max-w-3xl mx-auto px-4 lg:px-8">
            <div class="card p-6 sm:p-8 text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-secondary-900 dark:text-secondary-50">
                    {{ app()->getLocale() === 'ar' ? 'تم استلام طلبك' : 'Order Received' }}
                </h1>
                <p class="mt-2 text-secondary-600 dark:text-secondary-300">
                    {{ app()->getLocale() === 'ar' ? 'سنقوم بالتواصل معك لإتمام عملية الدفع.' : 'We will contact you to complete the payment.' }}
                </p>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('shops.index') }}" class="btn-primary">
                        {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                    </a>
                    <a href="{{ route('home') }}" class="btn-outline">
                        {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
