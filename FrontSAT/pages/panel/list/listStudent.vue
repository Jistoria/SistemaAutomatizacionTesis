<script setup>
import { panel } from '~/stores/panel/panel';
const panelStore = panel();
const studentlist= ref(null);
const { openAnimation, closeAnimation } = inject('requestAnimation');


onMounted(async () => {
    const response = await panelStore.getlistStudents();
    studentlist.value = panelStore.stuendent_data;
    console.log(response);
    //studentlist.value = response;
});


const chageDatacolor = (data)=>{
    if(data == 'Habilitado'){
        return  'bg-success p-2 rounded-lg' 
    }else{
        return  'bg-neutral p-2 rounded-lg' 
    }
}
const details_student = (data)=>{
    //implementar fase de carga
    openAnimation('spinner');
    console.log(data);
    panelStore.detailStudent(data);
    closeAnimation();


}


</script>
<template>
    <div class="container mx-auto  mt-10">
        <div v-for="dataList in studentlist" class="bg-gray-300  rounded-lg grid grid-cols-3 gap-4 items-center p-4 mt-2 mb-2">
            <div class=" col-span-2 bg-white ms-2 rounded-lg">
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
                        <span>{{dataList.state_now}}</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <!-- estado del usuario -->
                        <span :class="chageDatacolor('habilitado')" >{{dataList.state_now}}</span>
                    </div>
                </div>
                <div class="p-6 italic">
                    <!-- descripcion -->
                    {{ dataList.title }}
                </div>
            </div>
            <div class=" justify-center p-1 ">
                <div >
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="text-center italic">Documentos:</div>
                            <div class="grid grid-cols-3 text-center p-3">
                                <div >
                                    <i class="bi bi-hourglass-bottom"></i>
                                    <a class="ms-2">2</a>     
                                    
                                </div>
                                <div>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <a class="ms-2">3</a>     
                                </div>
                                <div >
                                    <i class="bi bi-x-circle-fill"></i>
                                    <a class="ms-2">5</a>     
                                </div>
                            </div>
                        </div>
                        <div class="d-flex  ">
                            <div class="justify-self-center p-4">
                                <button @click="details_student(dataList.student_id)" class="btn btn-neutro">
                                    <i class="bi bi-info"></i>Ver mas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>

</style>