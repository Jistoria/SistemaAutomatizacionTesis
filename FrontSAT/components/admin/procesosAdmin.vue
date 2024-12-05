<script setup>
import { admin } from '~/stores/dashboards/admin';
import { usePaginationStore } from '~/stores/pagination/pagination';
import { computed, onMounted } from 'vue';
import { sweetAlert } from '~/composables/sweetAlert';
const { openAnimation, closeAnimation } = inject('requestAnimation');


const swal = sweetAlert();

const paginationStore = usePaginationStore();
const adminStore = admin();
const visibleData = computed(() => paginationStore.visible_data);
const listName = 'admin';
const pageSize = 5;

// Función para mostrar una alerta temporal para las acciones en construcción
const showAlert = (action) => {
      swal.showAlert('info', 'normal', { title: `Funcion de ${action}`, text: 'Se esta trabajando en esta funcion, esperala en una version futura', confirmType: 'normal' });
};

// Monta la paginación y carga los datos
onMounted(async () => {
  openAnimation('spinner');
  await adminStore.getProcesosTesis(); // Carga los estudiantes desde el store
  await paginationStore.setupPagination({
    dataStore: adminStore.procesosTesis, // Asigna los datos al paginador
    pageSize: pageSize, // Tamaño de página
    listName: listName, // Nombre único para la paginación
    caso: 'procesosTesis', // Caso para diferenciar listas
  });
  closeAnimation();
});
onUnmounted(async() => {
  paginationStore.resetPagination();
});
</script>
<template>
  <div class="p-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
      <h1 class="text-2xl font-bold text-center md:text-left">
        Gestión de Procesos de Tesis
      </h1>
      <UploadFiles></UploadFiles>
    </div>
    <!-- Filtro y busqueda -->
    <div class="flex justify-between">
      <div>
          <FilterSearch />
      </div>
  
    </div>
    <!-- Tabla -->
    <div class="overflow-x-auto">
      <table class="table w-full">
        <thead>
          <tr>
            <th class="whitespace-nowrap">Titulo</th>
            <th class="whitespace-nowrap">Fecha de Inicio</th>
            <th class="whitespace-nowrap">Estudiante/s</th>
            <th class="whitespace-nowrap">Tutor</th>
            <th class="whitespace-nowrap">Acciones</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="process in visibleData" :key="process.thesis_title">
                <td>{{ process.thesis_title || "Sin Título" }}</td>
                <td>{{ process.latest_start_date || "Sin Fecha" }}</td>
                <td>
                  {{ process.student_names || "Sin Estudiantes" }}
                </td>
                <td>{{ process.teacher_names || "Sin Tutores" }}</td>
                <td class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:space-x-2">
                  <button
                    @click="showAlert('editar proceso')"
                    class="btn btn-primary btn-sm"
                  >
                    Editar
                  </button>
                  <button
                    @click="showAlert('eliminar proceso')"
                    class="btn btn-error btn-sm"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>              
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4 flex justify-center">
      <Pagination :listName="listName" :pageSize="pageSize" />
    </div>
  </div>
</template>








