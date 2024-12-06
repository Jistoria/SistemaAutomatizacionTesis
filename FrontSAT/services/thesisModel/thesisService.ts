import { useNuxtApp } from '#app';
class ThesisService{
    private fetchClient: any | null = null;
    private getFetchClient() {
        if (!this.fetchClient) {
            const { $fetchClient } = useNuxtApp();
            this.fetchClient = $fetchClient;
        }
        return this.fetchClient;
    }
    async getDashboardThesis(){
        const fetchClient = this.getFetchClient();
        return
        try {
            const response = await fetchClient('/thesis-tutor/dashboard',{
                method:'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }
    }
    //
    async GetdefenseDateTime(){
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
            console.log(error)
        }
    }   
    async PostdefenseDateTime(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }
    }
    async setTribunal(){
        const fetchClient = this.getFetchClient();
        try {
            const response = await fetchClient('',{
                method:'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            return response
        } catch (error) {
            console.log(error)
        }
    }


}
export const thesisService = new ThesisService();
