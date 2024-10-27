module.exports = {
  i18n: {
    locales: [
      { code: 'en', iso: 'en-US', file: 'en-US.json', name: 'English (US)' },
      { code: 'es', iso: 'es-ES', file: 'es-ES.json', name: 'Español (ES)' },
      { code: 'ca', iso: 'ca-ES', file: 'ca-ES.json', name: 'Català (ES)' }
    ],
    lazy: true,
    langDir: 'locales/',
    defaultLocale: 'es',
    strategies: 'prefix_except_default',
    vueI18n: './config/vue-i18n.config.js',  // Apunta al archivo externo
  }
};
