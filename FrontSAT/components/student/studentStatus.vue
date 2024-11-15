<script setup>
import { onMounted, ref, watch } from 'vue';
import { student } from '~/stores/dashboards/student';
import { auth } from '~/stores/auth/auth';

const authStore = auth();
const studentStore = student();
const loading = ref(true);
const animatedPercentages = ref({});

onMounted(async () => {
  

  studentStore.dashboard_status.forEach((module, moduleIndex) => {
    module.phases.forEach((phase, phaseIndex) => {
      animatedPercentages.value[moduleIndex + '-' + phaseIndex] = 0;
    });
  });

  setTimeout(() => animatePercentages(), 300);
  loading.value = false;
});

function animatePercentages() {
  Object.keys(animatedPercentages.value).forEach((key) => {
    const [moduleIndex, phaseIndex] = key.split('-');
    const targetPercentage = studentStore.dashboard_status[moduleIndex].phases[phaseIndex].progress || 0;
    let currentPercentage = 0;
    const interval = setInterval(() => {
      if (currentPercentage >= targetPercentage) {
        clearInterval(interval);
      } else {
        currentPercentage += 1;
        animatedPercentages.value[moduleIndex + '-' + phaseIndex] = currentPercentage;
      }
    }, 20);
  });
}

function formatDate(date) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString('es-ES', options);
}
</script>

<template>
  <div class="container mx-auto px-6 py-8 space-y-8">
    <div v-if="loading" class="flex justify-center items-center py-8">
      <span class="loading loading-spinner loading-lg text-primary"></span>
    </div>

    <!-- Cambié justify-center a justify-start y agregué pl-4 para asegurar la visibilidad del primer módulo -->
    <div v-else class="flex space-x-6 overflow-x-auto py-4 justify-start pl-4">
      <div
        v-for="(module, moduleIndex) in studentStore.dashboard_status"
        :key="moduleIndex"
        class="card bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex flex-col items-center min-w-[280px] space-y-6"
      >
        <h3 class="text-xl font-semibold text-gray-700">{{ module.module_name }}</h3>

        <div class="flex flex-col items-center justify-center flex-1 space-y-4">
          <div
            v-for="(phase, phaseIndex) in module.phases"
            :key="phaseIndex"
            class="flex items-center space-x-4 mb-4"
          >
            <div class="relative w-20 h-20">
              <svg class="w-full h-full transform -rotate-90">
                <circle
                  class="text-gray-300"
                  stroke-width="4"
                  stroke="currentColor"
                  fill="transparent"
                  r="32"
                  cx="40"
                  cy="40"
                />
                <circle
                  class="text-primary"
                  stroke-width="4"
                  stroke="currentColor"
                  :stroke-dasharray="201.06"
                  :stroke-dashoffset="(100 - animatedPercentages[moduleIndex + '-' + phaseIndex]) * 2.01"
                  fill="transparent"
                  r="32"
                  cx="40"
                  cy="40"
                />
              </svg>
              <div class="absolute inset-0 flex items-center justify-center text-lg font-bold text-primary">
                {{ animatedPercentages[moduleIndex + '-' + phaseIndex] }}%
              </div>
            </div>
            <div class="flex flex-col items-start">
              <p class="text-sm font-semibold text-gray-700">{{ phase.phase_name || 'Sin Fase' }}</p>
              <span
                class="px-2 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-700': phase.state_now === 'Aprobado',
                  'bg-yellow-100 text-yellow-700': phase.state_now === 'En proceso',
                  'bg-red-100 text-red-700': phase.state_now === 'No habilitado',
                }"
              >
                {{ phase.state_now }}
              </span>

              <div class="text-xs text-gray-500 mt-1">
                <p v-if="phase.date_start">Inicio: {{ formatDate(phase.date_start) }}</p>
                <p v-if="phase.date_end">Fin: {{ formatDate(phase.date_end) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<style scoped>
.container {
  max-width: 1200px;
}

.card {
  min-width: 280px;
}
</style>
