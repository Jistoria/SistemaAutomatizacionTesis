<script setup>
import { auth } from '~/stores/auth/auth';
import { login } from '~/services/authService';
import { sweetAlert } from '~/composables/sweetAlert';

const authpinia = auth()
const localePath = useLocalePath()
const swal = sweetAlert()

const email = 'admin_tesis@uleam.edu.ec';
const password = 'admin_tesis';

onMounted(() => {
    console.log('mounted')
})
const Login = async () => {
    const response = await login(email, password);
    swal.showAlert('success','right',{title: response, text: '',confirmType: 'timer'})
    console.log(response)
}
const logout = async () => {
    const response = await login(email, password);
    swal.showAlert('success','right',{title: response, text: '',confirmType: 'timer'})
    console.log(response)
}
</script>
<template>
    <h1>{{ authpinia.placeholder }}</h1>

    <ChangeLenguaje></ChangeLenguaje>
    <NuxtLink :to="localePath('/login/loginScreen')">login</NuxtLink>

    <button @click="Login()">Login</button>


</template>
<style>

</style>