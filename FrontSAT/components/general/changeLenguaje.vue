<script setup>
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';

const lengaunge_selected = ref('espanol');
const lenguaje = ref({
  spanish:{
    name:'espanol',
    value:'es'
  },
  english:{
    name:'ingles',
    value:'en'
  }
})
const router = useRouter();
const { setLocale, locale } = useI18n();

const changeLanguage = async (lang) => {
  lengaunge_selected.value = lang.name;
  // Cambiar el idioma usando i18n
  await setLocale(lang.value);
};

</script>
<template>  
    <div class="dropdown dropdown-right dropdown-end">
      <div tabindex="0" role="button"  >
        <div class="bg-primary p-3 rounded-box inline-block">
          <i class="bi bi-translate" style="font-size: 1.5rem; color: azure;"></i> 
        </div>
        <div class="inline-block ms-3">
          <div class="italic text-xl">
            <a>{{ lengaunge_selected }}</a>
          </div>
        </div>
      </div>

      <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow ms-2">
        <li v-for="leng in lenguaje">
          <a @click="changeLanguage(leng)">
            {{ leng.name }}
          </a>
        </li>
      </ul>
    </div>

</template>
<style>

</style>