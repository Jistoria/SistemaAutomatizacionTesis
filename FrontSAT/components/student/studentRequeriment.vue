<template>
  <!-- Botón que abre el modal principal -->
  <div class="flex justify-end mt-4">
    <button @click="openModal" class="btn btn-outline btn-primary flex items-center">
      Agregar entrega
    </button>
  </div>

  <!-- Modal Principal -->
  <div v-if="isModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-50">
    <div class="modal-box max-w-3xl p-6 bg-white shadow-lg rounded-lg">
      
      <!-- Header del Modal -->
      <h2 class="font-bold text-lg mb-4 text-gray-800">Requerimientos</h2>

      <!-- Tabla de Contenido del Modal -->
      <table class="table w-full border-collapse">
        <thead class="border-b-2 border-gray-200 text-gray-600">
          <tr>
            <th v-if="visibilityConfig[userRole].name" class="text-left px-4 py-2">Nombre del archivo</th>
            <th v-if="visibilityConfig[userRole].info" class="text-center px-4 py-2">Información</th>
            <th v-if="visibilityConfig[userRole].upload" class="text-center px-4 py-2">Subida</th>
            <th v-if="visibilityConfig[userRole].approve" class="text-center px-4 py-2">Aprobar</th>
            <th v-if="visibilityConfig[userRole].comment" class="text-center px-4 py-2">Comentario</th>
            <th v-if="visibilityConfig[userRole].submit" class="text-center px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(requirement, index) in sampleRequirements"
            :key="index"
            :class="{
              'bg-green-50': requirement.status === 'approved',
              'bg-red-50': requirement.status === 'rejected',
              'bg-gray-50': requirement.status === 'pending'
            }"
          >
            <!-- Nombre del archivo (visible para ambos roles) -->
            <td v-if="visibilityConfig[userRole].name" class="text-gray-700 px-4 py-2">{{ requirement.filename || 'Archivo sin nombre' }}</td>

            <!-- Botón de Información (solo icono) -->
            <td v-if="visibilityConfig[userRole].info" class="text-center">
              <button class="btn btn-sm btn-info px-3 py-1 rounded-full">
                <i class="bi bi-info-circle-fill"></i>
              </button>
            </td>

            <!-- Campo de Subida y Limpiar (solo estudiantes) -->
            <td v-if="visibilityConfig[userRole].upload" class="text-center px-4 py-2">
              <div class="flex items-center justify-center gap-2">
                <label class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded-lg cursor-pointer">
                  <span>Seleccionar Archivo</span>
                  <input 
                    type="file" 
                    accept=".doc" 
                    @change="handleFileUpload($event, index)" 
                    class="hidden"
                  />
                </label>
                <span v-if="requirement.uploadedFile" class="text-sm text-gray-600 truncate max-w-[120px]">
                  {{ requirement.uploadedFile.name }}
                </span>
                <span v-else class="text-sm text-gray-500 italic">Ningún archivo seleccionado</span>
                <button 
                  v-if="requirement.uploadedFile" 
                  @click="clearFile(index)" 
                  class="bg-gray-400 hover:bg-red-500 text-white font-bold py-1 px-2 rounded-full flex items-center justify-center"
                  title="Eliminar archivo"
                >
                  <i class="bi bi-trash-fill text-lg"></i>
                </button>
              </div>
            </td>

            <!-- Checkbox de Aprobación (para Docentes/Revisores) -->
            <td v-if="visibilityConfig[userRole].approve" class="text-center">
              <input type="checkbox" class="checkbox checkbox-success" v-model="requirement.approved" />
            </td>

            <!-- Botón de Comentario (abre modal de comentario) -->
            <td v-if="visibilityConfig[userRole].comment" class="text-center px-4 py-2">
              <button 
                @click="openCommentModal(index)" 
                class="btn btn-sm btn-secondary flex items-center justify-center px-3 py-1"
                title="Agregar comentario"
              >
                <i class="bi bi-chat-left-text-fill"></i>
              </button>
            </td>

            <!-- Botón de Enviar (solo icono) -->
            <td v-if="visibilityConfig[userRole].submit" class="text-center px-4 py-2">
              <button @click="submitRequirement(index)" class="btn btn-sm btn-primary flex items-center justify-center px-3 py-1">
                <i class="bi bi-send-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Acción de Cerrar -->
      <div class="modal-action justify-end mt-4">
        <button @click="closeModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Cerrar</button>
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
import { student } from '~/stores/dashboards/student';

const studentStore = student();
const isModalOpen = ref(false);
const isCommentModalOpen = ref(false);
const selectedRequirementIndex = ref(null);
const userRole = ref("student"); // Cambia entre "student" y "reviewer" para cambiar el rol de usuario

// Configuración de visibilidad basada en rol
const visibilityConfig = {
  student: {
    name: true,
    info: true,
    upload: true,
    approve: false,
    comment: false,
    submit: true
  },
  reviewer: {
    name: true,
    info: false,
    upload: false,
    approve: true,
    comment: true,
    submit: true
  }
};

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const openCommentModal = (index) => {
  selectedRequirementIndex.value = index;
  isCommentModalOpen.value = true;
};

const closeCommentModal = () => {
  isCommentModalOpen.value = false;
  selectedRequirementIndex.value = null;
};

const sampleRequirements = ref([
  { filename: "Archivo aprobado", status: "approved", approved: false, comment: '', uploadedFile: null },
  { filename: "Archivo pendiente", status: "pending", approved: false, comment: '', uploadedFile: null },
  { filename: "Archivo rechazado", status: "rejected", approved: false, comment: '', uploadedFile: null },
]);

const handleFileUpload = (event, index) => {
  const file = event.target.files[0];
  if (file) {
    sampleRequirements.value[index].uploadedFile = file;
  }
};

const clearFile = (index) => {
  sampleRequirements.value[index].uploadedFile = null;
};

const submitRequirement = (index) => {
  console.log(`Enviando requerimiento ${index + 1}`, sampleRequirements.value[index]);
};
</script>

