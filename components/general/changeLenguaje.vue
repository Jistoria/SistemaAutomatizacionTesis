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
    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn-ghost p-3 rounded-box">
        <i class="bi bi-translate" style="font-size: 1.5rem; color: azure;"></i>
      </div>
      <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-auto p-2 shadow">
        <li><a>Español</a></li>
        <li><a>Ingles</a></li>
      </ul>
    </div>

</template>
<style>

</style>