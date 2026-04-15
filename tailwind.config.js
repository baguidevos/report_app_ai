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
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Morandi Color Palette
                primary: {
                    DEFAULT: '#a8a29e',
                    hover: '#78716c',
                    light: '#d6d3d1',
                },
                cream: {
                    DEFAULT: '#F8F5F0',
                    dark: '#F5F0E8',
                },
                text: {
                    primary: '#44403c',
                    secondary: '#78716c',
                },
                accent: {
                    DEFAULT: '#d6d3d1',
                    light: '#e7e5e4',
                },
            },
            borderRadius: {
                'card': '16px',
            },
            backdropBlur: {
                'glass': '12px',
            },
            transitionTimingFunction: {
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },

    plugins: [forms],
};
