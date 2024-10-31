<script setup>

const { setLocale } = useI18n()
const localePath = useLocalePath()

</script>

<template>
   <button @click="setLocale('es')">espanol </button>
    <button @click="setLocale('en')">ingles </button>
    <p>{{ $t('examples') }}</p>

    <NuxtLink :to="localePath('/')"> hola tuneado</NuxtLink>

</template>
<style>

</style>