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
    console.log('entro')
    const response = await panelStore.menus() 
    console.log(panelStore.menus_data)
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
<div class="navbar bg-primary">
  <div class="drawer  grow-0">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex ">
      <label for="my-drawer" class="text-3x1 cursor-pointer">
        <Bars3Icon class="size-7" />
      </label>
      <p class="text-info text-lg ms-4">{{currentRouteName}}</p>
    </div>
    <div class="drawer-side">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <ul class="menu bg-info text-base-content min-h-full w-80 p-4">
        <a class="flex w-full items-center space-x-1 rounded-md px-2 py-3 hover:bg-gray-100 hover:text-lime-800"
        :class="{'bg-gray-200 text-lime-800': route.path === menus.url,}" 
        v-for="menus in panelStore.menus_data">
        <div >
          <span class="text-2xl pe-3"><i :class="menus.icon"></i></span>
          <NuxtLink :to="menus.url" >
            {{ menus.name }}
          </NuxtLink>
        </div>
        </a>
      </ul>
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
</div>
</template>
<style>


</style>