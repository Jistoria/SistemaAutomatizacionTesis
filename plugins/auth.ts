import { sesionData } from '~/services/authService';

export default defineNuxtPlugin(async (nuxtApp) => {
  // Solo ejecuta este código en el lado del cliente (browser)
if (process.server) {
    return;
  }

  // Ejecuta el servicio de `sesionData` para verificar el estado de la sesión al cargar la aplicación
  try {
    const session = await sesionData();
    console.log('Estado de la sesión:', session);

  } catch (error) {
    console.error('Error al inicializar la sesión:', error);
  }

  // Proveer el servicio de autenticación
  const authService = {
    getSessionData: sesionData,
  };

  nuxtApp.provide('authService', authService);
});
