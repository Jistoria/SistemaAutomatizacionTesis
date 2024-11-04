import { auth } from "~/stores/auth/auth";

export default defineNuxtPlugin((nuxtApp)=>{
    return
    nuxtApp.hook('page:loading:end', () => {
        const authStore = auth();
        const router = useRouter(); 
        const session = authStore.session;
        if (!session && router.currentRoute.value.path !== '/login/loginScreen') {
             console.log('No hay sesión activa, redirigiendo al login...');
             router.push('/login/loginScreen');
        }        

    });
})
