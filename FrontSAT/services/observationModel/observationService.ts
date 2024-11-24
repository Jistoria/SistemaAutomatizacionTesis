import { useNuxtApp } from '#app';

class ObservationService {
    private fetchClient: any | null = null; 
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async sendObservation(id:any, id_requeriment:any,content:any){
        const fetchClient = this.getFetchClient();
        const body = {
            student_requirements_id: id_requeriment,
            comment: content,
        }

        try {
            const response = await fetchClient(`/thesis-tutor/observations-requirements/${id}`,{
                method:'POST',
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
    async getObservation(student_id:any,student_requirements_id:any){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient(`/thesis-tutor/observations-requirements/${student_id}/${student_requirements_id}`,{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            console.log(response);
            return response;
            console.log(response);
        } catch (error) {
            console.log(error);
        }
    }
    async deleteObservation(student_id:any,student_requirements_id:any){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient(`/thesis-tutor/observations-requirements/${student_id}/${student_requirements_id}`,{
                method:'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            console.log(response);
        } catch (error) {
            console.log(error);
        }
    }
    async updateObservation(){
        const fetchClient = this.getFetchClient();
        try {
            return console.log('updateObservation');
            const response = await fetchClient('/auth/menus',{
                method:'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    }
}
export const observationService = new ObservationService();