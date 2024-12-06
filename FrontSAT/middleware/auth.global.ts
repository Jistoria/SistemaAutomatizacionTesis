// middleware/authMiddleware.ts
import { auth } from "~/stores/auth/auth";
import { firstLoad } from "~/composables/firs_load";
import { roles } from "#imports";
const routeRoleMapping = {
    //admin
    "/panel/admin_tesis_panel/": [roles.rol1], 
    //secretaria
    "/panel/list/listManagment":[roles.rol3],
    //Docente
    //Estudiante
    "/panel/student_panel/studentPanelScreen":[roles.rol5]
};
const forbiddenRoutes = [
    '/panel/list/listTeacher'
];
const roleSpecificRestrictions = {
    [roles.rol5]: [
        "/panel/list/listCourt",
        "/panel/list/listDefense",
        "/panel/list/listStudent",
        "/panel/court_panel/courtCreateScreen",
        "/panel/court_panel/courtPanelScreen",
        "/panel/teacher_panel/teacherPanelScreen",
        "/panel/management_panel/managementPanelScreen"
    ],
    [roles.rol3]:[
        "/panel/list/listDefense",
        "/panel/teacher_panel/teacherPanelScreen",
        "/panel/court_panel/courtCreateScreen",

    ],
};

export default defineNuxtRouteMiddleware(async (to) => {
    if (import.meta.server) return; // Evitar que se ejecute en el servidor

    const authStore = auth();
    const session = authStore.session;
    const userRole = authStore.role;

    console.log(authStore.session);
    if(authStore.session === true && authStore.online === true){
        console.log('Sesión activa');
        await firstLoad();
    }
    //
    if (forbiddenRoutes.includes(to.path)) {
        console.warn(`Ruta prohibida: ${to.path}`);
        return navigateTo("/"); // O redirige a otra página como "/"
    }


    // Si no hay sesión y no está en la página de login, redirigir al login
    if (!session && to.path !== '/login/loginScreen') {
        return navigateTo('/login/loginScreen'); 
    }

    // Si hay sesión activa y se intenta acceder al login, redirigir a la página de inicio
    if (session && to.path === '/login/loginScreen') {
        return navigateTo('/'); // Ajusta la ruta de inicio si es otra
    }
    //mape de rutas
    if(session){
        const restrictedRoutesForRole = roleSpecificRestrictions[userRole[0]];
        if (restrictedRoutesForRole && restrictedRoutesForRole.includes(to.path)) {
            console.warn(`Acceso denegado al rol ${userRole[0]} para la ruta: ${to.path}`);
            return navigateTo("/"); // Redirigir a una página de acceso denegado
        }
    }
    //validacion de rutas
    const matchingPrefix = Object.keys(routeRoleMapping).find((prefix) =>
        to.path.startsWith(prefix)
    );
    if (matchingPrefix) {
        const allowedRoles = routeRoleMapping[matchingPrefix];
        console.log(allowedRoles);
        if (!allowedRoles.includes(userRole[0])) {
          console.warn(`Acceso denegado a la ruta: ${to.path}`);
          return navigateTo("/"); // Ajusta esta ruta según tu diseño
        }
    }
});
