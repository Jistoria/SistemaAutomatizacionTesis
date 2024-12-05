import { useNuxtApp } from '#app';
class ManagementService {
    private fetchClient: any | null = null;
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async getmanagement(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('/analyst-degree/students',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response;
            console.log(response);
        } catch (error) {
            console.log(error);
        }
    }
    async changePreRequeriment(id_prerequirement:any,status:any){
        const fetchClient = this.getFetchClient();
        const body ={
            status: status
        }
        console.log(id_prerequirement);
        console.log(status)
        try {
            const response = await fetchClient(`/analyst-degree/change-status-prerequirements/${id_prerequirement}`,{
                method:'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body)
            })
            console.log(response);
            return response;
        } catch (error) {
            console.log(error);
        }
    }
}
export const managementService = new ManagementService();