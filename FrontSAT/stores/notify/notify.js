
import { student } from "../dashboards/student"
import { auth } from "../auth/auth";
import { StudentDetails } from "../details/studentDetails";
export const notify = defineStore('notify',{
    state: () =>({
        
    }),
    actions:{
        async actionNotify(role){
            const authStore = auth();
            const studentStore = student();
            const studentDetailsStore = StudentDetails();
            switch (role) {
                case 'Estudiante-tesis':
                    console.log('Update del estudiante, cambiar')
                    await studentStore.updateStudent(authStore.token);
                break
                case 'Docente-tesis':
                    await studentDetailsStore.update_req();
                    console.log('Update del docente, cambiar')
                break
                
            }
        }
    },
})