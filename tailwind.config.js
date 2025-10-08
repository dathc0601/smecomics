import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'Quicksand', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'orange-manga': {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                },
                'peach': {
                    50: '#fef5ee',
                    100: '#fde8d7',
                    200: '#fbcdae',
                    300: '#f8aa7a',
                    400: '#f58244',
                    500: '#f2611e',
                    600: '#e34814',
                    700: '#bc3513',
                    800: '#962d17',
                    900: '#792716',
                },
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
                '3xl': '2rem',
            },
            boxShadow: {
                'manga': '0 4px 12px rgba(249, 115, 22, 0.15)',
                'manga-lg': '0 10px 25px rgba(249, 115, 22, 0.2)',
            },
        },
    },

    plugins: [forms],
};
