import { thesisService } from "~/services/thesisModel/thesisService"

export const tribunal = defineStore('tribunal',{
    state: () =>({
        isLoaded: false,
    }),
    actions:{
        async getDate(){
            if(this.isLoaded){

            }else{
                const response = await thesisService.GetdefenseDateTime()
            }
        },
        async postDate(){
            const response = await thesisService.PostdefenseDateTime()
        },
        resetDefault(){

            this.isLoaded = false;
        },
        
    }
})
