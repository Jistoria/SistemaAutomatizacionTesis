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
        console.log(id_student);
        console.log(id_requirement);
        console.log(status);
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
    async getrequerimentsStudent(id_student:any, id_process_phases:any){
        console.log(id_student);
        console.log(id_process_phases);
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient(`/thesis-tutor/details-student/${id_student}/${id_process_phases}`)
            console.log(response.data)
            return response
        } catch (error) {
            console.log(error);
        }
    }
    async get_pluck(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient(`/thesis-phases-pluck`)
            return response
        } catch (error) {
            
        }
    }

}
export const detailsService = new DetailsService();