import { getDatabase } from '~/plugins/idb'; // Asegúrate de tener el path correcto al archivo

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
  

export default defineNuxtPlugin(()=>{
    const fetchClient =  async (
        endpoint: string, 
        options: RequestInit = {}
    
    ) => {
        const url = import.meta.env.VITE_API_URL;
        const defaultHeaders:any = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(options.headers || {}),
    
        }
        const token = await obtenerToken();
        if (token) {
            defaultHeaders['Authorization'] = `Bearer ${token}`;
        }
        if (!defaultHeaders['Content-Type']) {
            if (options.body instanceof FormData) {
                // El navegador se encarga de Content-Type con FormData
            } else {
                defaultHeaders['Content-Type'] = 'application/json';
            }
        }
    
        return fetch(`${url}${endpoint}`, {
            ...options,
            headers: defaultHeaders,
            credentials: 'include', // Permite enviar cookies con la solicitud
        })
        .then(async response => {
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error en la solicitud');
            }
            return response.json();
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            throw error;
        });
    }
    return{
        provide:{
            fetchClient
        }
    }
}) 
  


