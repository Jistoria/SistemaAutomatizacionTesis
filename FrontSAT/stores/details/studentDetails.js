export const StudentDetails = defineStore('studentDetails',{
    state: () =>({
        selectedStudent: {
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
                name: studentData.name,
                email:studentData.email,
                phase_state_now:studentData.phase_state_now,
                phase_name:studentData.phase_name,
                period_academic_name:studentData.period_academic_name,
                title:studentData.title,
            };
            this.RequerimentsSelected = studentData.requirements

        }
    }
})
// response.map((data) => ({
//     ...data,
//     requirements: JSON.parse(data.requirements), // Parsear aquí
//   }));
//hacer un middelware que si intenta entrar a StudentDetails sin haber seleccionado redireccionar
//y persistencia de datos al recargar la pagina