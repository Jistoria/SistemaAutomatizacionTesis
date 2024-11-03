<script setup>
import { auth } from '~/stores/auth/auth';

const { $echoReady } = useNuxtApp();
const authStore = auth()
const localePath = useLocalePath()
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();

onMounted(async () => {
    await $echoReady
})
const Logout = async () => {
    await authStore.logout()
}

</script>
<template>
    <ChangeLenguaje></ChangeLenguaje>
    <client-only>
        <button v-if="authStore.session" @click="Logout()">Logout</button>
    </client-only>

    <UploadFiles></UploadFiles>

</template>

<style>

</style>