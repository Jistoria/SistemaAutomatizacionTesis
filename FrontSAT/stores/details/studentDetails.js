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
            thesis_phases_id:''
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
                thesis_phases_id:studentData.thesis_phases_id
            };

            if(import.meta.client){
                localStorage.setItem('studentDetails', JSON.stringify({
                    selectedStudent: this.selectedStudent,
                }));
            }
        },
        async get_requeriments(id_student,id_process_phases){
            const response = await detailsService.getrequerimentsStudent(id_student,id_process_phases);
            //console.log(response.success)
            const data = response.data
            if(data == null){
                return data
            }
            this.RequerimentsSelected = data.requirements
            return data
        },
        async changeStudentreq(id_student,id_req_student, status){
            const response = await detailsService.updatedRequeriemnts(id_student,id_req_student, status);
            //console.log(response.success)
            const data = response.success
            return data
        },
        async get_pluck_phases(){
            const response = await detailsService.get_pluck();
            //console.log(response.success);
            const data = response.data;
            return data;
        },
        async update_req(){
            await this.get_requeriments(this.selectedStudent.id, this.selectedStudent.thesis_phases_id);

        },
        hydrate(){
            const storage_data = localStorage.getItem('studentDetails');
            if(storage_data){
                const { selectedStudent, requirementSelected } = JSON.parse(storage_data);
                this.selectedStudent = selectedStudent;
            }
        },
        async aprobeStudent(){
            const response = await detailsService.aproveStudent();
            return response.success
        }
    },


})
// response.map((data) => ({
//     ...data,
//     requirements: JSON.parse(data.requirements), // Parsear aquí
//   }));
//hacer un middelware que si intenta entrar a StudentDetails sin haber seleccionado redireccionar
//y persistencia de datos al recargar la pagina