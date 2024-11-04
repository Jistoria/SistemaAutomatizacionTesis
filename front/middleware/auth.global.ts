// middleware/authMiddleware.ts
import { auth } from "~/stores/auth/auth";

export default defineNuxtRouteMiddleware((to) => {
    if (import.meta.server) return; // Evitar que se ejecute en el servidor

    const authStore = auth();
    const session = authStore.session;

    // Si no hay sesión y no está en la página de login, redirigir al login
    if (!session && to.path !== '/login/loginScreen') {
        return navigateTo('/login/loginScreen'); 
    }

    // Si hay sesión activa y se intenta acceder al login, redirigir a la página de inicio
    if (session && to.path === '/login/loginScreen') {
        return navigateTo('/'); // Ajusta la ruta de inicio si es otra
    }
});
