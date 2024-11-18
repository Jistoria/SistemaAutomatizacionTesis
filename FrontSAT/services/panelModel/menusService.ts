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
    async getListStudents(page: Number, filter: String ,search: String){
        const fetchClient = this.getFetchClient();
        console.log('entre al getlist student')
        try {
            const pagination = 4; 
            const response = await fetchClient(`/thesis-tutor/my-students?pagination=${pagination}&page=${page}&filter=${filter}&search=${search}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            console.log('respuesta de regreso')
            return response
        } catch (error) {
            console.log(error)
        }
    }
    async getdetailsStudent(id:any){
        return 

    }

}

// Exporta una instancia de la clase para su uso en el store u otras partes de la aplicación
export const menusService = new MenusService();
