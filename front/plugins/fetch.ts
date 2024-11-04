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
    

        const response = await fetch(`${url}${endpoint}`, {
            ...options,
            headers: defaultHeaders,
            credentials: 'include',
        })
        const data = await response.json();

        if (!response.ok) {
            throw data; 
        }
        return data;

    }
    return{
        provide:{
            fetchClient
        }
    }
}) 
  


