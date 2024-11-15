import { studentService } from '~/services/studentModel/studentService';

export const student = defineStore('student',{
    state: () =>({
        dashboard_status:[],
        faseActual: [],
        requeriments: [],
    }),
    actions:{
        async getDataStatus(token){
            const response = await studentService.getDataStatus(token);
            this.setDashboardStatus(response.response.data);
            this.setActualPhase(response.faseActual);
            const response2 = await studentService.getRequeriments(token,this.faseActual.thesis_process_phases_id);
            await this.setRequeriments(response2.data)
            console.log(this.requeriments)
            return
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