<script setup>
import { auth } from '~/stores/auth/auth';
const authStore = auth();
import studentAdmin from '~/components/admin/studentAdmin.vue';
import docenteAdmin from '~/components/admin/docenteAdmin.vue';
import procesosAdmin from '~/components/admin/procesosAdmin.vue';


const formatUserName = (name) => {
  if (!name || typeof name !== 'string') {
    return ''; // Retorna un string vacío si el nombre no es válido
  }
  return name
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};
</script>
<template>
    <div class="container mx-auto mt-4">
        <div class="gap-4 p-3">
            <div>
                <h2 class="text-2xl font-bold text-center sm:text-left">Bienvenido, {{formatUserName(authStore.user.name)}}</h2>
            </div>
            <div class="border_blue container mx-auto">
                <div class="grid grid-cols-8">

                    <div class="col-span-2 border_y">

                    </div>
                    <div class="col-span-6 border_blue">
                        <DataDashboard data="Estudiantes"></DataDashboard>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<style>
</style>