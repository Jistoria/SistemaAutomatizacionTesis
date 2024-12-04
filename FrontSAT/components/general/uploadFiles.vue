<template>
  <div class="items-center justify-center">
    <!-- Botón para abrir el modal -->
    <button
      @click="openPdfModal"
      class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-full flex items-center space-x-2 shadow-sm border border-gray-300"
    >
      <span>Subir documento PDF</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
        />
      </svg>
    </button>

    <!-- Modal -->
    <div v-if="isPdfModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-10">
      <div class="bg-white w-[500px] p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-2 border-b pb-2">Subir PDF</h2>
        <p class="text-xs text-gray-500 mb-4">Número máximo de archivos: 1</p>

        <!-- Área de carga de archivos (condicional) -->
        <div
          v-if="!selectedPdfFile"
          class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4 cursor-pointer"
          @click="handleClickOnPdfUploadArea"
        >
          <input
            type="file"
            accept="application/pdf"
            @change="handlePdfFileUpload"
            class="hidden"
            ref="pdfFileInput"
          />
          <!-- Icono y texto si no hay archivo seleccionado -->
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-12 w-12 mx-auto text-gray-400 mb-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          <span class="block text-sm text-gray-500">Haz clic para seleccionar un archivo PDF</span>
        </div>

        <!-- Vista de archivo seleccionado -->
        <div v-else class="flex items-center justify-between border-2 border-gray-300 rounded-lg p-4 mb-4">
          <span class="text-sm text-gray-700">Archivo seleccionado: <span class="font-semibold">{{ selectedPdfFile.name }}</span></span>
          <button @click="removeSelectedPdfFile" class="text-gray-500 hover:text-red-500">
            <!-- Icono de X para eliminar el archivo -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <!-- Botones del modal -->
        <div class="flex justify-end space-x-2">
          <button
            @click="uploadPdfData"
            class="bg-primary text-white font-semibold py-2 px-4 rounded hover:bg-primary/50 flex items-center space-x-2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v-4a2 2 0 012-2h2a2 2 0 002-2V6a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 002 2h2a2 2 0 012 2v4m-12 4h8" />
            </svg>
            <span>Subir datos</span>
          </button>
          <button
            @click="closePdfModal"
            class="bg-gray-400 text-white font-semibold py-2 px-4 rounded hover:bg-gray-500 flex items-center space-x-2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>Cancelar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { sweetAlert } from '#imports';
import { auth } from '~/stores/auth/auth';
import { documents } from '~/stores/doc/document';
import { inject } from 'vue';

// Obtener el composable desde el contexto
const { openAnimation, closeAnimation } = inject('requestAnimation');

const swal = sweetAlert();
const authStore = auth();
const docStore = documents();

const isPdfModalOpen = ref(false);
const selectedPdfFile = ref(null);
const pdfFileInput = ref(null);


const openPdfModal = () => {
if (authStore.online) {
  isPdfModalOpen.value = true;
} else {
  swal.showAlert('warning', 'normal', {
    title: 'Atención',
    text: 'Para poder usar esta acción requiere tener conexión',
    confirmType: 'normal',
  });
}
};

const closePdfModal = () => {
isPdfModalOpen.value = false;
selectedPdfFile.value = null;
};

const handleClickOnPdfUploadArea = () => {
if (!selectedPdfFile.value) {
  pdfFileInput.value.click();
  }
};

const handlePdfFileUpload = (event) => {
const file = event.target.files[0];
if (file && file.type === 'application/pdf') {
  selectedPdfFile.value = file;
  console.log('Archivo PDF seleccionado:', file.name);
} else {
  swal.showAlert('error', 'right', {
    title: 'Solo se aceptan archivos de tipo PDF',
    confirmType: 'timer',
  });
  event.target.value = null;
}
};

const removeSelectedPdfFile = () => {
selectedPdfFile.value = null;
};

const uploadPdfData = async () => {
openAnimation('spinner');
if (selectedPdfFile.value) {
  await docStore.sendPdf(authStore.token, authStore.user.id, selectedPdfFile.value);
  closePdfModal();
} else {
  swal.showAlert('error', 'right', {
    title: 'Debe subir un archivo',
    confirmType: 'timer',
  });
}
closeAnimation();
};
</script>