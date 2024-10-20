import { defineNuxtConfig } from "nuxt/config";

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
  //Esto de aqui es una prueba de todo lo que se puede configurar en pwa junto con su worker
  pwa: {
    // Tipo de registro del Service Worker.
    // 'autoUpdate' significa que el SW se actualizará automáticamente sin intervención del usuario.
    // Puedes cambiarlo a 'prompt' para que el usuario reciba una notificación antes de actualizar.
    registerType: 'autoUpdate',

    // Configuración del manifiesto de la PWA.
    manifest: {
      // Nombre completo de la aplicación, que aparecerá en la pantalla de inicio del usuario.
      name: 'Mi Aplicación PWA',
      
      // Nombre corto que se mostrará si no hay suficiente espacio para el nombre completo.
      short_name: 'AppPWA',
      
      // Breve descripción de la aplicación. Mejora la comprensión del propósito de la PWA.
      description: 'Esta es una aplicación web progresiva construida con Nuxt 3',
      
      // Color de la barra de estado del navegador. Debe alinearse con el esquema de colores de tu aplicación.
      theme_color: '#4DBA87',
      
      // Color de fondo mientras la aplicación está cargando.
      background_color: '#ffffff',
      
      // Definición del modo de presentación de la PWA.
      // `standalone` permite que la aplicación se vea como una app nativa sin la barra del navegador.
      display: 'standalone',
      
      // URL que se abrirá cuando el usuario inicie la PWA.
      start_url: '/',
      
      // Íconos que se utilizarán cuando el usuario añada la PWA a la pantalla de inicio.
      // Se recomienda incluir varias versiones de diferentes tamaños.
      icons: [
        {
          src: '/icon-192x192.png',
          sizes: '192x192',
          type: 'image/png'
        },
        {
          src: '/icon-512x512.png',
          sizes: '512x512',
          type: 'image/png'
        }
      ]
    },
    // Configuración de Workbox para controlar el comportamiento del Service Worker.
    // workbox: {
    //   // URL de fallback cuando se accede a una ruta que no está en la caché mientras se está offline.
    //   navigateFallback: '/',
      
    //   // Estrategias de cacheo para diferentes tipos de recursos.
    //   runtimeCaching: [
    //     {
    //       // Regla para imágenes. Se usa CacheFirst para servir desde la caché primero.
    //       urlPattern: ({ request }) => request.destination === 'image',
    //       handler: 'CacheFirst',
    //       options: {
    //         cacheName: 'images',
    //         expiration: {
    //           maxEntries: 50, // Máximo de 50 imágenes almacenadas.
    //           maxAgeSeconds: 30 * 24 * 60 * 60, // Máximo de 30 días en la caché.
    //         },
    //       },
    //     },
    //     {
    //       // Regla para scripts y estilos. Se usa StaleWhileRevalidate para actualizar mientras se sirve desde la caché.
    //       urlPattern: ({ request }) => request.destination === 'script' || request.destination === 'style',
    //       handler: 'StaleWhileRevalidate',
    //       options: {
    //         cacheName: 'assets',
    //         expiration: {
    //           maxEntries: 50, // Máximo de 50 scripts/estilos almacenados.
    //           maxAgeSeconds: 7 * 24 * 60 * 60, // Máximo de 7 días en la caché.
    //         },
    //       },
    //     }
    //   ]
    // },
    // Opciones de desarrollo para facilitar la prueba de PWA durante la fase de desarrollo.
    // devOptions: {
    //   enabled: true, // Habilita PWA en modo desarrollo para poder probar la funcionalidad.
    // },
  }

})