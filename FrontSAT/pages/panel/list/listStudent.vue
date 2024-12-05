<script setup>
import { panel } from '~/stores/panel/panel';
import { useRouter } from 'vue-router';
import { StudentDetails } from '~/stores/details/studentDetails';
import { usePaginationStore } from '~/stores/pagination/pagination';
import { auth } from '~/stores/auth/auth';
import ListManagment from './listManagment.vue';
const studentDetailsStore = StudentDetails();
const panelStore = panel();
const authStore = auth();
import { sweetAlert } from '~/composables/sweetAlert';
const swal = sweetAlert();
const paginationStore = usePaginationStore();

// Obtener los datos visibles desde el store de paginación
const visibleData = computed(() => paginationStore.visible_data);

onMounted(async () => {
   if(authStore.role =='Administrador-tesis'){
        console.log('es admin');
    }
    if(authStore.role == 'Analista-Carrera'){
        console.log('es analista');
    }
    if(authStore.role == 'Docente-tesis'){
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
        return  'bg-success p-2  rounded-full border-2 border-stone-300' 
    }else{
        return  'bg-neutral p-2 rounded-full border-2 border-stone-300' 
    }
}
const details_student = (data)=>{
    console.log(data);
    studentDetailsStore.setStudentDetails(data);
    router.push('/panel/details/studentDetails');
}
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
        swal.showAlert('success','right',{title: `Fases Actualizadas`, text: '',confirmType: 'timer'})
    }else{
        swal.showAlert('error','right',{title: `A ocurrido un error`, text: '',confirmType: 'timer'})

    }

    console.log('cambiar de fase');
}


</script>
<template>
    <div class="container mx-auto  mt-10 ">
        <div v-if="authStore.role == 'Docente-tesis'">
            <div >
                <div class="flex justify-between">
                    <div>
                        <FilterSearch />
                    </div>
                </div>
            </div>
            <div  v-for="dataList in visibleData" class="bg-gray-300  rounded-lg grid grid-cols-3 gap-4 items-center p-4 mt-2 mb-2">
            <div class=" col-span-2 bg-white ms-2 rounded-lg border-2 border-stone-300">
                <div class="flex items-center mt-5 space-x-4 text-gray-600 text-xs ps-6 pt-3 ">
                    <div class="flex items-center space-x-1 ">
                        <div class="bg-error p-2 rounded-full">
                            <i class="bi bi-person-fill icon_size"></i>
                        </div>
                        <span class="bg-neutral p-2 rounded-full border-2 border-stone-300">
                            {{ dataList.name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <i class="bi bi-calendar-fill icon_size pe-2"></i>
                        <!-- phase -->
                        <span class="bg-neutral p-2 rounded-full border-2 border-stone-300">
                            {{dataList.phase_name}}
                        </span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <!-- estado del usuario -->
                        <span  :class="chageDatacolor('habilitado')"  >
                            {{dataList.state_now}}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <!-- descripcion -->
                    <a class="bg-neutral p-2 mb-2 rounded-full border-2 border-stone-300">
                        Tema:
                    </a>
                    <p class=" italic mt-2">
                        {{ dataList.title }}
                    </p>
                </div>
            </div>
            <div class=" justify-center p-1 ">
                <div  >
                    <div class="grid grid-cols-2 gap-4 p-2">
                        <div class="h-full flex flex-col ">
                            <div class="grid grid-cols-1 b p-4 flex-grow bg-white  border-2 border-stone-300 rounded">
                                <div class="p-1 rounded ">
                                    <div class="italic">
                                        Documentos:
                                    </div>
                                    <div >
                                        <div class="flex justify-between" >
                                            <a class="ms-1">Pendientes: </a>
                                            <a>
                                                {{ dataList.total_requirements_pending }}
                                                <i class="bi bi-hourglass-bottom"></i>
                                            </a>     
                                        </div>
                                        <div class="me-1 flex justify-between">
                                            <a class="ms-1">Aprobados:</a>
                                            <a>
                                                {{dataList.total_requirements_approved}}
                                                <i class="bi bi-check-circle-fill"></i>
                                            </a>     
                                        </div>
                                        <div class="me-1 flex justify-between">
                                            <a class="ms-1">Enviados:</a>
                                            <a>
                                                {{ dataList.total_requirements_sent }}
                                                <i class="bi bi-envelope-fill"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex content-center">
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
        <div v-else-if="authStore.role == 'Analista-Carrera'">
            <ListManagment></ListManagment>
        </div>
    </div>

</template>
<style>

</style>