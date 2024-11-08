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
    async sendObservation(){
        const fetchClient = this.getFetchClient();
        try {
            return console.log('sendObservation');
            const response = await fetchClient('/auth/menus',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    }
    async getObservation(){
        const fetchClient = this.getFetchClient();
        try {
            return console.log('setObservation');
            const response = await fetchClient('/auth/menus',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    }
    async deleteObservation(){
        const fetchClient = this.getFetchClient();
        try {
            return console.log('deleteObservation');
            const response = await fetchClient('/auth/menus',{
                method:'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
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