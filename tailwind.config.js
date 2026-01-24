import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require("daisyui")],
    daisyui: {
        themes: [
            {
                tungning: {
                    primary: "#8b4513", // 馬鞍棕，像老帆船的木頭色
                    secondary: "#2f4f4f", // 深石板灰，像深海或軍官服
                    accent: "#d4af37", // 黃金，貿易的象徵
                    neutral: "#f5f5dc", // 米色，像羊皮紙或地圖
                    "base-100": "#fffaf0", // 象牙白，背景色
                    info: "#3abff8",
                    success: "#36d399",
                    warning: "#fbbd23",
                    error: "#f87272",
                },
            },
        ],
    },
};
