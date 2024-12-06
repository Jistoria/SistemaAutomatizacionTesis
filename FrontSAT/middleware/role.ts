import { auth } from "~/stores/auth/auth";

// middleware/authMiddleware.ts
export default defineNuxtRouteMiddleware((to) => {
    const authStore = auth(); // Cambia el nombre del store a 'useAuth' para obtener el store 'auth'
    const role = authStore.role; // Obtiene el rol del usuario
    //debo validar que tipos de rutas solo deben estos usuarios

});
  