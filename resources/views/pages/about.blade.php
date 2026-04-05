@extends('layouts.app')

@section('content')
    <section class="section bg-white dark:bg-secondary-950">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-secondary-50">
                {{ app()->getLocale() === 'ar' ? 'عن مول وسط البلد' : 'About West Elbalad Mall' }}
            </h1>
            <div class="mt-4 text-sm sm:text-base text-secondary-700 dark:text-secondary-200 max-w-4xl space-y-3 leading-relaxed">
                @if(app()->getLocale() === 'ar')
                    <p class="font-semibold text-lg">مول وسط البلد – تجربة تسوق وترفيه متكاملة في قلب أسيوط✨</p>
                    <p>يُعد مول وسط البلد واحدًا من أبرز وأحدث المولات التجارية في أسيوط وصعيد مصر، حيث يجمع بين التسوق والترفيه في مكان واحد بتصميم عصري مميز</p>
                    <p class="font-semibold mt-4">يتكون المول من 4 طوابق متكاملة:</p>
                    <p>الطابق الأرضي والأول والثاني يضموا مجموعة متنوعة من المحلات التجارية التي تلبي كل احتياجاتك، من الأزياء إلى الإكسسوارات، بالإضافة إلى محلات الذهب والأحجار الكريمة والألماس.</p>
                    <p>أما الطابق الثالث والروف، فيقدّموا تجربة ترفيهية رائعة من خلال مجموعة مميزة من المطاعم والكافيهات التي تناسب جميع الأذواق</p>
                    <p class="font-semibold mt-4">يتميز المول بتجهيزات حديثة لراحة الزوار، تشمل:</p>
                    <ul class="list-none space-y-1 mr-4">
                        <li>✔ سلالم كهربائية</li>
                        <li>✔ أسانسير بانوراما بتصميم أنيق</li>
                        <li>✔ أسانسير مخصص لنقل البضائع</li>
                    </ul>
                @else
                    <p class="font-semibold text-lg">West Elbalad Mall – A Complete Shopping and Entertainment Experience in the Heart of Assiut✨</p>
                    <p>West Elbalad Mall is one of the most prominent and newest shopping malls in Assiut and Upper Egypt, combining shopping and entertainment in one place with a distinctive modern design.</p>
                    <p class="font-semibold mt-4">The mall consists of 4 complete floors:</p>
                    <p>The ground, first, and second floors feature a diverse range of shops that meet all your needs, from fashion to accessories, in addition to gold, gemstones, and diamond stores.</p>
                    <p>The third floor and rooftop offer a wonderful entertainment experience through a distinctive collection of restaurants and cafes that suit all tastes.</p>
                    <p class="font-semibold mt-4">The mall features modern facilities for visitor comfort, including:</p>
                    <ul class="list-none space-y-1 ml-4">
                        <li>✔ Escalators</li>
                        <li>✔ Panoramic elevator with elegant design</li>
                        <li>✔ Dedicated freight elevator</li>
                    </ul>
                @endif
            </div>

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
                <div class="mt-3 text-secondary-700 dark:text-secondary-200 space-y-2">
                    @if(app()->getLocale() === 'ar')
                        <p>مول وسط البلد هو المكان المثالي للخروج مع العائلة والأصدقاء</p>
                        <p>وكل زيارة هتكون تجربة جديدة!</p>
                        <p>استمتع بيوم مختلف🎉</p>
                    @else
                        <p>West Elbalad Mall is the perfect place to go out with family and friends.</p>
                        <p>Every visit will be a new experience!</p>
                        <p>Enjoy a different day🎉</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
