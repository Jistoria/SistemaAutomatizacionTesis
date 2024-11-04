// plugins/useLoading.js
import { useNuxtApp } from '#app';

export const useLoading = () => {
  const loading = useNuxtApp().$loading; // Accede a las propiedades inyectadas
  return {
    isLoading: loading.isLoading,
    startLoading: loading.startLoading,
    stopLoading: loading.stopLoading,
    isFetching: loading.isFetching,
    startFetching: loading.startFetching,
    stopFetching: loading.stopFetching,
  };
};
