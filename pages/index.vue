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
</script>
<template>
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