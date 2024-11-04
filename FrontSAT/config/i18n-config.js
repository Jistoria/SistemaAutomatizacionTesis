module.exports = {
  i18n: {
    
    locales: [
      { code: 'en', iso: 'en-US', file: 'en-US.json', name: 'English (US)' },
      { code: 'es', iso: 'es-ES', file: 'es-ES.json', name: 'Español (ES)' },
    ],
    defaultLocale: 'es',
    lazy: true,
    langDir: 'locales',
    compilation: {
      strictMessage: false
    }
  }
};
