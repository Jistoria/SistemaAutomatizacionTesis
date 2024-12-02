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
export const managementService = new ManagementService();