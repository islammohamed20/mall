import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                // Brand colors extracted from West Elbalad Mall logo
                // Golden/copper gradient tones
                primary: {
                    50: '#fdf8f0',
                    100: '#f9ecd8',
                    200: '#f2d5ae',
                    300: '#e9b87c',
                    400: '#df9548',
                    500: '#d67c2a', // Main golden/copper
                    600: '#c86420',
                    700: '#a64b1d',
                    800: '#863d1f',
                    900: '#6d331c',
                    950: '#3b180c',
                },
                // Dark brown/gray for text
                secondary: {
                    50: '#f6f6f6',
                    100: '#e7e7e7',
                    200: '#d1d1d1',
                    300: '#b0b0b0',
                    400: '#888888',
                    500: '#6d6d6d',
                    600: '#5d5d5d',
                    700: '#4a4a4a',
                    800: '#3d3d3d',
                    900: '#2d2d2d', // Main dark
                    950: '#1a1a1a',
                },
                // Accent gold for highlights
                gold: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
            },
            fontFamily: {
                // Arabic font
                arabic: ['Cairo', 'Noto Sans Arabic', ...defaultTheme.fontFamily.sans],
                // English font
                sans: ['Poppins', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            minHeight: {
                'hero': '600px',
                'hero-mobile': '500px',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [
        function ({ addUtilities, addVariant }) {
            // Add RTL variant
            addVariant('rtl', '[dir="rtl"] &');
            addVariant('ltr', '[dir="ltr"] &');
            
            // Add RTL utilities
            addUtilities({
                '.flip-x': {
                    transform: 'scaleX(-1)',
                },
                '.text-start': {
                    textAlign: 'start',
                },
                '.text-end': {
                    textAlign: 'end',
                },
                '.start-0': {
                    insetInlineStart: '0',
                },
                '.end-0': {
                    insetInlineEnd: '0',
                },
                '.ms-auto': {
                    marginInlineStart: 'auto',
                },
                '.me-auto': {
                    marginInlineEnd: 'auto',
                },
                '.ps-4': {
                    paddingInlineStart: '1rem',
                },
                '.pe-4': {
                    paddingInlineEnd: '1rem',
                },
                '.border-s': {
                    borderInlineStartWidth: '1px',
                },
                '.border-e': {
                    borderInlineEndWidth: '1px',
                },
            });
        },
    ],
};
