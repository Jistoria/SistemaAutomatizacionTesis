import en from '../locales/en-US.json'
import es from '../locales/es-ES.json'
import { i18n } from './i18n-config'

export default defineI18nConfig(() => {
  return {
    legacy: false,
    fallbackLocale: 'es',
    strategy: 'prefix_except_default',
    messages: {
      en,
      es
    }
  }
})