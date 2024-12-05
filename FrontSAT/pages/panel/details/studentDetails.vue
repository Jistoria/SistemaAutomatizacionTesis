<script setup>
import { Fase,Requerimientos,Requerimientos_use } from '~/composables/studentDetails';
import Observation from '~/components/modal/observation.vue';
import { StudentDetails } from '~/stores/details/studentDetails';
import { panel } from '~/stores/panel/panel';
import { sweetAlert } from '~/composables/sweetAlert';
const studentDetailsStore = StudentDetails();
const select_phase = ref('');
const phase_default = ref(studentDetailsStore.selectedStudent.phase_name);
const show_phase = ref(false);

const setActivePhase = async(phase) => {
    isLoading.value = true;
    const data = await studentDetailsStore.get_pluck_phases();
    select_phase.value = findUIDByPhaseName(phase,data);
    const data_1 = await studentDetailsStore.get_requeriments(studentDetailsStore.selectedStudent.id, select_phase.value[0]);
    console.log(data_1)
    if(data_1 === null){
        show_phase.value = true;
        phase_default.value = phase;
        isLoading.value = false;
        return
    }
    show_phase.value = false;
    phase_default.value = phase;
    requerimets_details.value = data_1.requirements;
    isLoading.value = false;
}
const findUIDByPhaseName = (phaseName,data) => {
  const entries = Object.entries(data);
  const result = entries.find(([key, value]) => value === phaseName);
  return result ? result : null;
};


definePageMeta({
    middleware: 'details'
})


const swal = sweetAlert();
const base_url = import.meta.env.VITE_API_STORAGE;
const requerimets_details = ref([]);
const requeriment_selected = ref(null);
const panelStore = panel();
//


//para la carga
const isLoading = ref(false);
const isProcessing = ref(false); 

onMounted(async () => {
    studentDetailsStore.hydrate();
    requerimets_details.value = studentDetailsStore.RequerimentsSelected;
    const data = await studentDetailsStore.get_requeriments(studentDetailsStore.selectedStudent.id, studentDetailsStore.selectedStudent.thesis_phases_id);
    requerimets_details.value = data.requirements;
});
//tengo que hacer un emmit que cuando pase el updtate requeriments pueda yo actualizar el requerimets_details

//
const selectedStatus = ref('Pendiente');
const filterRequerimentsByStatus = (status) => {
    console.log(status)
  selectedStatus.value = status;
};

const filteredRequeriments = computed(() =>
    Array.isArray(requerimets_details.value) && requerimets_details.value.length > 0
        ? requerimets_details.value.filter((req) => req.status === selectedStatus.value)
        : []
);
//

