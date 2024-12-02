import { tutorService } from "~/services/tutorModel/tutorService"
export const tutor = defineStore('tutor',{
    state: () =>({
        isLoaded: false,
    }),
    actions:{
       async getAllTutors(){
            if(this.isLoaded){

            }else{
                const response = await tutorService.getAllTutors();                
            }
       },
       resetDefault(){
            this.isLoaded = false;
    },
    }
})