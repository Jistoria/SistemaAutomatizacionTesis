<script setup>
import { auth } from '~/stores/auth/auth';



const authpinia = auth()
const localePath = useLocalePath()


</script>
<template>
    <h1>{{ authpinia.placeholder }}</h1>

    <ChangeLenguaje></ChangeLenguaje>
    <NuxtLink :to="localePath('/login/loginScreen')">login</NuxtLink>


</template>
<style>

</style>