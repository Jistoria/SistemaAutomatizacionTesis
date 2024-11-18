import { useNuxtApp } from '#app';

class DetailsService {
    private fetchClient: any | null = null; 
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }

    async updatedRequeriemnts(id_student:any, id_requirement:any, status:any){
        const fetchClient = this.getFetchClient();
        const body ={
            status: status
        }
        console.log(body);
        try {
            const response = await fetchClient(`/thesis-tutor/requirements-student/change-status/${id_student}/${id_requirement}`,{
                method:'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body)
            })
            return response
        } catch (error) {
            console.log(error);
        }
    }

}
export const detailsService = new DetailsService();