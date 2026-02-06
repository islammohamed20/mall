<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mall Configuration
    |--------------------------------------------------------------------------
    */

    'name' => [
        'ar' => 'مول وسط البلد',
        'en' => 'West Elbalad Mall',
    ],

    'slogan' => [
        'ar' => 'وجهتك الأولى للتسوق والترفيه في قلب المدينة',
        'en' => 'Your First Destination for Shopping & Entertainment in the Heart of the City',
    ],

    'contact' => [
        'phone' => '+20 123 456 7890',
        'whatsapp' => '+20 123 456 7890',
        'email' => 'info@westelbaladmall.com',
        'address_ar' => 'شارع الملك فيصل، وسط البلد، القاهرة، مصر',
        'address_en' => 'King Faisal Street, Downtown, Cairo, Egypt',
    ],

    'social' => [
        'facebook' => 'https://facebook.com/westelbaladmall',
        'instagram' => 'https://instagram.com/westelbaladmall',
        'twitter' => 'https://twitter.com/westelbaladmall',
        'tiktok' => 'https://tiktok.com/@westelbaladmall',
        'snapchat' => 'westelbaladmall',
    ],

    'working_hours' => [
        'ar' => 'السبت - الخميس: 10 صباحاً - 12 منتصف الليل | الجمعة: 2 ظهراً - 12 منتصف الليل',
        'en' => 'Sat - Thu: 10 AM - 12 AM | Friday: 2 PM - 12 AM',
    ],

    'map' => [
        'latitude' => 27.181635661223417,
        'longitude' => 31.185293801826425,
        'zoom' => 17,
        'embed_url' => 'https://www.google.com/maps?q=27.181635661223417,31.185293801826425&z=17&output=embed',
    ],

    'stats' => [
        'shops' => 150,
        'restaurants' => 30,
        'parking_spots' => 1000,
        'monthly_visitors' => '500K+',
    ],

    'payments' => [
        // Card/Visa payments should only be shown when a real payment gateway is integrated.
        // Keep it false until the gateway/API flow is implemented and configured.
        'card_enabled' => (bool) env('CARD_PAYMENTS_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Sizes
    |--------------------------------------------------------------------------
    */

    'images' => [
        'shop_logo' => [
            'width' => 400,
            'height' => 400,
        ],
        'shop_cover' => [
            'width' => 1200,
            'height' => 600,
        ],
        'slider' => [
            'width' => 1920,
            'height' => 800,
        ],
        'slider_mobile' => [
            'width' => 768,
            'height' => 1024,
        ],
        'offer_banner' => [
            'width' => 1200,
            'height' => 600,
        ],
        'gallery' => [
            'width' => 800,
            'height' => 600,
        ],
    ],
];
