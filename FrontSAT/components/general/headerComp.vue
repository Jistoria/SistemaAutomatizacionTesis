<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline';
import { panel } from '~/stores/panel/panel';
import { useRoute } from 'vue-router';
import { auth } from '~/stores/auth/auth';
import { useI18n } from 'vue-i18n';
import { roles } from '#imports';

const { t } = useI18n();


const { openAnimation, closeAnimation } = inject('requestAnimation');
const authStore = auth()
const Logout = async () => {

  openAnimation('spinner'); // Mostrar animación de carga
  await authStore.logout(); // Llamar al logout
  closeAnimation(); // Cerrar animación de carga

  // Redirigir a la página de login
  router.push('/login/loginScreen');
};
const panelStore = panel();
const profileOpen = ref(false);
const asideOpen = ref(false);
const route = useRoute()
const router = useRouter();
function closeProfile() {
  profileOpen.value = false;
}
onMounted(async()=>{
    console.log('entro')
    const response = await panelStore.menus() 
    console.log(panelStore.menus_data)
})
const routeNames ={
  '/panel/list/listDefense': 'routes.listDefense',
  '/panel/list/listManagment': 'routes.listManagment',
  '/panel/list/listCourt': 'routes.listCourt',
  '/panel/list/listStudent': 'routes.listStudent',
  '/panel/list/listTeacher': 'routes.listTeacher'
};
const normalizePath = (path) => {
  const langPrefix = /^\/(en|es|fr)/; 
  return path.replace(langPrefix, '');
};
const currentRouteName = computed(() => {
  const normalizedPath = normalizePath(route.path);
  return t(routeNames[normalizedPath] || 'routes.dashboard');
});

const filteredMenus = computed(() => {
  const userRole = authStore.role[0]; 

  // Opciones de menú permitidas según el rol
  const roleBasedMenu = {
    [roles.rol1]: ['dashboard', 'students', 'teachers', 'courts', 'settings'], // Administrador
    [roles.rol2]: ['courts', 'students'], // Tribunal
    [roles.rol3]: ['students', 'teachers'], // Secretaría
    [roles.rol4]: ['courts', 'students'], 
    [roles.rol5]: ['dashboard', 'settings'], 
  };

  // Obtener las claves de los menús permitidos para el rol actual
  const allowedMenus = roleBasedMenu[userRole] || [];

  // Filtrar los menús dinámicos desde el backend
  return panelStore.menus_data.filter((menu) =>
    allowedMenus.includes(menu.name.toLowerCase().replace(' ', ''))
  );
});
const translatedMenus = computed(() =>
  filteredMenus.value.map((menu) => ({
    ...menu,
    translatedName: t(`menu.${menu.name.toLowerCase().replace(' ', '')}`), // Mapear claves de traducción
  }))
);

</script>
<template>

<div class="navbar bg-primary">
  <div class="drawer grow-0">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex ">
      <label for="my-drawer" class="text-3x1 cursor-pointer">
        <Bars3Icon class="size-7" />
      </label>
      <p class="text-info text-lg ms-4">{{currentRouteName}}</p>
    </div>
    <div class="drawer-side z-10">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <ul class="menu bg-info text-base-content min-h-full w-80 p-4 ">
        <div class="grow ">
          <a class="flex w-full items-center space-x-1  cursor-pointer rounded-md px-2 py-3 hover:bg-gray-100 hover:text-lime-800"
            :class="{'bg-gray-200 text-lime-800': route.path === menus.url,}" 
            v-for="menus in panelStore.menus_data">
            <div >
              <span class="text-2xl pe-3"><i :class="menus.icon"></i></span>
              <NuxtLink :to="menus.url" >
                {{ menus.name }}
              </NuxtLink>
            </div>
          </a>
        </div>
        <div class="mt-8 border-t pt-4">
          <div> 
            <div class="grid grid-cols-3 ">
              <div class="col-span-1" >
                <div>
                  <ColorMode></ColorMode>               
                </div>
              </div>
              <div class="col-span-2" >
                <ChangeLenguaje></ChangeLenguaje> 
              </div>
            </div>
          </div>
          <div>
            <div class="bg-primary rounded hover:bg-zinc-500">
              <div class="dropdown dropdown-bottom dropdown-end ">
                  <div tabindex="0" role="button" class=" m-1">
                    <button @click="Logout" type="button" class="  rounded-box inline-flex justify-center items-center w-full px-4 py-2 text-sm font-medium text-white ">
                        <i class="bi bi-door-closed" style="font-size: 2rem;" ></i><a class="ms-3">Cerrar Sesion</a>
                    </button>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </ul>
    </div>
  </div>
</div>

</template>
<style>


</style>