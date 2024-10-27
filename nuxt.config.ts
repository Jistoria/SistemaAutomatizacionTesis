import { defineNuxtConfig } from 'nuxt/config';
const pwaConfig = require('./pwa-config/pwa-config.js');
const colorModeConfig = require('./config/color-mode.config.js');
const i18nConfig = require('./config/i18n.config.js');

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/i18n',
    'nuxt-translation-manager',
    '@nuxtjs/color-mode',
    'nuxt-auth-utils',
    '@vite-pwa/nuxt',
  ],
  // Combina la configuración de PWA, ColorMode y i18n
  ...pwaConfig, // Usa el operador de propagación para PWA
  ...colorModeConfig, // Usa el operador de propagación para color-mode
  ...i18nConfig, // Usa el operador de propagación para i18n
});
