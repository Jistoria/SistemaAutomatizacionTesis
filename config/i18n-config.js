module.exports = {
    i18n: {
      locales: [
        { code: 'en', name: 'English', file: 'en.json' },
        { code: 'es', name: 'Español', file: 'es.json' }
      ],
      lazy: true, // Carga diferida de los archivos de idioma
      langDir: 'locales/', // Directorio donde se almacenarán los archivos de idioma
      defaultLocale: 'es', // Idioma por defecto
      strategy: 'prefix', // Añade un prefijo a las rutas basado en el idioma (/es/, /en/)
      vueI18n: {
        fallbackLocale: 'es', // Idioma de fallback
        messages: {
          es: {
            welcome: 'Bienvenido',
          },
          en: {
            welcome: 'Welcome',
          }
        }
      }
    }
  };
  