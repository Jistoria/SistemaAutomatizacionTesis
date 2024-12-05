import { requestService } from "~/services/RequestModel/requestService"

export const request = defineStore('request',{
    state: () =>({

    }),
    actions:{
        async  sendRequest(data){
            try {
                //console.log(data)
                await requestService.sendrequest(data);    
            } catch (error) {
                console.log(error);
            }
        },
        async setDocument(data){
            try {
                await requestService.setDocument(data);
            } catch (error) {
                console.log(error);
            }

        },
        async deleteRequest(data){
            try {
                await requestService.deleteDocument(data);
            } catch (error) {
                console.log(error);

            }
        },
    }   
})