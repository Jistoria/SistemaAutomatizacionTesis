// services/authService.ts

import { useNuxtApp } from '#app';
import { useIdb } from '~/composables/idb';
import { getDatabase } from '~/plugins/idb';
import { auth } from '~/stores/auth/auth';
import { sweetAlert } from '#imports';

const { consult } = useIdb();
const swal = sweetAlert() as ReturnType<typeof sweetAlert>;
class AuthService {
    private fetchClient: any | null = null;

    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }

    async login(email: string, password: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });
            console.log('Respuesta:', response);
            
            if (response.success == true) {
                await this.saveAuthData(true, response.data.user, response.data.token);
                
                return response;
            }else {
                
                return response;
            }

        } catch (error) {
            swal.showAlert('error','normal',{title: 'Error', text: 'Credenciales Inválidas',confirmType: 'normal'})
            return error;
        }
    }
    async authMicrosoft(name: string, email: string, jwt: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('api/microsoft/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    name: name,
                    email: email,
                    jwt: jwt
                 }),
            });
            console.log('Respuesta:', response);

        } catch (error) {
            swal.showAlert('error','normal',{title: 'Error', text: 'Credenciales Inválidas',confirmType: 'normal'})
            return error;
        }
    }

    async logout() {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/logout', {
                method: 'POST',
            });

            if (response.success == true) {
                await this.deleteSession();
                auth().setLogout();
                swal.showAlert('success','right',{title: 'Sesión cerrada', text: '',confirmType: 'timer'})
                return true;
            } else if(response.status == 401) {
                swal.showAlert('error','right',{title: 'Algo salio mal', text: '',confirmType: 'timer'})
                return false;
            }
            
        } catch (error) {
            swal.showAlert('success','right',{title: 'Algo salio mal', text: '',confirmType: 'timer'})
            return false;
        }
    }

    async sesionData() {
        try {
            const data = await consult('auth');
            if (data.length === 0) {
                return 'No hay una sesión activa';
            } else {
                const response = await this.checkSession(data.token);
                if (!response) {
                    if(auth().online == true){
                        await this.deleteSession();
                    }else {
                        console.log('Sesión cerrada');
                    }
                    return 'Sesión cerrada';
                } else {
                    auth().setLogin(data.user, data.token);
                    return 'Hay una sesión activa';
                }
            }
        } catch (error) {
            console.error('Error durante la obtención de la sesión:', error);
            return null;
        }
    }

    async checkSession(token: string) {
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/user', {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });

            if (response.success == true) {
                console.log('Datos de usuario:', response.data);
                return 'Activa';
            } else {
                throw new Error('Sesión no activa');
            }
        } catch (error) {
            console.error('Error al verificar la sesión:', error);
            return false;
        }
    }

    async deleteSession() {
        try {
            const db = await getDatabase();
            await db.clear('auth');
            console.log(await consult('auth'));
        } catch (error) {
            console.error('Error al cerrar la sesión:', error);
        }
    }

    async saveAuthData(sesion: boolean, user: Object, token: string) {
        try {
            const db = await getDatabase();
            if (!db) {
                console.error('No se pudo acceder a la base de datos.');
                return;
            }

            const tx = db.transaction('auth', 'readwrite');
            const store = tx.objectStore('auth');
            await store.put({
                key: 'auth',
                session: sesion,
                user: user,
                token: token,
            });

            await tx.done;
            console.log('Datos de autenticación guardados en IndexedDB');
        } catch (error) {
            console.error('Error al guardar datos de autenticación en IndexedDB:', error);
        }
    }
}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const authService = new AuthService();
export const sessionData = authService.sesionData.bind(authService);
