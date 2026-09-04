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
