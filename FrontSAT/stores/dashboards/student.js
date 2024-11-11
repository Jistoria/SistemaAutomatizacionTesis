import { studentService } from '~/services/studentModel/studentService';

export const student = defineStore('student',{
    state: () =>({
        dashboard_status:[],
    }),
    actions:{
        async getDataStatus(token){
            const response = await studentService.getDataStatus(token);
            this.dashboard_status = response.data;
            return
        }
    },
})