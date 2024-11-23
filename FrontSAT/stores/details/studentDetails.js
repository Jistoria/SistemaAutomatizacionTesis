import { detailsService } from "~/services/details/detailsService";
export const StudentDetails = defineStore('studentDetails',{
    state: () =>({
        selectedStudent: {
            id:null,
            name: '',
            email:'',
            phase_state_now:'',
            phase_name:'',
            period_academic_name:'',
            title:'',
        },
        RequerimentsSelected: [],
        isLoaded:false
    }),
    actions:{
        setStudentDetails(studentData){
            this.selectedStudent = {
                id:studentData.id,
                name: studentData.name,
                email:studentData.email,
                phase_state_now:studentData.phase_state_now,
                phase_name:studentData.phase_name,
                period_academic_name:studentData.period_academic_name,
                title:studentData.title,
            };
            this.RequerimentsSelected = typeof studentData.requirements === 'string'
            ? JSON.parse(studentData.requirements)
            : studentData.requirements;
            //this.RequerimentsSelected = studentData.requirements
        },
        async changeStudentreq(id_student,id_req_student, status){
            const response = await detailsService.updatedRequeriemnts(id_student,id_req_student, status);
            console.log(response.success)
            const data = response.success
            return data
        },

    }

})
// response.map((data) => ({
//     ...data,
//     requirements: JSON.parse(data.requirements), // Parsear aquí
//   }));
//hacer un middelware que si intenta entrar a StudentDetails sin haber seleccionado redireccionar
//y persistencia de datos al recargar la pagina