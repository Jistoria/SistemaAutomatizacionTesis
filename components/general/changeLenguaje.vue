<script setup>
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';

const router = useRouter();
const { setLocale, locale } = useI18n();

const changeLanguage = async (lang) => {
  // Cambiar el idioma usando i18n
  await setLocale(lang);
};

</script>
<template>  

    <button @click="changeLanguage('es')">Español</button>
    <button @click="changeLanguage('en')">English</button>
    
    <!-- <p>{{ $t('car') }}</p>  -->

</template>
<style>

</style>