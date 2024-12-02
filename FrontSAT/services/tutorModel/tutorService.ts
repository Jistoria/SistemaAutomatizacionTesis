import { useNuxtApp } from '#app';
class TutorService{
    private fetchClient: any | null = null;
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async getAllTutors(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error);
        }
    }
}
export const tutorService = new TutorService();
