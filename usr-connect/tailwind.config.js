import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue', //scanne le dossier Slots pour trouver les classes Tailwind utilisées dans les composants Vueph
    ],

    theme: {
        extend: {
           colors: {
                'usr-purple': '#4b208c', // Le violet officiel de l'USR
                'usr-light': '#f3f0ff',
            },
        },
    },

    plugins: [forms],
};
