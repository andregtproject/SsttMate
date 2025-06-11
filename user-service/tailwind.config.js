import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Enable dark mode with class strategy

    theme: {
        extend: {
            colors: {
                colorBackgroundDark: '#111827',
                colorBackgroundLight: '#f9fafb',
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
            animation: {
                'gradient-shift': 'gradientShift 8s ease infinite',
                'gradient-red-yellow': 'gradientRedYellow 6s ease infinite',
                'subtle-shift': 'subtleShift 10s ease infinite',
                'pulse-glow': 'pulseGlow 2s infinite',
                'glow': 'glow 3s ease-in-out infinite',
                'float-red': 'float-red 25s infinite linear',
                'float-yellow': 'float-yellow 30s infinite linear',
            },
            keyframes: {
                gradientShift: {
                    '0%': { 'background-position': '0% 50%' },
                    '50%': { 'background-position': '100% 50%' },
                    '100%': { 'background-position': '0% 50%' },
                },
                gradientRedYellow: {
                    '0%': { 'background-position': '0% 50%' },
                    '50%': { 'background-position': '100% 50%' },
                    '100%': { 'background-position': '0% 50%' },
                },
                subtleShift: {
                    '0%, 100%': { background: 'linear-gradient(120deg, #f8fafc 60%, #ffd6c0 100%)' },
                    '50%': { background: 'linear-gradient(120deg, #ffd6c0 60%, #f8fafc 100%)' },
                },
                pulseGlow: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
                glow: {
                    '0%, 100%': { 'box-shadow': '0 0 20px rgba(255, 187, 1, 0.5)' },
                    '50%': { 'box-shadow': '0 0 30px rgba(255, 187, 1, 0.8), 0 0 40px rgba(255, 187, 1, 0.6)' },
                },
                'float-red': {
                    '0%': { 
                        transform: 'translateY(0px) translateX(0px) rotate(0deg)' 
                    },
                    '25%': { 
                        transform: 'translateY(-50px) translateX(30px) rotate(90deg)' 
                    },
                    '50%': { 
                        transform: 'translateY(0px) translateX(60px) rotate(180deg)' 
                    },
                    '75%': { 
                        transform: 'translateY(50px) translateX(30px) rotate(270deg)' 
                    },
                    '100%': { 
                        transform: 'translateY(0px) translateX(0px) rotate(360deg)' 
                    },
                },
                'float-yellow': {
                    '0%': { 
                        transform: 'translateX(0px) translateY(0px) rotate(0deg) scale(1)' 
                    },
                    '20%': { 
                        transform: 'translateX(40px) translateY(-30px) rotate(72deg) scale(1.1)' 
                    },
                    '40%': { 
                        transform: 'translateX(20px) translateY(20px) rotate(144deg) scale(0.9)' 
                    },
                    '60%': { 
                        transform: 'translateX(-30px) translateY(-10px) rotate(216deg) scale(1.05)' 
                    },
                    '80%': { 
                        transform: 'translateX(-10px) translateY(40px) rotate(288deg) scale(0.95)' 
                    },
                    '100%': { 
                        transform: 'translateX(0px) translateY(0px) rotate(360deg) scale(1)' 
                    },
                },
            },
        },
    },

    plugins: [forms],
};
