<script setup>
import { panel } from '~/stores/panel/panel';
import { useRouter } from 'vue-router';
import { StudentDetails } from '~/stores/details/studentDetails';
import { usePaginationStore } from '~/stores/pagination/pagination';
import { auth } from '~/stores/auth/auth';
const studentDetailsStore = StudentDetails();
const panelStore = panel();
const authStore = auth();

const paginationStore = usePaginationStore();

// Obtener los datos visibles desde el store de paginación
const visibleData = computed(() => paginationStore.visible_data);

onMounted(async () => {
   if(authStore.role =='Administrador-tesis'){
        console.log('es admin');
    }else{
        await panelStore.getlistStudents(1) 
        paginationStore.setupPagination({
            dataStore: panelStore.stuendent_data,
            pageSize: 2,
            listName: 'estudiante',
        });
    }
  console.log(visibleData.value);
});

onUnmounted(() => {
  paginationStore.resetPagination();
});
const studentlist= ref(null);
const { openAnimation, closeAnimation } = inject('requestAnimation');

const router = useRouter();

const listName = 'estudiante';
const pageSize = 2;

const chageDatacolor = (data)=>{
    if(data == 'Habilitado'){
        return  'bg-success p-2 rounded-lg' 
    }else{
        return  'bg-neutral p-2 rounded-lg' 
    }
}
const details_student = (data)=>{
    console.log(data);
    studentDetailsStore.setStudentDetails(data);
    router.push('/panel/details/studentDetails');
}



</script>
<template>
    <div class="container mx-auto  mt-10 ">
        <div >
            <FilterSearch />
        </div>
        <div v-for="dataList in visibleData" class="bg-gray-300  rounded-lg grid grid-cols-3 gap-4 items-center p-4 mt-2 mb-2">
            <div class=" col-span-2 bg-white ms-2 rounded-lg ">
                <div class="flex items-center mt-5 space-x-4 text-gray-600 text-xs ps-6 pt-3 ">
                    <div class="flex items-center space-x-1 ">
                        <i class="bi bi-person-fill icon_size"></i>
                        <span>
                            <!-- nombre -->
                            {{ dataList.name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <i class="bi bi-calendar-fill icon_size pe-2"></i>
                        <!-- phase -->
                        <span>{{dataList.phase_name}}</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <!-- estado del usuario -->
                        <span :class="chageDatacolor('habilitado')" >{{dataList.state_now}}</span>
                    </div>
                </div>
                <div class="p-6">
                    <!-- descripcion -->
                    <a>Tema:</a>
                    <p class=" italic">
                        {{ dataList.title }}
                    </p>
                </div>
            </div>
            <div class=" justify-center p-1 ">
                <div >
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="text-center italic">Documentos:</div>
                            <div class="grid grid-cols-3 text-center p-3">
                                <div class="me-2">
                                    <i class="bi bi-hourglass-bottom"></i>
                                    <a >{{ dataList.total_requirements_pending }}</a>     
                                </div>
                                <div class="me-2">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <a>{{dataList.total_requirements_approved}}</a>     
                                </div>
                                <div class="me-2">
                                    <i class="bi bi-envelope-fill"></i>
                                    <a >{{ dataList.total_requirements_sent }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex  ">
                            <div class="justify-self-center p-4">
                                <button @click="details_student(dataList)" class="btn btn-neutro">
                                    <i class="bi bi-info icon_size_2"></i>Ver mas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <Pagination :listName="listName" :pageSize="pageSize" />
    </div>

</template>
<style>

</style>