<script setup>
import { ref } from 'vue';
import { auth } from '~/stores/auth/auth';
import studentStatus from '~/components/student/studentStatus.vue';
import studentRequeriment from '~/components/student/studentRequeriment.vue';
import studentObservation from '~/components/student/studentObservation.vue';
import { student } from '~/stores/dashboards/student';
const { openAnimation, closeAnimation } = inject('requestAnimation');
const authStore = auth();
const studentStore = student();

// Reactive variable for the details modal
const isDetailsModalOpen = ref(false);

// Functions to control the details modal
const openDetailsModal = async () => {
  isDetailsModalOpen.value = true;
};
const closeDetailsModal = () => (isDetailsModalOpen.value = false);
const formatUserName = (name) => {
  // Convierte el nombre a minúsculas y luego capitaliza la primera letra de cada palabra
  return name
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

</script>

<template>
  <div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
      <h2 class="text-2xl font-bold text-center">Bienvenido, {{formatUserName(authStore.user.name)}}</h2>
      <button class="btn btn-outline btn-primary flex items-center">
        <i class="bi bi-envelope mr-2"></i> Enviar Solicitud
      </button>
    </header>

    <!-- Status Section -->
    <section class="mb-8 w-full">
      <h2 class="text-2xl font-bold text-center text-primary">Estado del Proyecto: NOMBRE DEL PROYECTO</h2>
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
    </section>
    <!-- Details Modal -->
    <div v-if="isDetailsModalOpen" class="modal modal-open">
      <div class="modal-box max-h-[70vh] overflow-y-auto p-6 bg-white shadow-lg rounded-lg">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Detalles de Recursos</h3>
        <div v-if="studentStore.requeriments && studentStore.requeriments.length">
          <div v-for="(requirement, index) in studentStore.requeriments" :key="requirement.student_requirements_id" class="mb-6 p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-700"><strong class="text-gray-800">Tutor a cargo:</strong> {{ requirement.approved_role || 'No especificado' }}</p>
            <p class="text-gray-700"><strong class="text-gray-800">Tema:</strong> {{ requirement.name || 'No especificado' }}</p>
            <p class="text-gray-700"><strong class="text-gray-800">Fecha de fin:</strong> {{ requirement.send_date || 'No especificado' }}</p>
            <p class="text-gray-700"><strong class="text-gray-800">Requisitos de la actividad:</strong> {{ requirement.description || 'No especificado' }}</p>
            <hr class="my-4 border-t border-gray-200"/>
          </div>
        </div>
        <div v-else class="text-center text-gray-500">
          Cargando...
        </div>
        <div class="modal-action justify-end mt-4">
          <button @click="closeDetailsModal" class="btn btn-outline btn-primary">Cerrar</button>
        </div>
      </div>
    </div>
    
    

    <!-- Requirements Section -->
    <section class="mb-8">
      <h2 class="text-lg sm:text-xl font-semibold mb-4">Requerimientos</h2>
      <div class="bg-gray-100 p-4 rounded-lg flex justify-between items-center">
        <p class="text-gray-700 text-sm flex items-center">
          ⚠️ Para pasar a la siguiente fase, es necesario completar todos los requerimientos.
        </p>
        <studentRequeriment />
      </div>
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
