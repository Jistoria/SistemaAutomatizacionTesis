import { defineNuxtConfig } from 'nuxt/config';
//import i18nConfig from './config/i18n-config.js';
import colorModeConfig from './config/color-mode.js';
import pwaConfig from './pwa-config/pwa-config.js';
import i18nConfig from './config/i18n-config.js';


export default defineNuxtConfig({
  devtools:{ enabled: true},
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/i18n',
    'nuxt-translation-manager',
    '@nuxtjs/color-mode',
    '@vite-pwa/nuxt',
  ],
  
  plugins: [
    '~/plugins/echo.client.ts',
    '~/plugins/echoChannel.client.ts',
    '~/plugins/fetch',
    '~/plugins/auth',
    '~/plugins/i18n-hooks',
    '~/plugins/idb',
    '~/plugins/sweetAlert',
  ],
  components:[
    {
      path: '~/components',
      pathPrefix: false,

    }
  ],
  imports:{
    dirs:['./stores/~'],
  },
  ...i18nConfig,
  ...colorModeConfig,
  ...pwaConfig,
  compatibilityDate: '2024-10-27',
});