import { useNuxtApp } from '#app';

class RequestService {
    private fetchClient: any | null = null; 
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async sendrequest(content:any){
        const fetchClient = this.getFetchClient();
        try {
            return console.log(content);
            const response = await fetchClient('/auth/menus',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    };
    async setDocument(content:String){
        const fetchClient = this.getFetchClient();
        try {
            return console.log(content);
            const response = await fetchClient('/auth/menus',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    };
    async deleteDocument(content:String){
        const fetchClient = this.getFetchClient();
        try {
            return console.log(content);
            const response = await fetchClient('/auth/menus',{
                method:'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
        } catch (error) {
            console.log(error);
        }
    };
}
export const requestService = new RequestService();