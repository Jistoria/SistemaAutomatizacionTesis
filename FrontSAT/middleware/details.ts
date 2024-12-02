import { StudentDetails } from "~/stores/details/studentDetails";

export default defineNuxtRouteMiddleware((to, from)=>{
    if (import.meta.server) return;
    const studentDetailsStrore = StudentDetails();
    const storedData = localStorage.getItem('studentDetails');

    if (storedData) {
        studentDetailsStrore.hydrate();
    } else if (!studentDetailsStrore.selectedStudent.id) {
        return navigateTo('/panel/list/listStudent')
    }

});