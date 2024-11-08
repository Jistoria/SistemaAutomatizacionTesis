/** @type {import('tailwindcss').Config} */

export default {
  content: [

  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui'),
    require('@tailwindcss/typography'),

  ],
  daisyui: {
    themes: [
      "light",
      "cupcake",
      "bumblebee",
      "retro",
      "garden",
      "lofi",
      "pastel",
      "fantasy",
      "wireframe",
      "black",
      "dracula",
      "cmyk",
      "autumn",
      "acid",
      "lemonade",
      "coffee",
      "winter",
      "dim",
      "nord",
      "sunset",
    ],
  },
}

