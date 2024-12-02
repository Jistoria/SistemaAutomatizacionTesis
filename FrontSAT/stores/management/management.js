import { managementService } from "~/services/managementModel/managementService"
export const  management = defineStore('management',{
    state: () =>({
        isLoaded: false,
    }),
    actions:{
        async getManagement(){
            if(this.isLoaded){

            }else{
                const response = await managementService.getmanagement()
            }
        },
        resetDefault(){
            this.isLoaded = false;
        },
    }
})