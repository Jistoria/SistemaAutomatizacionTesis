<script setup>
import { auth } from '~/stores/auth/auth';
import { login, logout } from '~/services/authService';
import { sweetAlert } from '~/composables/sweetAlert';

const authStore = auth()
const localePath = useLocalePath()
const swal = sweetAlert()

const email = 'admin_tesis@uleam.edu.ec';
const password = 'admin_tesis';

onMounted(() => {
    console.log(authStore.session)
})
const Login = async () => {
    const response = await login(email, password);
    if(response == true){
        swal.showAlert('success','right',{title: 'Sesion Iniciada', text: '',confirmType: 'timer'})
    }else{
        swal.showAlert('error','Normal',{title: 'Error', text: 'Credenciales Invalidas',confirmType: 'normal'})
    }
}
const Logout = async () => {
    const response = await logout();
    if(response == true){
        swal.showAlert('success','right',{title: 'Sesion Cerrada', text: '',confirmType: 'timer'})
    }
    console.log(response)
}

import { preuba } from '~/stores/auth/prueba';
const autprueha = preuba();
const Login_p = async () => {
    console.log('entre al loginP')
    const response = await autprueha.login(email, password);
    console.log(response)
    if(response == true){
        swal.showAlert('success','right',{title: 'Sesion Iniciada', text: '',confirmType: 'timer'})
    }else{
        swal.showAlert('error','Normal',{title: 'Error', text: 'Credenciales Invalidas',confirmType: 'normal'})
    }
}
const logout_p = async () => {
    const response = await autprueha.logout(email, password);
    if(response == true){
        swal.showAlert('success','right',{title: 'Sesion Iniciada', text: '',confirmType: 'timer'})
    }else{
        swal.showAlert('error','Normal',{title: 'Error', text: 'Credenciales Invalidas',confirmType: 'normal'})
    }
}


</script>
<template>
    <button @click="Login_p()">LoginP</button>
    <client-only>
        <button v-if="authStore.session" @click="logout_p()">Logout</button>
    </client-only>





    <h1>{{ authStore.placeholder }}</h1>
    <ChangeLenguaje></ChangeLenguaje>
    <NuxtLink :to="localePath('/login/loginScreen')">login</NuxtLink>
    <button @click="Login()">Login</button>
    <client-only>
        <button v-if="authStore.session" @click="Logout()">Logout</button>
    </client-only>




</template>

<style>

</style>