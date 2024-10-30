import { auth } from "~/stores/auth/auth";

// middleware/authMiddleware.ts
export default defineNuxtRouteMiddleware((to) => {
    const authStore = auth(); // Cambia el nombre del store a 'useAuth' para obtener el store 'auth'
    const session = authStore.session; // Obtiene la sesión del usuario

    // Si el usuario no tiene sesión y no está intentando acceder a la página de login
    if (!session && to.path !== '/login/loginScreen') {
      return navigateTo('/login/loginScreen'); // Redirige al login si no tiene sesión
    }
});
  