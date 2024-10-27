module.exports = {
    manifest: {
      fileName: 'manifest.json', // Archivo del manifest en tu estructura
      name: 'Mi App Web',
      short_name: 'AppWeb',
      theme_color: '#4DBA87',
      background_color: '#ffffff',
      display: 'browser', // Modo navegador
      start_url: '/',
      icons: [
        {
          src: '/pwa-config/icons/favicon.png',
          sizes: '64x64',
          type: 'image/png'
        }
      ]
    },
    workbox: {
      config: 'pwa-config/workbox-config.json', // Ruta al archivo de configuración de Workbox
    },
    icon: {
      source: 'pwa-config/icons', // Carpeta de íconos para tu PWA
    }
  };
  