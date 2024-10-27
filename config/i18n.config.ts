import en from '../locales/en-US.json'
import es from '../locales/es-ES.json'

export default defineI18nConfig(() => {
  return {
    legacy: false,
    fallbackLocale: 'en',
    messages: {
      en,
      es
    }
  }
})