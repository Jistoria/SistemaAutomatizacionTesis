
import { student } from "../dashboards/student"
import { auth } from "../auth/auth";
export const notify = defineStore('notify',{
    state: () =>({
        
    }),
    actions:{
        async actionNotify(role){
            const authStore = auth();
            const studentStore = student();
            switch (role) {
                case 'Estudiante-tesis':
                    console.log('Update del estudiante, cambiar')
                    await studentStore.updateStudent(authStore.token);
                break
                case 'Docente-tesis':
                    console.log('Update del docente, cambiar')
                break
                
            }
        }
    },
})