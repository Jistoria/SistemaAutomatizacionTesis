<script setup>
import { auth } from '~/stores/auth/auth';
import { useRoute, useRouter } from 'vue-router';
import { firstLoad } from '~/composables/firs_load';
import { sweetAlert } from '~/composables/sweetAlert';
import { useMicrosoftAuth } from '~/composables/authMicrosoft';
const swal = sweetAlert();
const { $echoReady } = useNuxtApp();
const authStore = auth()
const router = useRouter();
const route = useRoute();
const microsoftToken = ref(null);

// Composable para autenticación con Microsoft
const { getAccessToken,login, getUserProfile } = useMicrosoftAuth();

import { ref } from 'vue';
import { inject } from 'vue';

// Obtener el composable desde el contexto
const { openAnimation, closeAnimation } = inject('requestAnimation');

const email = ref('');
const password = ref('');
onMounted(async () => {
    await $echoReady
})
const Login = async () => {
    openAnimation('spinner');
    const response1 = await authStore.login(email.value, password.value)
    if(response1.success==true){
        const response = await firstLoad();
        if(response){
            closeAnimation();
            router.push('/');
            swal.showAlert('success','right',{title: 'Bienvenido', text: '',confirmType: 'timer'})
        }
        closeAnimation();
    }else{
        swal.showAlert('error','normal',{title: 'Error', text: 'Credenciales Inválidas',confirmType: 'normal'})
        closeAnimation();
    }
}
const loginWithMicrosoft = async () => {
    openAnimation('spinner');
  const account = await login();
  if (account) {
    // Obtener información del usuario
    const profile = await getUserProfile();
    // console.log("Usuario autenticado:", profile);
    // console.log("Token de acceso:", account.idToken);  
    
        const response = await authStore.authMicrosoft(profile,account.idToken)
        if(response.success == true){
            router.push('/');
            swal.showAlert('success','right',{title: 'Bienvenido', text: '',confirmType: 'timer'})
        }

    
  } else {
    console.error("No se pudo autenticar el usuario.");
  }
  closeAnimation();
};

// // Lógica de inicio de sesión con Microsoft
// const loginWithMicrosoft = async () => {
//   swal.showAlert('info', 'normal', { title: 'Autenticando con Microsoft...' });

//   // Obtenemos el token desde el composable
//   const token = await getAccessToken();
//   if (token) {
//     microsoftToken.value = token;

//     // Aquí puedes usar el token para llamar a tu API o acceder a los recursos de Microsoft
//     swal.showAlert('success', 'right', {
//       title: 'Autenticación exitosa',
//       text: 'Token obtenido correctamente',
//       confirmType: 'timer',
//     });

//     // Simulación de redirección o integración con tu backend
//     router.push('/'); // Redirige a la página principal
//   } else {
//     swal.showAlert('error', 'normal', {
//       title: 'Error',
//       text: 'No se pudo autenticar con Microsoft',
//       confirmType: 'normal',
//     });
//   }
// };

const passwordForget = () => {
    swal.showAlert('info', 'normal', { title: 'Funcion en construccion', text: 'Se esta trabajando en esta funcion, esperala en una version futura', confirmType: 'normal' });
};
</script>

<template >
        <div class="layoutLogin justify-center general_text">
            <div class="container bg-white mx-auto w-96 border_z rounded-md shadow-2xl ">
            <div class="grid grid-rows-1 gap-0 justify-items-center">
                <div class="mt-4 mb-4">
                        <img src="/assets/Imagen1-removebg-preview.png"  width="120" >
                </div>
                <div>
                    <form @submit.prevent="Login">
                        <!-- correo electronico -->
                        <label class="block p-2 m-2"> 
                            <span class="block text-sm font-medium text-slate-700 mb-2">Correo electrónico</span>
                            <div class="flex items-stretch shadow-lg">
                                <div class="grow-0 self-center icon_deco p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            <input type="text" v-model="email" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 input_edit" />
                            </div>
                        </label>

                        <label class="block p-2 m-2">
                            
                            <span class="block text-sm font-medium text-slate-700 mb-2">Contraseña</span>
                            <div class="flex items-stretch shadow-lg">
                                <div class="grow-0 self-center icon_deco p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </div>
                                <input type="password" v-model="password" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 input_edit" />
                            </div>
                        </label>
                        <div class="flex justify-center mb-3 m-1 ">
                            <button type="submit" class="m-3 w-full bg-red-500 hover:bg-green-700 text-white py-2 px-4 rounded">
                                Iniciar sesión
                            </button>                        
                        </div>
                        <div>
                            <div class="grid grid-cols-2 mt-2 mb-2 p-2 m-2 ">
                                <div>
                                    <a @click="loginWithMicrosoft" class="text-sm cursor-pointer">
                                        Inciar Sesión con cuenta Microsoft
                                        <i class="bi bi-microsoft ms-2"></i>
                                    </a>
                                </div>
                                <div class="text-end  content-center	 ">
                                    <a @click="passwordForget" class="text-sm cursor-pointer">¿Olvido su Contraseña?</a>
                                </div>
                            </div>
                        </div>

                </form>
                </div>
            </div>
            </div>
        </div>

</template>
<style>
.layoutLogin {
  display: flex;
  flex-direction: column;
  min-height: 100vh; 
}
.contentLogin {
  flex-grow: 1; 
  overflow-y: auto; 
}


.border_r{
    border: 5px solid red;
}
.border_b{
    border: 5px solid blue;
}
.border_y{
    border: 5px solid yellow;
}
.border_z{
    border: 1px solid #a1a1aa;
}
.icon_deco{
    background-color: rgba(121, 114, 118, 0.5);
    border: 1px solid rgba(78, 75, 75, 0.788);
    border-top-left-radius: 1px;
    border-bottom-left-radius: 1px;
    border-bottom-right-radius: 0px;
}
.input_edit{
    border-top-left-radius: 0px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
    border-top-right-radius: 0px;
}



</style>