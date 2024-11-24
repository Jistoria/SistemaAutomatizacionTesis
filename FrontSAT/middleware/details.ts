import { StudentDetails } from "~/stores/details/studentDetails";

export default defineNuxtRouteMiddleware((to, from)=>{
    const studentDetailsStrore = StudentDetails();

    if(!studentDetailsStrore.selectedStudent.id){
        console.warn('Intento de acceso a StudentDetails sin estudiante seleccionado. Redirigiendo...');
        return navigateTo('/panel/list/listStudent')
    }

})