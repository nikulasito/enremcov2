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
            colors: {
                primary: "#19e680",
                "primary-dark": "#15c26b",
                "background-light": "#f8faf9",
                "background-dark": "#0a1410",
                "sidebar-green": "#0d1a14",
                "border-muted": "#e2e8f0",
                "text-muted": "#64748b",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                display: ["Public Sans", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
