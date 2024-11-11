<script setup>
import { onMounted, ref, watch } from 'vue';
import { student } from '~/stores/dashboards/student';
import { auth } from '~/stores/auth/auth';

const authStore = auth();
const studentStore = student();
const loading = ref(true); // Initialize loading state

// Watch to animate percentage change
const animatedPercentages = ref({});

onMounted(async () => {
  await studentStore.getDataStatus(authStore.token);

  // Initialize animated percentages for each phase after data loads
  studentStore.dashboard_status.forEach((module, moduleIndex) => {
    module.phases.forEach((phase, phaseIndex) => {
      animatedPercentages.value[moduleIndex + '-' + phaseIndex] = 0;
    });
  });

  // Animate each percentage individually after a delay
  setTimeout(() => animatePercentages(), 300);

  loading.value = false; // Set loading to false once data is loaded
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
  <div class="container mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-center text-primary">Estado del Estudiante</h2>
    
    <div v-if="loading" class="flex justify-center items-center py-8">
      <!-- Loading Spinner from DaisyUI -->
      <span class="loading loading-spinner loading-lg text-primary"></span>
    </div>
    
    <div v-else class="flex space-x-6 overflow-x-auto py-4 justify-center">
      <div
        v-for="(module, moduleIndex) in studentStore.dashboard_status"
        :key="moduleIndex"
        class="card bg-base-100 p-8 rounded-xl shadow-lg border border-gray-200 flex flex-col items-center min-w-[280px] space-y-6"
      >
        <!-- Module Header with Title -->
        <h3 class="text-xl font-semibold text-secondary">{{ module.module_name }}</h3>
        
        <!-- Container for Phases, centered if there's only one -->
        <div class="flex flex-col items-center justify-center flex-1 space-y-4">
          <div
            v-for="(phase, phaseIndex) in module.phases"
            :key="phaseIndex"
            class="flex items-center space-x-4 mb-4"
          >
            <!-- Animated Circular Progress -->
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
              <div class="absolute inset-0 flex items-center justify-center text-xl font-bold text-primary">
                {{ animatedPercentages[moduleIndex + '-' + phaseIndex] }}%
              </div>
            </div>
            <div class="flex flex-col items-start">
              <!-- Phase Name and Status Badge -->
              <p class="text-sm font-semibold">{{ phase.phase_name || 'Sin Fase' }}</p>
              <span
                class="badge"
                :class="{
                  'badge-success': phase.state_now === 'Aprobado',
                  'badge-warning': phase.state_now === 'En proceso',
                  'badge-error': phase.state_now === 'No habilitado',
                }"
              >
                {{ phase.state_now }}
              </span>

              <!-- Display Dates if Available -->
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

