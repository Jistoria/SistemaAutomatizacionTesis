


import { authService } from '~/services/authModel/authService';

export const preuba = defineStore('prueba',{
    state: () =>({
        user: null,
        token: null,
        isAuthenticated: false,
    }),
    actions:{
        async login(email, password) {
            console.log(email)
            console.log(password)
            try {
                // Llama a la función login del servicio de autenticación
                const result = await authService.login(email, password);
                if (result) {
                    // Actualiza el estado si el login es exitoso
                    this.user = result.user;
                    this.token = result.token;
                    this.isAuthenticated = true;
                }
                return result;
            } catch (error) {
                console.error('Error en login en el store:', error);
                throw error;
            }
        },
        async logout() {
            try {
                // Llama a la función logout del servicio de autenticación
                const success = await authService.logout();
                if (success) {
                    // Limpia el estado si el logout es exitoso
                    this.user = null;
                    this.token = null;
                    this.isAuthenticated = false;
                }
                return success;
            } catch (error) {
                console.error('Error en logout en el store:', error);
                throw error;
            }
        },

    }
})