<script setup>
import { computed, ref,defineProps } from 'vue';
import { roles } from '~/composables/roles';
import { auth } from '~/stores/auth/auth';
import { observation } from '~/stores/observation/observation';
import { StudentDetails } from '~/stores/details/studentDetails';
import RequestModal from './requestModal.vue';
const showdata = ref(false)
const observationStore = observation();
const observationlist = ref(null)
const authStore = auth();
const studentDetailsStore = StudentDetails();
const rolSelect = ref('');
const id_studend_ob = ref(null);


const showdatafunction = (data)=>{
    console.log(data)
    showdata.value = !showdata.value
}
const deleteObservation = (data)=>{
    console.log(data)
}   
const editObservation = (data)=>{
    console.log(data)
}
defineProps({
  requirementId: {
    type: String, 
    required: true, 
  },
});

onMounted(async ()=>{
    rolSelect.value = authStore.role[0]
    id_studend_ob.value = studentDetailsStore.selectedStudent.id;

    // const response = await observationStore.getObservation()
    // observationlist.value = observationStore.observationdata
    // observationlist.value.forEach(observation => {
    //     showFullText.value[observation.id] = false;
    // });
})
const showFullText = ref({}); 
const toggleText = (id) => {
  showFullText.value[id] = !showFullText.value[id];
};
const getTruncatedText = (content, id, maxChars = 100) => {
  if (showFullText.value[id]) return content;
  return content.length > maxChars ? content.substring(0, maxChars) + '...' : content;
};
//sistema del modal
const openModal = async(data) => {
    console.log('Opening modal for requirementId:',data);
    const modal = document.getElementById(`modal_observation_${data}`);
    if (modal) modal.showModal();
    await fecth_details(data);


};
const fecth_details = async (data) => {
    const response = await observationStore.getObservation(id_studend_ob.value,data);
    observationlist.value = Array.isArray(observationStore.observationdata)
    ? observationStore.observationdata
    : [...observationStore.observationdata];
    console.log(observationStore.observationdata);

    observationlist.value.forEach((observation) => {
        if (observation && observation.observation_requirement_id) {
            showFullText.value[observation.observation_requirement_id] = false;
        }
    });
    console.log('hola');
    console.log(observationlist.value);
};
const closeModal = (data) => {
  const modal = document.getElementById(`modal_observation_${data}`);
  if (modal) modal.close();
};
const delete_observation = async (data,requirementId)=>{
    const response = await observationStore.deleteObservation(id_studend_ob.value,data);
    await fecth_details(requirementId);
    
}

</script>
<template>
    
    <button class="btn p-2 me-2 rounded" @click="openModal(requirementId)">
        <i class="bi bi-newspaper icon_size_2"></i>
    </button>
    <dialog :id="`modal_observation_${requirementId}`" class="modal">
        <!-- w-11/12 max-w-5xl -->
    <div class="modal-box w-11/12 max-w-5xl bg-neutral ">
        <div class="flex justify-between">
            <h3 class="text-lg font-bold">Observaciones</h3>
            <button  v-if="rolSelect == roles.rol4">
                <TitapObservation :requirement_p="requirementId" ></TitapObservation>
            </button>
        </div>
        <div v-for="(lista,index) in observationlist">
            <div class="flex">
                <div class="btn btn-ghost font-bold  hover:bg-transparent "  @click="toggleText(lista.observation_requirement_id)" >
                    <i class="bi bi-caret-down-fill "  style="font-size: 1.2rem;" ></i>
                    Observacion # {{ index }}
                    
                </div>
                <div class="bg-error rounded" @click="delete_observation(lista.observation_requirement_id,requirementId)">
                    <div class="p-3">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                </div>
            </div>
            <div class="container rounded bg-white  m-3 p-3 " >
                <div>
                    <div class="inline-block">
                        <i class="bi bi-person-fill icon_size bg-neutral rounded-2xl p-1"></i>
                    </div>
                </div>
                <div class="inline-block" >
                    <a class="sm:p-5 italic"  >
                        {{ lista.formatted_created_at }}
                    </a>
                </div>
                <div class="sm:p-3 " :class="{'inline-block italic': !showdata, 'block ': showdata}"  >
                    <div class="ms-3" v-html="lista.comment">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-action">
        <form method="dialog">
            <!-- if there is a button, it will close the modal -->
            <button  class="btn" @click="closeModal(requirementId)">Close</button>
        </form>
        </div>
    </div>
    </dialog>
    
</template>
<style>

</style>