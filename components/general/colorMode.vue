<script setup>
import { useColorMode } from '#imports';
const colorMode = useColorMode()
const colorModeOpen = ref(false)
function closeProfile() {
    colorModeOpen.value = !colorModeOpen.value;
}
const themes = [
  { name: 'system', icon: 'bi-cloud-moon' },
  { name: 'light', icon: 'bi-sun-fill' },
  { name: 'dark', icon: 'bi-moon-stars-fill' },
  { name: 'sepia', icon: 'bi-cup-hot-fill' }
];

</script>
<template>
        <div class="relative group"  >
            <div class="bg_Boulder rounded-full opacity-0 group-hover:opacity-100 group-hover:visible transition duration-300" >
                    <div class="py-1">
                        <a v-for="theme in themes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-full"
                            @click="colorMode.preference = theme.name"
                        >
                            <i :class="theme.icon"></i>
                        </a>
                    </div>
            </div>
            <div  class="group">
                <button type="button" class="rounded-full inline-flex justify-center items-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:bg-gray-700">
                    <i class="bi bi-cloud-moon"></i>
                </button>
            </div>
        </div>

</template>
<style>


</style>