const change_status = async (id_student, id_requeriment_student,status)=>{
    if(status === 'Rechazado'){
        const response = await swal.showAlert('info', 'normal',{
            title: 'Aviso',
            text: 'Esta accion no se puede Desahacer, seguro de continuar?',
            confirmType: 'confirm',
        })
        if(response == false){
            return
        }
    }
    console.log(status);
    loadingStates.value[id_requeriment_student] = true;
    isProcessing.value = true;
    const response = await studentDetailsStore.changeStudentreq(id_student, id_requeriment_student,status);  
     if(response == true){
         const requerimentIndex = requerimets_details.value.findIndex((req) => req.student_requirements_id === id_requeriment_student);
         if(requerimentIndex !== -1){
             requerimets_details.value[requerimentIndex].status = status;
             panelStore.isLoaded = false;
             panelStore.getlistStudents();
                swal.showAlert('success','right',{title: `Requerimiento ${status}`, text: '',confirmType: 'timer'})
 
        }
    }else{
        swal.showAlert('Error','right',{title: 'No se pudo cambiar el Requerimiento', text: '',confirmType: 'timer'})
    }
    loadingStates.value[id_requeriment_student] = false;
    const timer = setTimeout(() => {
        isProcessing.value = false;
    }, 5000); 
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

//
const loadingStates = ref({});
const statuses = ['Enviado', 'Aprobado', 'Rechazado', 'Pendiente'];
const requerimientosPorEstado = computed(() => {
  const grupos = {};
  statuses.forEach((status) => {
    grupos[status] = studentDetailsStore.RequerimentsSelected.filter((req) => req.status === status);
  });
  return grupos;
});
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
</script>
<template>
<div>
    <div class="container mx-auto p-4 ">
        <div class="p-4 ">
            <h1 class="text-2xl p-3">Detalles del estudiante:</h1>
            <div class="bg-neutral p-4 rounded">
                <div class="grid grid-cols-4 p-4 gap-3">
                    <div class="col-span-3 ">
                        <div  role="tablist" class="tabs tabs-lifted">
                            <a  class="tab" v-for="phase in Fase" :class="{ 'tab-active text-primary bg-yellow-500': phase.Phases_select === phase_default, 'text-gray-500': phase !== phase_default}" @click="setActivePhase(phase.Phases_select)" >
                                    {{phase.phase_name}}
                            </a>
                        </div>
                        <div class="bg-white p-4 border-b-2 border-l-2 border-r-2  border-b-stone-300 border-l-stone-300 border-r-stone-300  ">
                            <div class="mt-2 mb-4">
                                <div class="flex justify-between">
                                    <div>
                                        <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full me-4 ">
                                        Estudiante:
                                        </a>
                                        <a>
                                            {{ studentDetailsStore.selectedStudent.name }}
                                        </a>
                                    </div>
                                    <div>
                                        <a class="p-2  bg-neutral italic rounded">
                                            {{ studentDetailsStore.selectedStudent.phase_state_now}}
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full me-2" >
                                        Correo:
                                    </a>
                                    <a>
                                        {{ studentDetailsStore.selectedStudent.email }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full" >Tema:</a>
                                <p class="p-4 ps-2 mb-2">
                                    <a>{{ studentDetailsStore.selectedStudent.title }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1  h-full flex flex-col">
                        <div class="grid grid-cols-1 b p-4 flex-grow bg-white border-2 border-stone-300">
                            <div class="p-4 rounded ">
                                <div class=" rounded ">
                                    <div v-if="false">
                                            <div class="p-4">
                                                Faltan procesos Pendientes
                                            </div>
                                    </div>
                                    <div v-else >
                                            <div >
                                                <div class="flex justify-between">
                                                    <div class="p-1">
                                                        <a >
                                                            Sustentacion:
                                                        </a>
                                                    </div>
                                                </div>
                                                <div v-for="data in 3">
                                                    <ul class="list-disc ps-4 mt-2 ">
                                                        <li>
                                                            <div class="flex justify-between">
                                                                <div>
                                                                    <a>Tribunal 1:</a>
                                                                    <p>
                                                                        Ing. Robert Moreira Mg.
                                                                    </p>
                                                                </div>
                                                                <div class="content-end">
                                                                    <i class="bi bi-check-circle-fill icon_size_2"></i>
                                                                    <i class="bi bi-x-circle-fill icon_size_2"></i>

                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- aqui debo hacer el v-if -->
                <div class="grid grid-cols-1 ps-4 pe-4 ">
                    <h1 class="text-2xl pt-0 ps-1 pb-2">Requerimientos:</h1>
                    <div v-if="show_phase" class="bg-white p-4 border-2 border-stone-300">
                        <div v-if="!isLoading">
                            <p class="text-center">Este Estudiante aun no esta en esta Fase</p>
                        </div>
                        <div v-if="isLoading" class="p-4">
                                <div class="flex justify-center">
                                    <div>
                                        <span class="loading loading-bars loading-lg"></span>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div v-else  class="bg-white p-4 border-2 border-stone-300">
                        <div v-if="isLoading" class="p-4">
                                <div class="flex justify-center">
                                    <div>
                                        <span class="loading loading-bars loading-lg"></span>
                                    </div>
                                </div>
                        </div>
                        <div v-else v-for="status in statuses">
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
                            <div v-if="requerimientosPorEstado[status].length">
                                <div
                                    v-for="files in requerimientosPorEstado[status]"
                                    class=" p-3 mb-2 bg-white rounded-lg shadow border"
                                >
                                    <div class="flex">
                                        <div class="grow p-4">
                                                <i class="bi bi-file-earmark"></i>
                                                <a @click="files.url_file ? download_file(files.url_file) : null"  :class="{
                                                        'text-blue-500 underline cursor-pointer ms-2': files.url_file,
                                                        'text-gray-500': !files.url_file,
                                                    }">
                                                    {{ files.name }}
                                                </a>
                                        </div>
                                        <div  v-if="loadingStates[files.student_requirements_id]" class="bg-neutral p-4">
                                            <div class="flex justify-center">
                                                <div>
                                                    <span class="loading loading-bars loading-lg"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <Observation :requirementId="files.student_requirements_id" ></Observation>
                                            <div v-for="actions in filteredActions(files.status)" 
                                            :class="[
                                                actions.btn_color, 'pt-2 pb-2 inline rounded-full ms-2 opacity-70',
                                                { 'opacity-45 cursor-not-allowed': isProcessing, 'opacity-100': !isProcessing }
                                                ]" 
                                            >
                                                <button :disabled="isProcessing" 
                                                 @click="change_status(studentDetailsStore.selectedStudent.id,files.student_requirements_id, actions.state)"  class=" pt-4 pb-3 ps-2 pe-2  ms-2 me-2 ">
                                                    {{ actions.title }}
                                                    <i :class="actions.icon_use" ></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto p-4 hidden">
        <div class="p-4">
            <h1 class="text-2xl p-3">Detalles del estudiante:</h1>
            <div class="grid grid-cols-2  p-4 rounded">
                <div class="bg-neutral p-4 rounded">

                    <div class="p-4">
                        <div >
                            <div  role="tablist" class="tabs tabs-lifted">
                                <a  class="tab" v-for="phase in Fase" :class="{ 'tab-active text-primary bg-yellow-500': phase.Phases_select === phase_default, 'text-gray-500': phase !== phase_default}" @click="setActivePhase(phase.Phases_select)" >
                                        {{phase.phase_name}}
                                </a>
                            </div>
                            <div class="bg-white p-4 border-b-2 border-l-2 border-r-2  border-b-stone-300 border-l-stone-300 border-r-stone-300  ">
                                <div class="mt-2 mb-4">
                                    <div class="flex justify-between">
                                        <div>
                                            <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full me-4 ">
                                            Estudiante:
                                            </a>
                                            <a>
                                                {{ studentDetailsStore.selectedStudent.name }}
                                            </a>
                                        </div>
                                        <div>
                                            <a class="p-2  bg-neutral italic rounded">
                                                {{ studentDetailsStore.selectedStudent.phase_state_now}}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full me-2" >
                                            Correo:
                                        </a>
                                        <a>
                                            {{ studentDetailsStore.selectedStudent.email }}
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <a class="bg-neutral pt-2 pb-2 ps-3 pe-3 rounded-full" >Tema:</a>
                                    <p class="p-4 ps-2 mb-2">
                                        <a>{{ studentDetailsStore.selectedStudent.title }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="col-span-2 h-full flex flex-col">
                        <div class="grid grid-cols-1 b p-4 flex-grow bg-white border-2 border-stone-300 ">
                            <div class="p-4 bg-gray-100 border-b border-stone-300">
                                <h3 class="text-lg font-bold">Header</h3>
                            </div>
                            <div  class="flex-grow overflow-y-auto p-4">
                                <p class="text-sm text-gray-700">
                                    Aquí puedes colocar el contenido que necesita scroll. Agrega suficiente texto o contenido
                                    para ver el efecto del scrollbar.
                                </p>
                                <p class="text-sm text-gray-700">...</p>
                            </div>
                            <div class="p-4 bg-gray-100 border-t border-stone-300">
                                <p class="text-sm text-gray-600">Footer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>
<style >
.btn_error{
    background-color: red;
}
.btn_suceess{
    background-color: green;
}
.btn_pending{
    background-color: rgb(174, 212, 198);
}
</style>