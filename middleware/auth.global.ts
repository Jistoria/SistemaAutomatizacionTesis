import { auth } from "~/stores/auth/auth";

// middleware/authMiddleware.ts
export default defineNuxtRouteMiddleware((to) => {
    if (import.meta.server) return;
    const nuxtApp = useNuxtApp();

  
    const authStore = auth(); 
    const session = authStore.session; 
    console.log('el console log sesion esta tomando un ' + session)
    if (!session && to.path !== '/login/loginScreen') {
      console.log('no deberia entrar')
      return navigateTo('/login/loginScreen'); 
    }

});
  