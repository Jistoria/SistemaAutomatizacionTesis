import { managementService } from "~/services/managementModel/managementService"
export const  management = defineStore('management',{
    state: () =>({
        isLoaded: false,
        management: [],
        stuendent_data:[],
        current_page: 1,
        last_page: 0,
    }),
    actions:{
        async getManagement(page=1,filter='',search=''){
            if(this.isLoaded){

            }else{
                const response = await managementService.getmanagement(page,filter,search)
                console.log(response);
                this.management = response.data;
                this.current_page = response.data.current_page;
                this.last_page = response.data.last_page;
            }
        },
        resetDefault(){
            this.isLoaded = false;
        },
        async changePreRequesite(student_prerequirements_id,status){
            
            const response = await managementService.changePreRequeriment(student_prerequirements_id,status);
            console.log(response.success)
            const data = response.success
            return data

        },
    }
})