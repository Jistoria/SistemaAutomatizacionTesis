// middleware/authMiddleware.ts
import { auth } from "~/stores/auth/auth";
import { firstLoad } from "~/composables/firs_load";

export default defineNuxtRouteMiddleware(async (to) => {
    if (import.meta.server) return; // Evitar que se ejecute en el servidor

    const authStore = auth();
    const session = authStore.session;
    console.log(authStore.session);
    if(authStore.session === true && authStore.online === true){
        console.log('Sesión activa');
        await firstLoad();
    }
    // Si no hay sesión y no está en la página de login, redirigir al login
    if (!session && to.path !== '/login/loginScreen') {
        return navigateTo('/login/loginScreen'); 
    }

    // Si hay sesión activa y se intenta acceder al login, redirigir a la página de inicio
    if (session && to.path === '/login/loginScreen') {
        return navigateTo('/'); // Ajusta la ruta de inicio si es otra
    }
});
