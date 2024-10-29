import { useNuxtApp } from '#app';

// services/authService.ts

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
                body: 
                JSON.stringify(
                    { email, password }
                ),
            });

            if (response) {
                const { user, token } = response;
                return { user, token };
            } else {
                throw new Error('Login fallido: No se obtuvo respuesta');
            }
        } catch (error) {
            console.error('Error en login:', error);
            throw error;
        }
    }

    async logout() {
        const fetchClient = this.getFetchClient(); 
        try {
            const response = await fetchClient('/auth/logout', {
                method: 'POST',
            });

            if (response) {
                return true;
            } else {
                throw new Error('Logout fallido: No se obtuvo respuesta');
            }
        } catch (error) {
            console.error('Error en logout:', error);
            throw error;
        }
    }
}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const authService = new AuthService();
