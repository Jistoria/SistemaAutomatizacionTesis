<script setup>
import { ref } from 'vue';
import { auth } from '~/stores/auth/auth';
import studentStatus from '~/components/student/studentStatus.vue';
import studentRequeriment from '~/components/student/studentRequeriment.vue';
import studentObservation from '~/components/student/studentObservation.vue';

const authStore = auth();

// Reactive variable for the details modal
const isDetailsModalOpen = ref(false);

// Functions to control the details modal
const openDetailsModal = () => (isDetailsModalOpen.value = true);
const closeDetailsModal = () => (isDetailsModalOpen.value = false);


</script>

<template>
  <div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
      <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-0">
        ¡Bienvenido, {{ authStore.user.name }}!
      </h1>
      <button class="btn btn-outline btn-primary flex items-center">
        <i class="bi bi-envelope mr-2"></i> Enviar Solicitud
      </button>
    </header>

    <!-- Status Section -->
    <section class="mb-8 w-full">
        <div class="flex justify-center">
        <!-- Setting a max-width and centering the studentStatus component container -->
        <div class="w-full max-w-5xl">
            <studentStatus />
        </div>
        </div>
    </section>
  
      

    <!-- Resource Section -->
    <section class="mb-8">
      <h2 class="text-lg sm:text-xl font-semibold mb-4">Recurso</h2>
      <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <a href="#" class="text-blue-600 underline text-lg flex items-center">
          <i class="bi bi-file-earmark-arrow-down mr-2"></i> Descargar Plantillas
        </a>
        <button @click="openDetailsModal" class="btn btn-outline btn-primary flex items-center">
          <i class="bi bi-eye mr-2"></i> Ver detalles
        </button>
      </div>
      <!-- Modal for Details -->
      <div v-if="isDetailsModalOpen" class="modal modal-open">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Detalles de Recursos</h3>
          <p>Contenido detallado del recurso...</p>
          <div class="modal-action">
            <button @click="closeDetailsModal" class="btn">Cerrar</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Requirements Section -->
    <section class="mb-8">
      <h2 class="text-lg sm:text-xl font-semibold mb-4">Requerimientos</h2>
      <div class="bg-gray-200 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
        <p class="text-gray-700 text-center sm:text-left">
          ⚠️ Para pasar a la siguiente fase, es necesario completar todos los requerimientos.
        </p>
      </div>
      <studentRequeriment />
    </section>

    <!-- Observations Section -->
    <section>
      <h2 class="text-lg sm:text-xl font-semibold mb-4">Observaciones</h2>
      <div class="bg-gray-200 p-4 rounded-lg flex justify-between items-center">
        <studentObservation />
      </div>
    </section>
  </div>
</template>
