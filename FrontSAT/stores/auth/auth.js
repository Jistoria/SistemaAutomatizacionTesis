import { authService } from '~/services/authModel/authService';

import { panel } from '../panel/panel';
import { student } from '../dashboards/student';
import { notifyService } from '~/services/Notify/notify';
import { admin } from '../dashboards/admin';
/**
 * Define una tienda de autenticación utilizando Pinia.
 * 
 * @store auth
 * 
 * @state
 * @property {string} placeholder - Texto de prueba para Pinia.
 * @property {Array} user - Información del usuario autenticado.
 * @property {string|null} token - Token de autenticación.
 * @property {boolean} session - Estado de la sesión de usuario.
 * @property {string|null} role - Rol del usuario autenticado.
 * 
 * @actions
 * @method login - Inicia sesión del usuario.
 * @param {Object} user - Información del usuario.
 * @param {string} token - Token de autenticación.
 * 
 * @method logout - Cierra sesión del usuario.
 */
export const auth = defineStore('auth',{
    state: () =>({
        placeholder:'prueba pinia nesteado',
        user: [],
        token: null,
        session: false,
        role: null,
        online:false,
    }),
    actions:{
        async setLogin(user,token){
            try {
                this.user = user
                this.token = token
                this.session = true
                this.role = user.role
            } catch (error) {
                console.error('Error en login en el store:', error);
                throw error;
            }
        },
        async setLogout(){
            try {
                this.user = []
                this.token = null
                this.session = false
                this.role = null
                //close Is Loaded
                const studentStore = student();
                const panelStore = panel();
                const adminStore = admin();
                adminStore.resetDefault();
                studentStore.resetDefault();
                panelStore.resetDefault();

            } catch (error) {
                console.error('Error en logout en el store:', error);
                throw error;
            }
            
        },
        async login(email, password){
            try {
                const response = await authService.login(email, password)
                if(response.success == true){
                    this.setLogin(response.data.user,response.data.token)
                    return response
                }
                else{
                    return response
                }
            } catch (error) {
                console.log(error)
                return success = false
            }
        },
        async authMicrosoft(perfile, jwt){
            const name = perfile.name
            const email = perfile.email
            const response = await authService.authMicrosoft(name, email, jwt)
            if (response.success == true){
                this.setLogin(response.data.user,response.data.token)

            }
            return response
            
        },
        async logout(){
            try {
                const studentStore = student();
                const adminStore = admin();
                if(this.role == 'Estudiante-tesis'){
                    studentStore.clearStore();
                }else if(this.role == 'Administrador-tesis'){
                    adminStore.clearData();
                }
                const response = await authService.logout()
                if(response == true){

                    this.setLogout()
                    
                }
                await notifyService.unlistenChannel();
                localStorage.clear();
                return true
            } catch (error) {
                console.error('Error en logout en el store:', error);
                throw error;
            }
        },

    },
})