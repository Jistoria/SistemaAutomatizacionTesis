<!-- ReviewerModal.vue -->
<template>
    <!-- Botones para abrir cada modal según el rol -->
    <div class="flex justify-end mt-4 space-x-4">
      <button @click="openReviewerModal" class="btn btn-outline btn-primary flex items-center">
        Revisar Requisitos
      </button>
    </div>
       <!-- Modal para Revisores -->
   <div v-if="isReviewerModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-50">
    <div class="modal-box max-w-3xl p-6 bg-white shadow-lg rounded-lg">
      <h2 class="font-bold text-lg mb-4 text-gray-800">Revisión de Requisitos</h2>
      <table class="table w-full border-collapse">
        <thead class="border-b-2 border-gray-200 text-gray-600">
          <tr>
            <th class="text-left px-4 py-2">Nombre del archivo</th>
            <th class="text-center px-4 py-2">Aprobar</th>
            <th class="text-center px-4 py-2">Comentario</th>
            <th class="text-center px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(requirement, index) in sampleRequirements"
            :key="index"
            :class="{
              'bg-green-50': requirement.status === 'Aprobado',
              'bg-red-50': requirement.status === 'Rechazado',
              'bg-gray-50': requirement.status === 'Pendiente'
            }"
          >
            <!-- Nombre del archivo -->
            <td class="text-gray-700 px-4 py-2">{{ requirement.filename || 'Archivo sin nombre' }}</td>

            <!-- Checkbox de Aprobación -->
            <td class="text-center px-4 py-2">
              <div class="flex justify-center items-center">
                <input type="checkbox" class="checkbox checkbox-success" v-model="requirement.approved" />
              </div>
            </td>

            <!-- Botón de Comentario -->
            <td class="text-center px-4 py-2">
              <div class="flex justify-center items-center">
                <button 
                  @click="openCommentModal(index)" 
                  class="btn btn-circle bg-gray-500 hover:bg-gray-600 text-white flex items-center justify-center"
                  title="Agregar comentario"
                >
                  <i class="bi bi-chat-left-text-fill text-lg"></i>
                </button>
              </div>
            </td>

            <!-- Botón de Enviar -->
            <td class="text-center px-4 py-2">
              <div class="flex justify-center items-center">
                <button @click="submitRequirement(index)" class="btn btn-circle bg-red-500 hover:bg-red-600 text-white flex items-center justify-center">
                  <i class="bi bi-send-fill text-lg"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="modal-action justify-end mt-4">
        <button @click="closeReviewerModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Cerrar</button>
      </div>
    </div>
  </div>
    <!-- Modal de Comentario -->
    <div v-if="isCommentModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-50">
      <div class="modal-box max-w-md p-4 bg-white shadow-lg rounded-lg">
        <h2 class="font-bold text-lg mb-4 text-gray-800">Comentario</h2>
        <textarea 
          v-model="sampleRequirements[selectedRequirementIndex].comment" 
          placeholder="Escribe tu comentario aquí..." 
          class="textarea textarea-bordered w-full h-24 p-2"
        ></textarea>
        <div class="modal-action justify-end mt-4">
          <button @click="closeCommentModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Guardar</button>
        </div>
      </div>
    </div>
</template>
  
  <script setup>
  import { ref } from 'vue';

  const isReviewerModalOpen = ref(false);
  const isCommentModalOpen = ref(false);

    const openReviewerModal = () => {
    isReviewerModalOpen.value = true;
  };

  const closeReviewerModal = () => {
    isReviewerModalOpen.value = false;
  };

  const openCommentModal = (index) => {
    selectedRequirementIndex.value = index;
    isCommentModalOpen.value = true;
  };

  const closeCommentModal = () => {
    isCommentModalOpen.value = false;
    selectedRequirementIndex.value = null;
  };
  </script>
  