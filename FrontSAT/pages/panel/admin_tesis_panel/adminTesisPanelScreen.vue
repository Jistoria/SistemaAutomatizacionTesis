<script setup>
import { auth } from '~/stores/auth/auth';
const authStore = auth();
import studentAdmin from '~/components/admin/studentAdmin.vue';
import docenteAdmin from '~/components/admin/docenteAdmin.vue';
import procesosAdmin from '~/components/admin/procesosAdmin.vue';

const totalData = ref({ approved: 0, inProcess: 0 });

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
const handleUpdateTotal = (totals) => {
  totalData.value = totals || { approved: 0, inProcess: 0 };
};

const totalStudents = computed(() => totalData.value.approved + totalData.value.inProcess);
const approvedPercentage = computed(() => {
  return totalStudents.value ? (totalData.value.approved / totalStudents.value) * 100 : 0;
});
const inProcessPercentage = computed(() => {
  return totalStudents.value ? (totalData.value.inProcess / totalStudents.value) * 100 : 0;
});
</script>
<template>
    <div>

    </div>
    <div class="container mx-auto mt-4">
        <div class="gap-4 p-3">
            <div>
                <h2 class="text-2xl font-bold text-center sm:text-left">Bienvenido, {{formatUserName(authStore.user.name)}}</h2>
            </div>
            <div class="container mx-auto">
                <div class="grid grid-cols-8">
                    <div class="col-span-2 content-center">
                        <h1 class="text-xl font-bold mb-4">Resumen de Progreso:</h1>
                        <div class="text-center">
                            <div
                            class="radial-progress bg-green-100 text-green-500 border-green-500 border-1"
                            :style="{ '--value': approvedPercentage.toFixed(0) }"
                            role="progressbar"
                            >
                            {{ approvedPercentage.toFixed(0) }}%
                            </div>
                            <p class="mt-2 font-semibold text-green-500">Aprobados</p>
                        </div>
                        <div class="text-center">
                        <div
                            class="radial-progress bg-yellow-100 text-yellow-500 "
                            :style="{ '--value': inProcessPercentage.toFixed(0) }"
                            role="progressbar"
                            >
                            {{ inProcessPercentage.toFixed(0) }}%
                            </div>
                            <p class="mt-2 font-semibold text-yellow-500">En Proceso</p>
                        </div>
                    </div>
                    <div class="col-span-6 ">
                        <DataDashboard data="Estudiantes" @update-total="handleUpdateTotal" ></DataDashboard>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<style>
</style>