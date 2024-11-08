<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline';
import { panel } from '~/stores/panel/panel';
import { useRoute } from 'vue-router';
import { auth } from '~/stores/auth/auth';
const authStore = auth()
const Logout = async () => {
    await authStore.logout()
}
const panelStore = panel();
const profileOpen = ref(false);
const asideOpen = ref(false);
const route = useRoute()
function closeProfile() {
  profileOpen.value = false;
}
onMounted(async()=>{
    const response = await panelStore.menus() 
    console.log(panelStore.menus)
})
const routeNames = {
  '/panel/list/listDefense': 'Sustentacion',
  '/panel/list/listManagment': 'Personal',
  '/panel/list/listCourt': 'Tribunales',
  '/panel/list/listStudent': 'Estudiantes',
  '/panel/list/listTeacher': 'Docentes'
}
const currentRouteName = computed(() => routeNames[route.path] || 'Dashboard');

</script>
<template>
<div>
  <header  class="flex w-full bg_Monza items-center justify-between border-b-2 border-gray-200 p-4">
    <div class="flex space-x-3">
        <button type="button" class="text-3x1" @click="asideOpen = !asideOpen">
            <Bars3Icon class="size-7" />
        </button>
        <div>
            <p class="text-white text-2xl">
              {{currentRouteName}}
            </p>
        </div>
    </div>
    <div class="flex justify-end grow">
      <ColorMode></ColorMode>
      <ChangeLenguaje></ChangeLenguaje>
      <div class="btn-primary">
        <div class="dropdown dropdown-bottom dropdown-end">
  
            <div tabindex="0" role="button" class=" m-1">
              <button @click="Logout" type="button" class=" btn-ghost rounded-box inline-flex justify-center items-center w-full px-4 py-2 text-sm font-medium text-white ">
                  <i class="bi bi-door-closed" style="font-size: 2rem;" ></i>
              </button>
            </div>
        </div>
      </div>
    </div>
  </header>
  <div class="flex">
    <aside v-if="asideOpen" class="flex w-72 absolute  flex-col space-y-2 border-r-4  border-gray-200 bg-white p-2" style="height: 86vh" >
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