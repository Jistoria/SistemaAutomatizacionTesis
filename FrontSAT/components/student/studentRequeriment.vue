<script setup>
import { ref } from 'vue';
import { student } from '~/stores/dashboards/student';
import { auth } from '~/stores/auth/auth';
import { sweetAlert } from '~/composables/sweetAlert';
const { openAnimation, closeAnimation } = inject('requestAnimation');

const studentStore = student();
const authStore = auth();
const swal = sweetAlert();
const isStudentModalOpen = ref(false);
const isInfoModalOpen = ref(false);
const selectedRequirement = ref({ details: "", observations: "" });
const selectedFiles = ref({}); // Almacena archivos temporalmente por índice

// Abre el modal de información y carga datos específicos
const openInfoModal = (index) => {
  console.log(studentStore.requeriments)
  const requirement = studentStore.requeriments[index];
  selectedRequirement.value = {
    details: requirement?.description || "No hay detalles para este requisito.",
    observations: requirement?.observations || "No hay comentarios para esta tarea.",
  };
  isInfoModalOpen.value = true;
};

// Cierra el modal de información
const closeInfoModal = () => {
  isInfoModalOpen.value = false;
};

// Abre y cierra el modal de estudiantes
const openStudentModal = () => isStudentModalOpen.value = true;
const closeStudentModal = () => isStudentModalOpen.value = false;

// Maneja la selección de archivo y validación del nombre
const handleFileUpload = (event, fileName,index) => {
  const file = event.target.files[0];
  if (file) {
    if (file.name === fileName) {
      selectedFiles.value[index] = file; // Almacena el archivo temporalmente
    } else {
      swal.showAlert('error', 'right', {
        title: `El archivo no coincide, debe llamarse: ${fileName}`,
        confirmType: 'timer',
      });
      event.target.value = ""; // Resetea el input
    }
  }
};

// Limpia el archivo seleccionado para un índice específico
const clearFile = (index) => {
  delete selectedFiles.value[index];
  const fileInput = document.querySelector(`#file-input-${index}`);
  if (fileInput) fileInput.value = ""; // Resetea el valor del input
};

