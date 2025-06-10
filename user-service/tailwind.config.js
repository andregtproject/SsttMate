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
            colors: {
                colorBackgroundDark: '#181D23',
                colorBackgroundLight: '#E5EAF0',
                grey: '#808080',
                colorNormal: '#FFBB01',
                colorLow: '#00CA4E',
                colorHigh: '#FF3131',
                colorMedium: '#FF7C4C',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // default text = Inter
                baloo: ['Baloo', 'cursive'], // custom untuk judul
            },
        },
    },

    plugins: [forms],
};
