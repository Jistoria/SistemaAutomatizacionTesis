// plugins/loadPage.ts
import { ref } from 'vue';
import { defineNuxtPlugin } from '#app';

// Crear el plugin con el estado y los métodos de carga
export default defineNuxtPlugin((nuxtApp) => {
  const isLoading = ref(true);
  const isFetching = ref(false);

  const startLoading = () => { isLoading.value = true; };
  const stopLoading = () => { isLoading.value = false; };
  const startFetching = () => { isFetching.value = true; };
  const stopFetching = () => { isFetching.value = false; };

  // Inyectar `loading` con sus propiedades en la app
  nuxtApp.provide('loading', {
    isLoading,
    isFetching,
    startLoading,
    stopLoading,
    startFetching,
    stopFetching,
  });

  // Detener la carga al finalizar el montaje de la página
  nuxtApp.hook('page:finish', () => {
    stopLoading();
  });
});
