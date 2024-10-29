import { useIdb } from '~/composables/idb';
import { getDatabase } from '~/plugins/idb';
import { auth } from '~/stores/auth/auth';

const { consult } = useIdb();

export const logout = async () => {
    try {
        const authStore = auth();
        const { $axios } = useNuxtApp();
        const response = await $axios.post('/auth/logout');
        if (response.status == 200) {
            await deleteSession();
            authStore.logout();
            return 'Sesión cerrada';
        }
    } catch (error) {
        console.error('Error durante el cierre de sesión:', error);
        return null;
    }
};    

export const login = async (email: string, password: string) => {
    try {
        const authStore = auth();
        const { $axios } = useNuxtApp();
        const response = await $axios.post('/auth/login', {
        email,
        password,
        });
        if (response.status == 200) {
            const data = response.data;
            await saveAuthData(true, data.user, data.token);
            authStore.login(data.user, data.token);
            return true;
        } else if(response.status == 400){
            return false;
        }
        
    } catch (error) {
        console.error('Error durante el inicio de sesión:', error);
        return null;
    }
};

export const sesionData = async () => {
  try {
    const authStore = auth();
    const data = await consult("auth");
    if(data.length == 0){
        return 'No hay una sesión activa';
    }else{
       const response = await checkSession(data.token);
       console.log('Respuesta de la sesión:', response);
       if (response == false) {
            await deleteSession();
         return 'Sesión cerrada';
       }else{
            authStore.login(data.user, data.token);
           return 'Hay una sesión activa';
       }
    }
  } catch (error) {
    console.error('Error durante la obtención de la sesión:', error);
    return null;
  }
};
export const checkSession = async (token: string) => {
    try {
        const { $axios } = useNuxtApp(); 
        const response = await $axios.get(`/auth/user`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        const data = response.data;
        console.log('Datos de usuario:', data);
        return 'Activa';
        
    } catch (error) {
        console.error('Error al verificar la sesión:', error);
        return false;
    }
};

export const deleteSession = async () => {
  try {
    const db = await getDatabase();
    await db.clear('auth');
    console.log(await consult("auth"));
    return 
    
  } catch (error) {
    console.error('Error al cerrar la sesión:', error);
  }
};

const saveAuthData = async (sesion: boolean, user: Object, token: string) => {
    try {
      // Esperar a que la base de datos esté lista
      const db = await getDatabase();
  
      if (!db) {
        console.error('No se pudo acceder a la base de datos.');
        return;
      }
      // Iniciar una transacción de escritura en el objectStore 'auth'
      const tx = db.transaction('auth', 'readwrite');
      const store = tx.objectStore('auth');
  
      // Guardar/actualizar el registro en el objectStore 'auth'
      await store.put({
        key: 'auth',       // Clave única del registro
        session: sesion,   // Valor booleano de la sesión
        user: user,        // Nombre o ID del usuario
        token: token       // Token de autenticación
      });
      await tx.done; // Esperar a que la transacción finalice
      console.log('Datos de autenticación guardados en IndexedDB');
    } catch (error) {
      console.error('Error al guardar datos de autenticación en IndexedDB:', error);
    }
  };
  
