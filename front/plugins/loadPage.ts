// plugins/loadPage.ts
import { ref, computed } from 'vue';
import { defineNuxtPlugin } from '#app';

export default defineNuxtPlugin((nuxtApp) => {
  const isLoading = ref(true);
  const isFetching = ref(false);
  const animationType = ref<'spinner' | 'progress-bar' | 'dots'>('spinner');

  const startLoading = (type: 'spinner' | 'progress-bar' | 'dots' = 'spinner') => {
    animationType.value = type;
    isLoading.value = true;
  };
  
  const stopLoading = () => { isLoading.value = false; };
  
  const startFetching = () => { isFetching.value = true; };
  const stopFetching = () => { isFetching.value = false; };
  
  const currentAnimation = computed(() => animationType.value);

  // Inyectar `loading` con sus propiedades en la app
  nuxtApp.provide('loading', {
    isLoading,
    isFetching,
    animationType: currentAnimation,
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
