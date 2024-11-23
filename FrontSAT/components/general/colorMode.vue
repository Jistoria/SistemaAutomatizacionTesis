<script setup>
import { useThemeStore } from '~/stores/themes/theme';
const themeStore = useThemeStore();
const themes =[
  { name: 'UleamTheme',    icon: 'bi bi-pc-display' },
  { name: 'Themegrays',   icon: 'bi bi-upc' },
]
function getIcon(theme) {
  const found = themes.find((item) => item.name === theme);
  return found ? found.icon : 'bi-question-circle';
}
function setTheme(data){
  themeStore.updateTheme(data);
}
</script>
<template>


<div class="dropdown dropdown-top dropdown-end ">
  
  <div tabindex="0" role="button" class="">
    <button type="button" class=" btn-ghost rounded-box bg-primary inline-flex justify-center items-center w-full px-4 py-2 mb-2 text-sm font-medium text-white ">
        <i :class="getIcon(themeStore.currentTheme)" style="font-size: 2rem;" ></i>
    </button>
  </div>

  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] p-3 shadow">
    <a  v-for="theme in themes"  class="mt-2 cursor-pointer rounded-box   hover:bg-slate-100 p-3" >
       <i   @click="setTheme(theme.name)"  :class="theme.icon" style="font-size: 1.5rem;"></i>
    </a>
  </ul>
</div>
</template>
<style>


</style>