/** @type {import('tailwindcss').Config} */

import { _grayscale } from '#tailwind-config/theme';

export default {
  content: [

  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui'),

  ],
  daisyui: {
    themes: [
      "lemonade",

      {
        UleamTheme:{
          //colores para los cambios de componentes
          'primary': '#C31F2A',
          'success': '#9EBD32',
          'secondary':'#7C7C7C',
          'neutral': '#F5F5F5',
          'info':'#fff5f5',
          'secondary-content':'#000000',
        },
        Themegrays:{
          //colores para los cambios de componentes
          'primary': '#444444',
          'success': '#666666',
          'secondary':'#222222',
          'neutral': '#F5F5F5',
          'info':'#fff5f5',
          'secondary-content':'#878787',
        },
      }
    ],
    
  },
}

