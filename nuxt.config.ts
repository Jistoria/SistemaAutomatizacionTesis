import { defineNuxtConfig } from 'nuxt/config';
//import i18nConfig from './config/i18n-config.js';
import colorModeConfig from './config/color-mode.js';
import pwaConfig from './pwa-config/pwa-config.js';


export default defineNuxtConfig({
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/i18n',
    'nuxt-translation-manager',
    '@nuxtjs/color-mode',
    '@vite-pwa/nuxt',
  ],
  i18n:{
    locales:[
      {
        code: 'en',
        iso: 'en',
        name: 'English',
        file: 'en-US.json'
      },
      {
        code: 'es',
        iso: 'es',
        name: 'Español',
        file: 'es-ES.json'
      },   
    ],
    defaultLocale: 'es',
    lazy: true,
    langDir: 'locales',
    compilation: {
      strictMessage: false
    }
  },
  ...colorModeConfig,
  ...pwaConfig,
  compatibilityDate: '2024-10-27',
});