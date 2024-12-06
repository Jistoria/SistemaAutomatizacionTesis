<script setup>
import FilterSearch from '~/components/general/filterSearch.vue';
import { management } from '~/stores/management/management';
import { usePaginationStore } from '~/stores/pagination/pagination';
import { Fase,Requerimientos,Requerimientos_use } from '~/composables/studentDetails';
import { sweetAlert } from '~/composables/sweetAlert';
const swal = sweetAlert();
const managementStore = management();
const paginationStore = usePaginationStore();
const visibleData = computed(() => managementStore.management.data);
const base_url = import.meta.env.VITE_API_STORAGE;
const loadingStates = ref({});
const { openAnimation, closeAnimation } = inject('requestAnimation');
const listName = 'estudiante';
const pageSize = 2;
const isLoading = ref(true); // Inicialmente está cargando
const hasNoData = computed(() => !isLoading.value && visibleData.value.length == 0); // Condición para mostrar el mensaje de "sin datos"

onMounted(async () => {
    openAnimation('spinner');
    await managementStore.getManagement();
    paginationStore.setupPagination({
        dataStore: managementStore.management,
        pageSize: 2,
        listName: 'estudiante',
    });
    isLoading.value = false;
    closeAnimation();

});

const data_place = ref(
    [
        {
            student:'FLORES CEDEÑO WILLIAM ANDRES',
            status:'pendiente',
            email:'e1316612603@live.uleam.edu.ec',
            Carrera:'TI',
            tutor:'ING ARMANDO FRANCO',
            modal:'Proyecto Integrador',
        }
    ],
)
const openModal= async(data)=>{
    const modal = document.getElementById(`modal_Prerequeriments_${data}`);
    if (modal) modal.showModal()
}
const closeModal= (data)=>{
    const modal = document.getElementById(`modal_Prerequeriments_${data}`);
    if (modal) modal.close();
}
const download_file = (url)=>{
    const fileUrl = `${base_url}/${url}`;
    const link = document.createElement('a');
    link.href = fileUrl; 
    link.download = ''; 
    link.target = '_blank'; 
    document.body.appendChild(link); 
    link.click(); 
    document.body.removeChild(link); 
}
const statuses = ['Enviado', 'Aprobado', 'Rechazado', 'Pendiente'];
const filteredActions = (status) => {
  if (status === 'Enviado') {
    return Requerimientos_use; // Mostrar ambos botones
  } else if (status === 'Aprobado') {
    return Requerimientos_use.filter((action) => action.state === 'Rechazado'); // Solo botón de Rechazado
  } else if (status === 'Pendiente') {
    return []; // No mostrar botones
  }
  return []; // Por defecto, no mostrar nada
};
const getGroupedRequirements = (preRequirements) => {
  const grupos = {};
  statuses.forEach((status) => {
    grupos[status] = preRequirements.filter((req) => req.status === status);
  });
  return grupos;
};
const change_status = async (pre_req,data,id_student,student_prerequirements_id,status)=>{

    // if(status === 'Rechazado'){
    //      const response = await swal.showAlert('info', 'normal',{
    //          title: 'Aviso',
    //          text: 'Esta accion no se puede Desahacer, seguro de continuar?',
    //          confirmType: 'confirm',
    //      })
    //      if(response == false){
    //          return
    //      }
    // }
    loadingStates.value[student_prerequirements_id] = true;
    const response = await managementStore.changePreRequesite(student_prerequirements_id,status);  
    if(response == true){
        const requerimentIndex = pre_req.findIndex((req) => req.student_prerequirements_id === student_prerequirements_id);
        if(requerimentIndex !== -1){

            pre_req[requerimentIndex].status = status;             
             await managementStore.getManagement();
                          swal.showAlert('success','right',{title: `Prerrequerimiento ${status}`, text: '',confirmType: 'timer'}) 
        }
    }else{
                swal.showAlert('Error','right',{title: 'No se pudo cambiar el Requerimiento', text: '',confirmType: 'timer'})
    }
    loadingStates.value[student_prerequirements_id] = false;

}
</script>
<template>

    <div v-if="isLoading" class="flex justify-center items-center py-8">
        <span class="loading loading-spinner loading-lg text-primary"></span>
    </div>
    <div v-else-if="hasNoData" class="flex justify-center items-center py-8 ">
        <p class="text-gray-500 text-lg bg-neutral p-6 rounded-full">No hay Prerrequerimientos de momento.</p>
    </div>

    <div v-else class="container mx-auto p-4">
        <FilterSearch>
        </FilterSearch>
        <div class="container mx-auto p-4  ">
            <div v-for="data in visibleData" class="bg-neutral ps-2 mt-2 border-2 border-stone-300">
                <div class="pt-4 ps-4">
                    <div class="flex  justify-between">
                        <div>
                            <p>
                                <a class="font-semibold me-3" >Documentos:</a>
                                <a class="bg-white p-3 rounded-full border-2 border-stone-300" >
                                    <i class="bi bi-file-earmark-fill"></i> 0/6
                                </a>
                            </p>
                        </div>
                        <div class="pe-2">
                            <i class="btn border-2 border-stone-300" @click="openModal(data.student_id)">
                                <i class="bi bi-three-dots-vertical icon_size_2"></i>
                            </i>
                            <dialog :id="`modal_Prerequeriments_${data.student_id}`" class="modal">
                                <div class="modal-box w-11/12 max-w-5xl ">
                                    <h3 class="text-lg font-bold">Prerrequerimientos</h3>
                                    <div  v-for="status in statuses">
                                        <h3
                                            :class="{
                                            'text-black-500': status === 'Enviado',
                                            'text-green-500': status === 'Aprobado',
                                            'text-red-500': status === 'Rechazado',
                                            'text-slate-500': status === 'Pendiente',
                                            }"
                                            class="text-md font-semibold mb-2"
                                        >
                                            {{ status }}
                                        </h3>
                                        <div v-if="getGroupedRequirements(data.pre_requirements)[status].length">
                                            <div v-for="pre_req in getGroupedRequirements(data.pre_requirements)[status]" class="bg-white px-4 py-3 rounded-lg shadow border mb-2" >
                                                <div class="flex items-center justify-between" >
                                                    <div>
                                                        <i class="bi bi-file-earmark"></i>
                                                        <a
                                                            @click="pre_req.url_file ? download_file(pre_req.url_file) : null"
                                                            :class="{
                                                            'text-blue-500 underline cursor-pointer ms-2': pre_req.url_file,
                                                            'text-gray-500 ms-2': !pre_req.url_file,
                                                            }"
                                                        >
                                                            {{ pre_req.pre_requirement.name }}
        
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <div v-for="actions in filteredActions(pre_req.status)"
                                                            :class="[actions.btn_color, 'pt-2 pb-2 inline rounded-full ms-2']"
                                                            >
                                                                <button @click="change_status(data.pre_requirements,visibleData,data.student_id, pre_req.student_prerequirements_id,actions.state)"  class="pt-4 pb-3 ps-2 pe-2  ms-2 me-2 ">
                                                                    {{ actions.title }}
                                                                    <i :class="actions.icon_use" ></i>
                                                                </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-action">
                                    <form method="dialog">
                                        <!-- if there is a button in form, it will close the modal -->
                                        <button @click="closeModal(data.student_id)" class="btn">Close</button>
                                    </form>
                                    </div>
                                </div>
                            </dialog>
                        </div>
                    </div>
                </div>
                <!-- Datos Del Estudiante -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 ">
                    <div class="ps-4 pe-4" >
                      <p class="font-semibold text-gray-700">Estudiante:</p>
                      <p class="bg-white px-4 py-2 rounded-lg font-medium border-2 border-stone-300">
                        {{ data.user.name }}
                      </p>
                    </div>
                    <div class="ps-4 pe-4" >
                      <p class="font-semibold text-gray-700">Correo:</p>
                      <p class="bg-white px-4 py-2 rounded-lg font-medium border-2 border-stone-300">
                        {{ data.user.email }}
                      </p>
                    </div>
                    <div class="ps-4 pe-4">
                        <p class="font-semibold text-gray-700">Tutor:</p>
                        <p class="bg-white px-4 py-2 rounded-lg font-medium border-2 border-stone-300">
                            {{ data.thesis_process.tutor.user.name }}
                        </p>
                    </div>
                </div>
                <!-- El tema -->
                 <div class="ps-4 pe-4 pb-4">
                    <p class="font-semibold text-gray-700 mb-2">Tema:</p>
                    <div class="bg-white px-4 py-3 rounded-lg border-l-4  border-2 border-stone-300">
                        <p class="text-gray-600">
                            {{ data.thesis_process.thesis.title }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <Pagination :listName="listName" :pageSize="pageSize" ></Pagination>
    </div>

</template>
<style>

</style>