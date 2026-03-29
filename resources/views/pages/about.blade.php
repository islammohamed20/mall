@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">
                {{ app()->getLocale() === 'ar' ? 'عن مول وسط البلد' : 'About West Elbalad Mall' }}
            </h1>
            <p class="mt-4 text-sm sm:text-base text-secondary-700 dark:text-secondary-200 max-w-3xl">
                {{ app()->getLocale() === 'ar'
                    ? 'نقدم تجربة متكاملة للتسوق والترفيه تجمع أفضل العلامات، خيارات الطعام، والخدمات في قلب المدينة.'
                    : 'We offer a complete shopping and entertainment experience, bringing top brands, dining options, and services together in the heart of the city.' }}
            </p>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="card p-5 sm:p-6">
                    <div class="text-sm text-primary-700 font-semibold">{{ app()->getLocale() === 'ar' ? 'رؤيتنا' : 'Vision' }}</div>
                    <div class="mt-2 text-secondary-800 dark:text-secondary-100">
                        {{ app()->getLocale() === 'ar' ? 'أن نكون الوجهة الأولى للعائلة للتسوق والترفيه.' : 'To be the first family destination for shopping and entertainment.' }}
                    </div>
                </div>
                <div class="card p-5 sm:p-6">
                    <div class="text-sm text-primary-700 font-semibold">{{ app()->getLocale() === 'ar' ? 'مهمتنا' : 'Mission' }}</div>
                    <div class="mt-2 text-secondary-800 dark:text-secondary-100">
                        {{ app()->getLocale() === 'ar' ? 'تقديم تجربة راقية بخدمات متكاملة وبيئة آمنة.' : 'Deliver an elegant experience with complete services and a safe environment.' }}
                    </div>
                </div>
                <div class="card p-5 sm:p-6">
                    <div class="text-sm text-primary-700 font-semibold">{{ app()->getLocale() === 'ar' ? 'قيمنا' : 'Values' }}</div>
                    <div class="mt-2 text-secondary-800 dark:text-secondary-100">
                        {{ app()->getLocale() === 'ar' ? 'الجودة، راحة الزوار، الشفافية، التطوير المستمر.' : 'Quality, visitor comfort, transparency, and continuous improvement.' }}
                    </div>
                </div>
            </div>

            <div class="mt-10 card p-5 sm:p-6 lg:p-10">
                <div class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-secondary-50">{{ app()->getLocale() === 'ar' ? 'رسالة الإدارة' : 'Management Message' }}</div>
                <p class="mt-3 text-secondary-700 dark:text-secondary-200">
                    {{ app()->getLocale() === 'ar'
                        ? 'نسعد باستقبالكم يومياً ونعدكم بتجربة تسوق مميزة. نعمل باستمرار على تطوير الخدمات والفعاليات لتناسب كل أفراد الأسرة.'
                        : 'We are happy to welcome you every day and promise a great shopping experience. We continuously improve services and events to suit the whole family.' }}
                </p>
            </div>
        </div>
    </section>
@endsection
