import { useNuxtApp } from '#app';

class AdminService {
    private fetchClient: any | null = null; 
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
   async getEstudiantes(page: Number, filter: string,search: string){
        const fetchClient = this.getFetchClient();
        console.log(page, filter, search)
        try {const response = await fetchClient(`/users/students?page=${page}&filter=${filter}&search=${search}`, {
            method: 'GET',
            headers: {
                    'Content-Type': 'application/json',
            },
        });
        return response
        } catch (error) {
            return error
        }
        
   }
   async getDocentes(page: Number, filter: string,search: string){
    const fetchClient = this.getFetchClient();
    console.log(page, filter, search)
    try {const response = await fetchClient(`/users/teachers?page=${page}&filter=${filter}&search=${search}`, {
        method: 'GET',
        headers: {
                'Content-Type': 'application/json',
        },
    });
    return response
    } catch (error) {
        return error
    }
    }
    async getProcesosTesis(page: Number, filter: string,search: string){
        const fetchClient = this.getFetchClient();
        console.log(page, filter, search)
        try {
            const response = await fetchClient(`/thesis/process-thesis?page=${page}&filter=${filter}&search=${search}`, {
            method: 'GET',
            headers: {
                    'Content-Type': 'application/json',
            },
        });
        return response
        } catch (error) {
            return error
        }
    }
    async getDashboardAdmin(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient(`/thesis-phases-dashboard`, {
            method: 'GET',
            headers: {
                    'Content-Type': 'application/json',
            },
        });
        return response
        } catch (error) {
            return error
        }
    }
}
export const adminService = new AdminService();