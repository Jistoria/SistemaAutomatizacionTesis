<script setup>
import { auth } from '~/stores/auth/auth';
import { sweetAlert } from '~/composables/sweetAlert';

const authStore = auth()
const localePath = useLocalePath()
const swal = sweetAlert()

const email = 'admin_tesis@uleam.edu.ec';
const password = 'admin_tesis';

onMounted(() => {
    console.log(authStore.session)
    console.log(authStore.user)
    console.log(authStore.token)
    console.log(authStore.role)
})
const Login = async () => {
    await authStore.login(email, password)
}
const Logout = async () => {
    await authStore.logout()
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