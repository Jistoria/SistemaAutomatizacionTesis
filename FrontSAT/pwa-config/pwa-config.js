export default {
  manifest: {
    name: 'Mi App Web',
    short_name: 'AppWeb',
    theme_color: '#4DBA87',
    background_color: '#ffffff',
    display: 'standalone',
    start_url: '/',
    icons: [
      {
        src: '/pwa-config/icons/favicon.png',
        sizes: '64x64',
        type: 'image/png'
      },
      {
        src: '/pwa-config/icons/icon-192x192.png',
        sizes: '192x192',
        type: 'image/png'
      },
      {
        src: '/pwa-config/icons/icon-512x512.png',
        sizes: '512x512',
        type: 'image/png'
      }
    ]
  },
  workbox: {
    runtimeCaching: [
      {
        urlPattern: '/*',
        handler: 'NetworkFirst',
        options: {
          cacheName: 'dynamic-content',
          expiration: {
            maxEntries: 200,
            maxAgeSeconds: 24 * 60 * 60, // 1 día
          },
        },
      },
      {
        urlPattern: /\.(?:png|jpg|jpeg|svg|gif|css|js)$/,
        handler: 'CacheFirst',
        options: {
          cacheName: 'static-assets',
          expiration: {
            maxEntries: 100,
            maxAgeSeconds: 30 * 24 * 60 * 60, // 30 días
          },
        },
      },
    ],
  },
};
