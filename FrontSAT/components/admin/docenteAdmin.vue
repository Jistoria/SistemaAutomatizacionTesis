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
  await adminStore.getDocentes(); // Carga los estudiantes desde el store
  await paginationStore.setupPagination({
    dataStore: adminStore.docentes, // Asigna los datos al paginador
    pageSize: pageSize, // Tamaño de página
    listName: listName, // Nombre único para la paginación
    caso: 'docentes', // Caso para diferenciar listas
  });
  console.log(visibleData.value[0]);
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
        Gestión de Docentes
      </h1>
      <button
        @click="showAlert('crear docente')"
        class="btn btn-primary w-full md:w-auto"
      >
        Crear Docente
      </button>
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
                <th class="whitespace-nowrap">Docente</th>
                <th class="whitespace-nowrap">Email</th>
                <th class="whitespace-nowrap">Categorías</th>
                <th class="whitespace-nowrap">Estudiantes a cargo</th>
                <th class="whitespace-nowrap">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="docent in visibleData" :key="docent.teacher_id">
                <!-- Docente Name -->
                <td>{{ docent.user?.name || "Sin Nombre" }}</td>
          
                <!-- Docente Email -->
                <td>{{ docent.user?.email || "Sin Email" }}</td>
          
                <!-- Categorías -->
                <td>
                  {{ docent.category_areas
                    ?.map((category) => category.name)
                    .join(", ") || "Sin Categorías" }}
                </td>
          
                <!-- Estudiantes a Cargo -->
                <td>
                  {{ docent.students_process_count || 0 }}
                </td>
          
                <!-- Acciones -->
                <td class="space-y-2 flex flex-col md:flex-row md:space-x-2 md:space-y-0">
                  <button
                    @click="showAlert('Editar Docente')"
                    class="btn btn-primary btn-sm"
                  >
                    Editar
                  </button>
                  <button
                    @click="showAlert('Eliminar Docente')"
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








