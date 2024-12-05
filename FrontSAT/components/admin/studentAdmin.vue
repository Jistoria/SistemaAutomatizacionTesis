<script setup>
import { admin } from '~/stores/dashboards/admin';
import { usePaginationStore } from '~/stores/pagination/pagination';
import { computed, onMounted } from 'vue';
import { sweetAlert } from '~/composables/sweetAlert';
import { StudentDetails } from '~/stores/details/studentDetails';
const { openAnimation, closeAnimation } = inject('requestAnimation');
const studentDetailsStore = StudentDetails();

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
  await adminStore.getEstudiantes(); // Carga los estudiantes desde el store
  await paginationStore.setupPagination({
    dataStore: adminStore.estudiantes, // Asigna los datos al paginador
    pageSize: pageSize, // Tamaño de página
    listName: listName, // Nombre único para la paginación
    caso: 'estudiantes', // Caso para diferenciar listas
  });
  console.log(visibleData.value[0]);
  closeAnimation();
});
onUnmounted(async() => {
  paginationStore.resetPagination();
});
const accept_phase = async()=>{
    const response = await swal.showAlert('info', 'normal',{
            title: 'Aviso',
            text: 'Se realizara el cambio de fase a todos los estudiantes que mandaron solicitud, esta seguro?',
            confirmType: 'confirm',
    })
    if(response == false){
            return
    }
    const data = await studentDetailsStore.aprobeStudent();
    if(data == true){
        swal.showAlert('success','right',{title: `Fases aprobadas correctamente`, text: '',confirmType: 'timer'})
    }else{
        swal.showAlert('error','right',{title: `A ocurrido un error`, text: '',confirmType: 'timer'})

    }
}
</script>
<template>
  <div class="p-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
      <h1 class="text-2xl font-bold text-center md:text-left">
        Gestión de Estudiantes
      </h1>
      <div>
        <button
          @click="showAlert('Crear Estudiante')"
          class="btn btn-primary w-full md:w-auto"
        >
          Crear Estudiante
        </button>
        <button>
        <div class="p-1 ">
          <button @click="accept_phase" class="btn btn-primary w-full md:w-auto">
            Empezar Siguiente Fase
          </button>
        </div>
      </button>
      </div>
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
            <th class="whitespace-nowrap">Estudiante</th>
            <th class="whitespace-nowrap">Email</th>
            <th class="whitespace-nowrap">DNI</th>
            <th class="whitespace-nowrap">Fecha de Inscripción</th>
            <th class="whitespace-nowrap">Tema de Tesis</th>
            <th class="whitespace-nowrap">Categorías</th>
            <th class="whitespace-nowrap">Tutor</th>
            <th class="whitespace-nowrap">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in visibleData" :key="student.student_id">
            <td>{{ student.user?.name || "Sin Nombre" }}</td>
            <td>{{ student.user?.email || "Sin Email" }}</td>
            <td>{{ student.dni || "Sin DNI" }}</td>
            <td>{{ student.enrollment_date || "Sin Fecha" }}</td>
            <td>{{ student.thesis_process?.thesis?.title || "Sin Título" }}</td>
            <td>
              {{ student.thesis_process?.thesis?.category_areas
                ?.map((area) => area.name)
                .join(", ") || "Sin Categorías" }}
            </td>
            <td>{{ student.thesis_process?.tutor?.user.name || "Sin Tutor" }}</td>
            <td class="space-x-2 flex flex-col md:flex-row md:space-y-0 space-y-2">
              <button
                @click="showAlert('Editar Estudiante')"
                class="btn btn-info btn-sm"
              >
                Editar
              </button>
              <button
                @click="showAlert('Eliminar Estudiante')"
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








