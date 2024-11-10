import { useNuxtApp } from '#app';

class CourtService {
    private fetchClient: any | null = null; 
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async getCourt(){
        const fetchClient = this.getFetchClient();
        try {
            return console.log('getCourt');
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
}
export const courtService = new CourtService();