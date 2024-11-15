import { defineStore } from 'pinia'
import { studentService } from '~/services/studentModel/studentService'
import { useIdb } from '~/composables/idb'

export const student = defineStore('student',{
    state: () =>({
        dashboard_status:[],
        faseActual: [],
        requeriments: [],
        //dato de carga 
        isLoaded: false,
    }),
    actions:{
        async getDataStatus(token){
            if(this.isLoaded === true){
                //Cuando se haga un Post y Un PUT dejar el isLoaded en false despues de que el Post Se complete
            }else{
                const response = await studentService.getDataStatus(token);
                this.setDashboardStatus(response.response.data);
                this.setActualPhase(response.faseActual);
                const response2 = await studentService.getRequeriments(token,this.faseActual.thesis_process_phases_id);
                await this.setRequeriments(response2.data)
                console.log(this.requeriments);
                this.isLoaded = true;
                return
            }

        },
        async setRequeriments(requeriments){
            this.requeriments = requeriments;
        },
        async setActualPhase(phase){
            this.faseActual = phase;
        },
        async setDashboardStatus(status){
            this.dashboard_status = status;
        },
    },
})
