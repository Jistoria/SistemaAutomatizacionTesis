<script setup>
import { Modal } from 'flowbite';

import { useColorMode } from '#imports';
const colorMode = useColorMode()
const colorModeOpen = ref(false)
function closeProfile() {
    colorModeOpen.value = !colorModeOpen.value;
}
const themes = [
  { name: 'system', icon: 'bi-cloud-moon' },
  { name: 'dark', icon: 'bi-moon-stars-fill' },
  { name: 'sepia', icon: 'bi-cup-hot-fill' }
];
function getIcon(theme) {
  const found = themes.find((item) => item.name === theme);
  return found ? found.icon : 'bi-question-circle';
}
</script>
<template>
<div class="dropdown dropdown-bottom dropdown-end">
  
  <div tabindex="0" role="button" class=" m-1">
    <button type="button" class=" btn-ghost rounded-box inline-flex justify-center items-center w-full px-4 py-2 text-sm font-medium text-white ">
        <i :class="getIcon(colorMode.preference)" style="font-size: 2rem;" ></i>
    </button>
  </div>

  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] p-3 shadow">
    <a  v-for="theme in themes"  class="mt-2 cursor-pointer rounded-box   hover:bg-slate-100 p-3"   @click="colorMode.preference = theme.name">
       <i   :class="theme.icon" style="font-size: 1.5rem;"></i>
    </a>
  </ul>
</div>

</template>
<style>


</style>