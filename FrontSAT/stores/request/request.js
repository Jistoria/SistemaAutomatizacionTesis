import { requestService } from "~/services/RequestModel/RequestService"

export const request = defineStore('request',{
    state: () =>({
        requests: '',
    }),
    actions:{
        async  sendRequest(data){
            try {
                console.log(data)
                //await requestService.sendrequest(this.requests);    
            } catch (error) {
                console.log(data);
            }
        }
    }   
})