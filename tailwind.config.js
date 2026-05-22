import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                judicial: {
                    50:  '#eef2f9',
                    100: '#d5e0f0',
                    200: '#adc2e2',
                    300: '#7a9dd0',
                    400: '#4d78bd',
                    500: '#2d5aa3',
                    600: '#1e4080',
                    700: '#163060',
                    800: '#0f2247',
                    900: '#0a1830',
                },
                dorado: {
                    400: '#d4a843',
                    500: '#c49a19',
                    600: '#a07e14',
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
