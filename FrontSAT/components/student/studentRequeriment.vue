<script setup>
import { ref } from 'vue';
import { student } from '~/stores/dashboards/student';
import { auth } from '~/stores/auth/auth';
import { sweetAlert } from '~/composables/sweetAlert';

const studentStore = student();
const authStore = auth();
const swal = sweetAlert();
const isStudentModalOpen = ref(false);
const isReviewerModalOpen = ref(false);
const isCommentModalOpen = ref(false);
const selectedRequirementIndex = ref(null);
const userRole = ref("student"); 


const isInfoModalOpen = ref(false);
const selectedRequirement = ref({ details: "", comment: "" }); 

// Función para abrir el modal de información y cargar datos específicos
const openInfoModal = (index) => {
  const requirement = sampleRequirements.value[index];
  selectedRequirement.value = {
    details: requirement.details || "",
    comment: requirement.comment || ""
  };
  isInfoModalOpen.value = true;
};

// Función para cerrar el modal de información
const closeInfoModal = () => {
  isInfoModalOpen.value = false;
};

const openStudentModal = () => {
  isStudentModalOpen.value = true;
};

const closeStudentModal = () => {
  isStudentModalOpen.value = false;
};

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

const sampleRequirements = ref([
  { 
    filename: "Archivo aprobado", 
    status: "approved", 
    approved: false, 
    comment: 'Buen trabajo. Este archivo cumple con todos los requisitos especificados y tiene un buen nivel de detalle. Sin embargo, es importante verificar algunos puntos adicionales para futuras entregas, especialmente en el formato de presentación y en la estructura de los encabezados.', 
    details: 'Este archivo contiene un análisis profundo sobre los temas especificados en el proyecto. Incluye estadísticas, gráficos y referencias externas que enriquecen la presentación. Asegúrate de revisar el anexo en la página final, ya que contiene información crucial para la comprensión total del contenido.' 
  },
  { 
    filename: "Archivo pendiente", 
    status: "pending", 
    approved: false, 
    comment: 'Se requiere una revisión en la sección de introducción. El lenguaje utilizado en algunas partes es redundante y podría beneficiarse de ser más conciso. Además, es necesario ajustar el diseño visual para mejorar la legibilidad.', 
    details: 'Este archivo es una introducción al proyecto con información general. Faltan algunas secciones requeridas según las instrucciones dadas en el curso. Se recomienda revisar la página de referencias para asegurarse de que todas las fuentes están correctamente citadas.' 
  },
  { 
    filename: "Archivo rechazado", 
    status: "rejected", 
    approved: false, 
    comment: 'Este archivo fue rechazado debido a errores en varias secciones, incluyendo la falta de coherencia en los argumentos y errores tipográficos en todo el documento. Se recomienda rehacer partes significativas y mejorar la estructura de los párrafos.', 
    details: 'El documento no cumple con los lineamientos básicos del proyecto. Las citas no están en el formato correcto, y hay muchas secciones que carecen de profundidad en el análisis. También faltan gráficos explicativos que deberían estar en la sección de resultados para mejor comprensión.' 
  },
]);

const handleFileUpload = (event, index) => {
  const file = event.target.files[0];
  const requiredFilename = sampleRequirements.value[index].filename;

  if (file) {
    if (file.name === requiredFilename) {
      // Si el nombre del archivo coincide con la plantilla, permitimos la subida
      sampleRequirements.value[index].uploadedFile = file;
    } else {
      // Si el nombre no coincide, mostramos un mensaje de error y rechazamos el archivo
      swal.showAlert('error', 'right', {
        title: 'El archivo no coincide, debe llamarse: '+requiredFilename,
        confirmType: 'timer',
      });
      event.target.value = ""; // Resetea el input para permitir nueva selección
    }
  }
};

const clearFile = (index) => {
  sampleRequirements.value[index].uploadedFile = null;
};

const submitRequirement = (index) => {
  console.log(`Enviando requerimiento ${index + 1}`, sampleRequirements.value[index]);
};
</script>
<template>
  <!-- Botones para abrir cada modal según el rol -->
  <div class="flex justify-end mt-4 space-x-4">
    <button v-if="userRole === 'student'" @click="openStudentModal" class="btn btn-outline btn-primary flex items-center">
      Agregar entrega
    </button>
    <button v-if="userRole === 'reviewer'" @click="openReviewerModal" class="btn btn-outline btn-primary flex items-center">
      Revisar Requisitos
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
            v-for="(requirement, index) in sampleRequirements"
            :key="index"
            :class="{
              'bg-green-50': requirement.status === 'approved',
              'bg-red-50': requirement.status === 'rejected',
              'bg-gray-50': requirement.status === 'pending'
            }"
          >
            <!-- Nombre del archivo -->
            <td class="text-gray-700 px-4 py-2">{{ requirement.filename || 'Archivo sin nombre' }}</td>
            <!-- Información -->
            <td class="text-center">
              <button @click="openInfoModal(index)" class="btn btn-sm btn-info px-3 py-1 rounded-full">
                <i class="bi bi-info-circle-fill"></i>
              </button>
            </td>
              <!-- Modal de Información -->
                <div v-if="isInfoModalOpen" class="modal modal-open flex items-center justify-center bg-gray-800 bg-opacity-75 fixed inset-0 z-50">
                  <div class="modal-box max-w-2xl p-6 bg-white shadow-lg rounded-lg">
                    <h2 class="font-bold text-lg mb-4 text-gray-800 text-center">Información del Requisito</h2>
                    
                    <!-- Sección dividida en dos mitades -->
                    <div class="grid grid-cols-2 gap-4">
                      <!-- Detalles del Requisito -->
                      <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Detalles del Requisito</h3>
                        <p v-if="selectedRequirement.details" class="text-gray-600">{{ selectedRequirement.details }}</p>
                        <p v-else class="text-gray-500 italic">No hay detalles para este requisito.</p>
                      </div>
                      
                      <!-- Comentarios -->
                      <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Comentarios</h3>
                        <p v-if="selectedRequirement.comment" class="text-gray-600">{{ selectedRequirement.comment }}</p>
                        <p v-else class="text-gray-500 italic">No hay comentarios para esta tarea.</p>
                      </div>
                    </div>

                    <!-- Acción de Cerrar -->
                    <div class="modal-action justify-end mt-4">
                      <button @click="closeInfoModal" class="btn btn-outline btn-primary px-4 py-2 rounded-lg text-sm">Cerrar</button>
                    </div>
                  </div>
                </div>
            <!-- Subida de archivo -->
            <td class="text-center px-4 py-2">
              <div class="flex items-center justify-center gap-2">
                <label class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded-lg cursor-pointer">
                  <span>Seleccionar Archivo</span>
                  <input 
                    type="file" 
                    accept="file" 
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
            <!-- Botón de Enviar -->
            <td class="text-center px-4 py-2">
              <button @click="submitRequirement(index)" class="btn btn-sm btn-primary flex items-center justify-center px-3 py-1">
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
              'bg-green-50': requirement.status === 'approved',
              'bg-red-50': requirement.status === 'rejected',
              'bg-gray-50': requirement.status === 'pending'
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



