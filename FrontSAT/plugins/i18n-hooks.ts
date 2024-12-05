export default defineNuxtPlugin((nuxtApp) => {
    // Se ejecuta antes de cambiar de idioma
    nuxtApp.hook('i18n:beforeLocaleSwitch', ({ oldLocale, newLocale }) => {
      console.log('onBeforeLanguageSwitch', oldLocale, newLocale);
    });
  
    // Se ejecuta justo después de que el idioma ha sido cambiado
    nuxtApp.hook('i18n:localeSwitched', ({ oldLocale, newLocale }) => {
      //console.log('onLanguageSwitched', oldLocale, newLocale);
      // Guardar el nuevo idioma en localStorage
      localStorage.setItem('locale', newLocale);
    });
  });
  