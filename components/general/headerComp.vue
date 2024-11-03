<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline';
import { panel } from '~/stores/panel/panel';

const panelStore = panel();
const profileOpen = ref(false);
const asideOpen = ref(false);

function closeProfile() {
  profileOpen.value = false;
}
onMounted(async()=>{
    const response = await panelStore.menus() 
    console.log(panelStore.menus)
})
</script>
<template>
<div>
  <header  class="flex w-full bg_Monza items-center justify-between border-b-2 border-gray-200 p-4">
    <div class="flex items-center space-x-3">
        <button type="button" class="text-3x1" @click="asideOpen = !asideOpen">
            <Bars3Icon class="size-7" />
        </button>
        <div>
            <p class="text-white">Dashboard</p>
        </div>
    </div>
  </header>
  <div class="flex">
    <aside v-if="asideOpen" class="flex w-72 absolute  flex-col space-y-2 border-r-4  border-gray-200 bg-white p-2" style="height: 88.2vh" >
      <a class="flex items-center space-x-1 rounded-md px-2 py-3 hover:bg-gray-100 hover:text-lime-800"v-for="menus in panelStore.menus">
          <div>
            <span class="text-2xl pe-3"><i :class="menus.icon"></i></span>
            <NuxtLink :to="menus.url" >
              {{ menus.name }}
            </NuxtLink>
          </div>
      </a>
    </aside>
  </div>
</div>
</template>
<style>


</style>