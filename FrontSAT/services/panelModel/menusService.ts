// services/authService.ts

import { useNuxtApp } from '#app';
import { useIdb } from '~/composables/idb';
import { getDatabase } from '~/plugins/idb';
import { auth } from '~/stores/auth/auth';
import { sweetAlert } from '#imports';

const { consult } = useIdb();
const swal = sweetAlert() as ReturnType<typeof sweetAlert>;
class MenusService {
    private fetchClient: any | null = null;

    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async menus(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/menus',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }

    }
    async getDataDashborad(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/menus',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }
    }
    async getListStudents(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/auth/menus',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }
    }

}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const menusService = new MenusService();
