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
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                display: ['Gliker', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    soft: {
                        bg: '#F7FAF7',
                        sidebar: '#EEF5EF',
                        border: '#E2ECE3',
                        borderDark: '#D9E6DB',
                        activeBg: '#DCEFE0',
                        activeText: '#1F6B45',
                        hoverBg: '#E8F4EA',
                        textMain: '#163123',
                        textSec: '#6B7A71',
                        accent: '#2C8C5A',
                        warnBg: '#FCECC8',
                        warnText: '#A66B00',
                    },
                    green: {
                        500: '#6FA65E',
                        700: '#1D6B46',
                    },
                    pink: {
                        300: '#F7B7A5',
                    },
                    coral: {
                        500: '#D98572',
                    },
                    gold: {
                        500: '#E9B63D',
                    },
                    navy: {
                        900: '#172238',
                    },
                },
                neutral: {
                    charcoal: '#2C2C2C',
                    white: '#FFFFFF',
                }
            }
        },
    },

    plugins: [forms],
};
