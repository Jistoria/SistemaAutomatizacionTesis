import { sessionData } from "~/services/authModel/authService";

/**
 * Plugin de Nuxt para manejar la autenticación.
 * 
 * Este plugin se ejecuta solo en el lado del cliente y verifica el estado de la sesión al cargar la aplicación.
 * 
 * @param {Object} nuxtApp - La instancia de la aplicación Nuxt.
 * 
 * @returns {void}
 * 
 * @example
 * // Proveer el servicio de autenticación
 * const authService = {
 *   getSessionData: sesionData,
 * };
 * 
 * nuxtApp.provide('authService', authService);
 * 
 * @remarks
 * Este plugin no se ejecuta en el lado del servidor.
 * 
 * @throws {Error} Si ocurre un error al inicializar la sesión.
 */
export default defineNuxtPlugin(async (nuxtApp) => {
  // Solo ejecuta este código en el lado del cliente (browser)
  const { ssrContext } = useNuxtApp();
  if (ssrContext) {
  
    return;
  }

  // Ejecuta el servicio de `sesionData` para verificar el estado de la sesión al cargar la aplicación
  try {
    const session = await sessionData();


  } catch (error) {
    console.error('Error al inicializar la sesión:', error);
  }

  // Proveer el servicio de autenticación
  const authService = {
    getSessionData: sessionData,
  };

  nuxtApp.provide('authService', authService);
});
