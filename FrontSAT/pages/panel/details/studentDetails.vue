<script setup>
import Observation from '~/components/modal/observation.vue';
import { StudentDetails } from '~/stores/details/studentDetails';
import { panel } from '~/stores/panel/panel';

const base_url = import.meta.env.VITE_API_STORAGE;
const studentDetailsStore = StudentDetails();
const requerimets_details = ref(null);
const requeriment_selected = ref(null);
const panelStore = panel();

//para la carga
const isLoading = ref(false);


onMounted(async () => {
    requerimets_details.value = studentDetailsStore.RequerimentsSelected;
    
});


const selectedStatus = ref(null);
const filterRequerimentsByStatus = (status) => {
  selectedStatus.value = status;
};
const filteredRequeriments = computed(() =>
  selectedStatus.value
    ? requerimets_details.value.filter((req) => req.status === selectedStatus.value)
    : requerimets_details.value
);
const change_status = async (id_student, id_requeriment_student,status)=>{
    console.log(status);
    isLoading.value = true;
    const response = await studentDetailsStore.changeStudentreq(id_student, id_requeriment_student,status);  
    //necesito logica de cuando que si un 400 no hacer nada y dejarlo en isLoading.value=false
    if(response == true){
        const requerimentIndex = studentDetailsStore.RequerimentsSelected.findIndex((req) => req.student_requirements_id === id_requeriment_student);
        if(requerimentIndex !== -1){
            studentDetailsStore.RequerimentsSelected[requerimentIndex].status = status;
            panelStore.isLoaded = false;
            panelStore.getlistStudents(); 
        }
    }else{
        console.error('Error al cambiar el estado en el backend.');
    }
    selectedStatus.value = status;
    isLoading.value = false;

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

</script>
<template>
<div>

    <div class="container  mx-auto mt-10 hinde">
        <div class="bg-neutral p-4 rounded" >
            <!-- columna de datos personales -->
            <div class="grid grid-cols-4 p-4">
                <div class="col-span-3">
                    <div class="mt-2 mb-4">
                        <a class="bg-error p-2 rounded-l-lg ">
                            {{ studentDetailsStore.selectedStudent.name }}
                        </a>
                        <a class="btn btn-info">
                                {{ studentDetailsStore.selectedStudent.phase_state_now}}
                        </a>
                    </div>
                    <div class="ps-3">
                        <div >
                            <a >{{ studentDetailsStore.selectedStudent.email }}</a>
                        </div>
                    </div>
                </div>
                <div>
                </div>
            </div>
            <!-- columna de los datos de la tesis -->
             <div class="grid grid-cols-1 p-2">
                <div class="ps-4" >
                    <div >
                        <a >{{ studentDetailsStore.selectedStudent.title }}</a>
                    </div>
                </div> 
             </div>
            <!-- columana para todos los requerimientos -->
             <div class="pb-4 ps-4 ">
                <p class="p-3">Requerimientos:</p>
                <div class=" flex p-3 ">
                    <div class="me-2"  @click="filterRequerimentsByStatus('Aprobado')" >
                            <a class="bg-success p-2 rounded" :class="{ 'opacity-70':selectedStatus !== 'Aprobado'  }">
                                Aceptados <i class="bi bi-check-circle-fill"></i>
                            </a>
                    </div>
                    <div class="me-2" @click="filterRequerimentsByStatus('Rechazado')">
                            <a class="bg-error p-2 rounded" :class="{ 'opacity-70':selectedStatus !== 'Rechazado'  }" >
                                Rechazados <i class="bi bi-x-circle-fill"></i> 
                            </a>
                    </div>
                    <div class="me-2" @click="filterRequerimentsByStatus('Pendiente')">
                            <a class="bg-neutral p-2 rounded" :class="{ 'opacity-70':selectedStatus !== 'Pendiente'  }">
                                Pendientes <i class="bi bi-hourglass-bottom"></i>
                            </a>
                    </div>
                    <div class="me-2" @click="filterRequerimentsByStatus('Enviado')"> 
                            <a class="bg-accent p-2 rounded" :class="{ 'opacity-70':selectedStatus !== 'Enviado'  }" >
                                Enviados <i class="bi bi-envelope-fill"></i>
                            </a>
                    </div>
                </div>
                <div class="p-2 rounded-lg">
                    <div v-if="isLoading" class="bg-neutral p-4">
                        <div class="flex justify-center">
                            <div>
                                <span class="loading loading-bars loading-lg"></span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="selectedStatus">
                        <div class="bg-neutral">
                            <div v-for="req in filteredRequeriments" class="p-5">
                                <div class="grid grid-cols-3">
                                    <div class="col-span-2">
                                        <p>
                                            <a class="bg-slate-300 p-2 rounded-lg">{{ req.requirements_data }}</a>
                                            <a @click="download_file(req.url_file)" class="italic ms-3"> <i class="bi bi-file-earmark"></i> {{ req.requirement_name}}</a>
                                        </p>
                                    </div>
                                    <div class="flex justify-end">
                                        <button class="btn_error p-2 me-2 rounded" :class="{ 'hidden':selectedStatus === 'Pendiente'  }"  @click="change_status(studentDetailsStore.selectedStudent.id,req.student_requirements_id,'Rechazado')" >
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                        <button class="btn_suceess p-2 me-2 rounded" :class="{ 'hidden':selectedStatus === 'Pendiente'  }"  @click="change_status(studentDetailsStore.selectedStudent.id,req.student_requirements_id,'Aprobado')"    >
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                        <button class="btn_pending p-2 me-2 rounded" :class="{ 'hidden':selectedStatus === 'Pendiente'  }" @click="change_status(studentDetailsStore.selectedStudent.id,req.student_requirements_id,'Pendiente')" >
                                            <i class="bi bi-hourglass-bottom"></i>
                                        </button>
                                        <button>
                                            <Observation :requirementId="req.student_requirements_id" ></Observation>
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

</template>
<style scoped>
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