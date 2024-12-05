<script setup>
import { ref } from 'vue';
import { auth } from '~/stores/auth/auth';
import studentStatus from '~/components/student/studentStatus.vue';
import studentRequeriment from '~/components/student/studentRequeriment.vue';
import { documents } from '~/stores/doc/document';
import studentObservation from '~/components/student/studentObservation.vue';
import { student } from '~/stores/dashboards/student';
const { openAnimation, closeAnimation } = inject('requestAnimation');



const authStore = auth();
const studentStore = student();
const docStore = documents();

// Reactive variable for the details modal
const isDetailsModalOpen = ref(false);

// Functions to control the details modal
const openDetailsModal = async () => {
  isDetailsModalOpen.value = true;
};
const closeDetailsModal = () => (isDetailsModalOpen.value = false);
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

const getPlantillas = async () => {
  openAnimation('spinner');
  const url = import.meta.env.VITE_API_URL;

  try {
    const response = await fetch(`${url}/thesis-process-student/download-resources`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      credentials: 'include',
    });

    // Verifica el estado de la respuesta
    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
    }

    // Registra los encabezados de la respuesta
    console.log('Encabezados de respuesta:', Array.from(response.headers.entries()));

    // Detectar el tipo de contenido
    const contentType = response.headers.get('Content-Type');
    console.log('Tipo de contenido:', contentType);

    if (!contentType || !contentType.includes('application/zip')) {
      throw new Error(`El servidor no devolvió un archivo ZIP. Tipo recibido: ${contentType}`);
    }

    // Procesar el archivo como blob
    const blob = await response.blob();
    console.log('Tamaño del archivo en bytes:', blob.size);

    // Crear un enlace temporal para descargar el archivo
    const urlBlob = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = urlBlob;
    a.download = 'plantillas.zip'; // Cambia el nombre del archivo según lo necesites
    a.click();
    window.URL.revokeObjectURL(urlBlob);

    console.log('Archivo descargado correctamente.');
  } catch (error) {
    console.error('Error al obtener las plantillas:', error);

    // Si falla, intenta capturar el texto de la respuesta
    try {
      const errorText = await response.text();
      console.error('Respuesta de error del servidor:', errorText);
    } catch (debugError) {
      console.error('No se pudo leer el texto de la respuesta:', debugError);
    }
  }
  closeAnimation();
};


function formatDate(dateString) {
  if (!dateString) return 'Fecha no disponible';
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, '0'); // Asegura dos dígitos
  const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Meses empiezan en 0
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
}

onMounted(async () => {
  await studentStore.getDataStatus(authStore.token);

  console.log(studentStore.dashboard_status);
  console.log(studentStore.faseActual);
  console.log(studentStore.requeriments);
  console.log(studentStore.generalData);
  console.log(studentStore.nextFase);
});

const mandarSolicitud = async () => {
  openAnimation('spinner');
  await studentStore.mandarSolicitud(authStore.token);
  closeAnimation();
};

</script>

