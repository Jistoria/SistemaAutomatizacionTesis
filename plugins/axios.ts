import axios from 'axios';
import { getDatabase } from '~/plugins/idb'; // Asegúrate de tener el path correcto al archivo
import type { AxiosInstance } from 'axios';


/**
 * peticion normal :
 * const { $axios } = useNuxtApp();

        const response = await $axios.post('/endpoint', {
        name: 'Johan',
        age: 25,
        });
        console.log(response.data);

 * peticion con un body que no sea json
    const { $axios } = useNuxtApp();
            const formData = new FormData();
            formData.append('file', file); // `file` es un archivo seleccionado por el usuario
            formData.append('description', 'Este es un archivo de ejemplo');

            const response = await $axios.post('/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data', // Esto podría ser omitido; el navegador lo maneja si es `FormData`
            },
            });

            console.log(response.data);
        }

 */

export default defineNuxtPlugin((nuxtApp) => {
  const url = import.meta.env.VITE_API_URL;

  // Crear una instancia de axios
  const api: AxiosInstance = axios.create({
    baseURL: url, // URL base del proyecto
  });

  // Interceptor para añadir los headers antes de cada solicitud
  api.interceptors.request.use(
    async (config) => {
      // Ejemplo: añadir header de autorización si está presente
      const token = await obtenerToken();
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }

      // Añadir headers comunes solo si no están ya definidos
      if (!config.headers['Content-Type']) {
        if (config.data instanceof FormData) {
          // No añadas Content-Type, ya que el navegador se encarga de añadirlo correctamente
        } else {
          config.headers['Content-Type'] = 'application/json';
        }
      }

      if (!config.headers['Accept']) {
        config.headers['Accept'] = 'application/json';
      }

      config.headers['X-Requested-With'] = 'XMLHttpRequest';
      config.withCredentials = true;

      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  // Proveer la instancia tipada de axios al contexto de Nuxt
  nuxtApp.provide('axios', api);
});

// Ejemplo de función para obtener el token desde IndexedDB
async function obtenerToken() {
  try {
    const db = await getDatabase();
    const tx = db.transaction('auth', 'readonly');
    const store = tx.objectStore('auth');
    const authData = await store.get('auth');
    return authData?.token || null;
  } catch (error) {
    console.error('Error obteniendo el token desde IndexedDB:', error);
    return null;
  }
}