// Prepara el archivo para envío (sin hacer la petición)
const submitRequirement = async (index) => {
  if (authStore.online == true ){
    const file = selectedFiles.value[index];
  if (file) {
    const formData = new FormData();
    formData.append("file", file);
    formData.append("requirementStudentId", studentStore.requeriments[index].student_requirements_id);
    console.log("FormData contents:");
    for (let [key, value] of formData.entries()) {
      console.log(`${key}: ${value.name || value}`);
    }
    openAnimation("spinner");
      await studentStore.sendRequeriments(authStore.token, formData);
    closeAnimation();

    
  } else {
    swal.showAlert("error", "right", {
      title: "No se ha seleccionado ningún archivo para este requerimiento",
      confirmType: "timer",
    });
  }
  }else {
      swal.showAlert('warning', 'normal', {
      title: 'Atención',
      text: 'Para poder usar esta acción requiere tener conexión',
      confirmType: 'normal',
  });
  }
};
function getFullFileName(name, extension) {
  const fileName = name || 'Archivo sin nombre';
  let fileExtension = extension || '';

  // Remover comillas de la extensión, si existen
  fileExtension = fileExtension.replace(/['"]+/g, '');

  // Concatenar con un punto solo si hay una extensión válida
  return fileExtension ? `${fileName}.${fileExtension}` : fileName;
}


</script>
<template>
  <div class="flex justify-end mt-4 space-x-4">
    <button @click="openStudentModal" class="btn btn-outline btn-primary flex items-center">
      Agregar entrega
    </button>
  </div>

  <!-- Modal para Estudiantes -->
  <div v-if="isStudentModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-50">
    <div class="modal-box max-w-3xl p-6 bg-white shadow-lg rounded-lg">
      <h2 class="font-bold text-lg mb-4 text-gray-800">Requerimientos del Estudiante</h2>
      <table class="table w-full border-collapse">
        <thead class="border-b-2 border-gray-200 text-gray-600">
          <tr>
            <th class="text-left px-4 py-2">Nombre del archivo</th>
            <th class="text-center px-4 py-2">Información</th>
            <th class="text-center px-4 py-2">Subida</th>
            <th class="text-center px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(requirement, index) in studentStore.requeriments"
            :key="index"
            :class="{
              'bg-green-50': requirement.status === 'Aprobado',
              'bg-red-50': requirement.status === 'Rechazado',
              'bg-gray-50': requirement.status === 'Enviado'
            }"
          >
            <!-- Nombre del archivo -->
            <td class="text-gray-700 px-4 py-2">
              <span>{{ getFullFileName(requirement.name,requirement.extension) || 'Archivo sin nombre' }}</span>
            </td>

            <!-- Información -->
            <td class="text-center">
              <button @click="openInfoModal(index)" class="btn btn-sm btn-info px-3 py-1 rounded-full">
                <i class="bi bi-info-circle-fill"></i>
              </button>
            </td>
              
            <!-- Subida de archivo -->
            <td class="text-center px-4 py-2">
              <div class="flex items-center justify-center gap-2">
                <label 
                  v-if="requirement.status !== 'Enviado'&&requirement.status !== 'Aprobado'"
                  class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded-lg cursor-pointer"
                >
                  <span>Seleccionar Archivo</span>
                  <input 
                    type="file" 
                    accept="file" 
                    @change="handleFileUpload($event, getFullFileName(requirement.name,requirement.extension),index)" 
                    :id="`file-input-${index}`" 
                    class="hidden"
                  />
                </label>
                <span v-if="requirement.status === 'Enviado'" class="text-gray-500 italic">Archivo enviado para su revision</span>
                <span v-else-if="requirement.status === 'Aprobado'" class="text-gray-500 italic">Archivo Aprobado</span>
                <span v-else-if="selectedFiles[index]" class="text-sm text-gray-600 truncate max-w-[120px]">
                  {{ selectedFiles[index].name }}
                </span>
                <span v-else-if="requirement.status === 'Rechazado'" class="text-sm text-gray-500 italic">Archivo rechazado, corrija y vuelva a subir</span>
                <span v-else class="text-sm text-gray-500 italic">Ningún archivo seleccionado</span>

                <button 
                  v-if="selectedFiles[index] && requirement.status !== 'Enviado'" 
                  @click="clearFile(index)" 
                  class="bg-gray-400 hover:bg-red-500 text-white font-bold py-1 px-2 rounded-full flex items-center justify-center"
                  title="Eliminar archivo"
                >
                  <i class="bi bi-trash-fill text-lg"></i>
                </button>
              </div>
            </td>

            <!-- Botón de Enviar -->
            <td class="text-center px-4 py-2">
              <button 
                @click="submitRequirement(index)" 
                class="btn btn-sm btn-primary flex items-center justify-center px-3 py-1"
                :disabled="requirement.status === 'Enviado' || requirement.status === 'Aprobado'"
              >
                <i class="bi bi-send-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="modal-action justify-end mt-4">
        <button @click="closeStudentModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Cerrar</button>
      </div>
    </div>
  </div>

  <!-- Modal de Información -->
<div v-if="isInfoModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-100">
  <div class="modal-box max-w-2xl p-6 bg-white shadow-lg rounded-lg">
    <h2 class="font-bold text-lg mb-4 text-gray-800 text-center">Información del Requisito</h2>
    <div class="grid grid-cols-2 gap-4">
      <!-- Detalles del Requisito -->
      <div class="flex flex-col">
        <h3 class="font-semibold text-gray-700 mb-2">Detalles del Requisito</h3>
        <!-- Hacemos scroll en los detalles -->
        <div class="overflow-y-auto max-h-64">
          <p class="text-gray-600" v-if="selectedRequirement.details">
            {{ selectedRequirement.details }}
          </p>
          <p v-else class="text-gray-500 italic">No hay detalles para este requisito.</p>
        </div>
      </div>
      <!-- Comentarios -->
      <div class="flex flex-col">
        <h3 class="font-semibold text-gray-700 mb-4">Observaciones</h3>
        <!-- Contenedor con scroll -->
        <div class="overflow-y-auto max-h-64 space-y-3">
          <!-- Lista de comentarios -->
          <ul v-if="selectedRequirement.observations && selectedRequirement.observations.length" class="space-y-3">
            <li
              v-for="(observation, index) in selectedRequirement.observations"
              :key="observation.observation_requirement_id || index"
              class="border-b border-gray-200 pb-2 last:border-b-0"
            >
              <p class="text-sm text-gray-800">
                <span v-html="observation.comment"></span>
              </p>
              <p class="text-gray-500 text-xs mt-1">
                Fecha: {{ new Date(observation.created_at).toLocaleDateString() }}
              </p>
            </li>
          </ul>
          <p v-else class="text-gray-500 italic">No hay comentarios para esta tarea.</p>
        </div>
      </div>
      
    </div>
    <div class="modal-action justify-end mt-4">
      <button @click="closeInfoModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Cerrar</button>
    </div>
  </div>
</div>

</template>