<template>
  <div class="container mx-auto p-4 mt-11 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
      <h2 class="text-2xl font-bold text-center sm:text-left">Bienvenido, {{formatUserName(authStore.user.name)}}</h2>
        <RequestModal />   
          </header>
              <!-- Status Section -->
        <section class="mb-8 w-full overflow-x-auto">
          <div v-if="studentStore.generalData && Object.keys(studentStore.generalData).length>0">
            <h2 class="text-xl sm:text-2xl font-bold text-center text-primary mb-4">
              Estado del Proyecto:
            </h2>
            <p class="text-center text-gray-800 font-medium text-lg sm:text-xl leading-relaxed">
              {{ formatUserName(studentStore.generalData.thesis.title) }}
            </p>
          </div>
          <div class="flex justify-center">
            <!-- Contenedor principal con padding adicional a la izquierda -->
            <div class="w-full max-w-5xl p-2 sm:p-4 lg:p-6 flex gap-4 overflow-x-auto pl-4">
              <studentStatus />
            </div>
          </div>
        </section>

        <div v-if="studentStore.faseActual && Object.keys(studentStore.faseActual).length > 0 && studentStore.faseActual.phase_name != 'Fase Evaluación'">
        <!-- Resource Section -->
        <section class="mb-8">
          <h2 class="text-lg sm:text-xl font-semibold mb-4">Recurso</h2>
          <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <a @click="getPlantillas()" class="text-blue-600 underline text-base sm:text-lg flex items-center cursor-pointer">
              <i class="bi bi-file-earmark-arrow-down mr-2"></i> Descargar Plantillas
            </a>
            <button @click="openDetailsModal" class="btn btn-outline btn-primary flex items-center">
              <i class="bi bi-eye mr-2"></i> Ver detalles
            </button>
          </div>
        </section>

        <div v-if="isDetailsModalOpen" class="modal modal-open flex items-center justify-center bg-gray-900 bg-opacity-75 fixed inset-0 z-100">
          <div class="modal-box max-w-full sm:max-w-lg max-h-[70vh] overflow-y-auto p-6 bg-white shadow-lg rounded-md">
            <!-- Título del Modal -->
            <h3 class="font-bold text-2xl mb-6 text-gray-800 border-b border-gray-300 pb-2">Detalles de Recursos</h3>
        
            <!-- Título de la Tesis -->
            <div class="mb-6">
              <div class="flex items-center space-x-2 mb-2">
                <span class="badge bg-red-500 text-white text-sm font-semibold rounded-md py-1 px-2">Tesis</span>
                <h4 class="text-gray-800 font-semibold text-lg">Título</h4>
              </div>
              <p class="text-gray-700 text-base leading-relaxed">
                {{ formatUserName(studentStore.generalData.thesis?.title) || 'Sin título disponible' }}
              </p>
            </div>
        
            <!-- Categorías -->
            <div class="mb-6">
              <div class="flex items-center space-x-2 mb-2">
                <span class="badge bg-blue-500 text-white text-sm font-semibold rounded-md py-1 px-2">Categorías</span>
                <h4 class="text-gray-800 font-semibold text-lg">Asociadas</h4>
              </div>
              <ul class="list-disc pl-5 text-gray-700 text-sm mt-2 space-y-2">
                <li v-for="(area, index) in studentStore.generalData.thesis?.category_areas" :key="index">
                  <strong class="text-gray-800">{{ area.name }}</strong>: {{ area.description }}
                </li>
              </ul>
            </div>
        
            <!-- Periodo Académico -->
            <div class="mb-6">
              <div class="flex items-center space-x-2 mb-2">
                <span class="badge bg-gray-700 text-white text-sm font-semibold rounded-md py-1 px-2">Periodo</span>
                <h4 class="text-gray-800 font-semibold text-lg">Académico</h4>
              </div>
              <p class="text-gray-700 text-sm mt-1">
                <strong>Nombre:</strong> {{ studentStore.generalData.period_academic?.name || 'Sin nombre' }}
              </p>
              <p class="text-gray-700 text-sm mt-1">
                <strong>Inicio:</strong> {{ formatDate(studentStore.generalData.period_academic?.start_date) }}
              </p>
              <p class="text-gray-700 text-sm mt-1">
                <strong>Fin:</strong> {{ formatDate(studentStore.generalData.period_academic?.end_date) }}
              </p>
            </div>
        
            <!-- Tutor -->
            <div class="mb-6">
              <div class="flex items-center space-x-2 mb-2">
                <span class="badge bg-yellow-500 text-black text-sm font-semibold rounded-md py-1 px-2">Tutor</span>
                <h4 class="text-gray-800 font-semibold text-lg">Asignado</h4>
              </div>
              <p class="text-gray-700 text-sm">
                {{ formatUserName(studentStore.generalData.tutor?.user?.name) || 'Sin tutor asignado' }}
              </p>
            </div>
        
            <!-- Botón para cerrar el modal -->
            <div class="modal-action justify-end mt-6">
              <button @click="closeDetailsModal" class="btn bg-gray-800 text-white font-semibold py-2 px-6 rounded-md hover:bg-gray-700">
                Cerrar
              </button>
            </div>
          </div>
        </div>
      
        <!-- Requirements Section -->
        <section class="mb-8">
          <h2 class="text-lg sm:text-xl font-semibold mb-4">Requerimientos</h2>
          <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <p class="text-gray-700 text-sm sm:text-base flex items-center">
              ⚠️ Para pasar a la siguiente fase, es necesario completar todos los requerimientos.
            </p>
            <ClientOnly>
              <div class="w-full sm:w-auto">
                <studentRequeriment />
              </div>
            </ClientOnly>
          </div>
        </section>
        </div>
        <div v-else-if="studentStore.faseActual.phase_name == 'Fase Evaluación'">
          <div class="alert alert-info shadow-lg rounded-lg p-4 flex items-start">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
              </svg>
            </div>
            <div class="ml-4">
              <span class="text-lg font-bold text-primary">¡Atención!</span>
              <p class="mt-2">
                En este momento estás en la fase de <span class="font-semibold text-primary">Evaluación</span>. 
                Espera a que se te asigne una <span class="font-semibold text-primary">fecha de sustentación</span>.
              </p>
            </div>
          </div>
          
        </div>
      <div v-else-if="studentStore.prerequsito === false"
      class="flex flex-col items-center justify-center gap-6 p-6 bg-white rounded-lg shadow-md">
      <!-- Mensaje -->
      <p class="text-lg font-semibold text-gray-700 text-center">
        <span v-if="studentStore.nextFase?.data?.requeriment">
          ¡Ya ha enviado la solicitud para matricularse en 
          <span class="text-primary">
            {{ studentStore.nextFase?.data?.phase_name || 'la siguiente fase' }}
          </span>!
        </span>
        <span v-else>
          ¡Haz completado la fase anterior! Si desea continuar el proceso, haga una solicitud para matricularse en 
          <span class="text-primary">
            {{ studentStore.nextFase?.data?.phase_name || 'la siguiente fase' }}
          </span>.
        </span>
      </p>
      <!-- Botón -->
      <button
        @click="mandarSolicitud()"
        :disabled="studentStore.nextFase?.data?.phase_requests === true"
        class="btn w-full sm:w-auto px-6 py-3"
        :class="studentStore.nextFase?.data?.phase_requests ? 'btn-disabled' : 'btn-primary'"
      >
        {{ studentStore.nextFase?.data?.phase_requests ? 'Solicitud Enviada' : 'Enviar Solicitud' }}
      </button>
    </div>
    <div v-else-if="studentStore.prerequsito === true ">
              <!-- Requirements Section -->
              <section class="mb-8">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Pre-Requisitos</h2>
                <div class="bg-gray-100 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                  <p class="text-gray-700 text-sm sm:text-base flex items-center">
                    ⚠️ Para solicitar matricularse en la siguiente fase, debe completar los pre-requisitos.
                  </p>
                  <ClientOnly>
                    <div class="w-full sm:w-auto">
                      <StudentPreRquisits />
                    </div>
                  </ClientOnly>
                </div>
              </section>
    </div>
  </div>
</template>

