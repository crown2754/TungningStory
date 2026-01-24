import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./app/Livewire/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "tungning-brown": "#4A2C16",
                "tungning-wood": "#8B4513",
                "tungning-paper": "#F5F5DC",
                "tungning-gold": "#D4AF37",
            },
        },
    },

    plugins: [forms, require("daisyui")],
    daisyui: {
        themes: ["retro"], // 使用 retro 作為基底
    },
};
