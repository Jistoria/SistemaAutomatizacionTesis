<script setup>
import { request } from '~/stores/request/request';
import { StudentDetails } from '~/stores/details/studentDetails';
import { observation } from '~/stores/observation/observation';
const { openAnimation, closeAnimation } = inject('requestAnimation');
import { sweetAlert } from '~/composables/sweetAlert';
const swal = sweetAlert();
const studentDetailsStore = StudentDetails();
const observationStore = observation();
const requestStore = request();
const isModalOpen = ref(false);
const id_studend = ref(null);

const isLoading_mo = ref(false);
const sendStatus = ref(null);

onMounted(async () => {
    id_studend.value = studentDetailsStore.selectedStudent.id;
});
defineProps({
    requirement_p: {
        type: String,
        required: true,
    },
});
const emit = defineEmits(['observation-sent'])

const form = ref({
    title:null,
    content:'<h3>....</h3>',
})
const sendRequest = async(data)=>{
    isLoading_mo.value = true;
    console.log(data);
    console.log(id_studend.value);
    console.log(form.value.content);
    const response = await observationStore.sendObservation(id_studend.value,data,form.value.content);
    if(response == true){
        emit('observation-sent', data);
        sendStatus.value = 'success';
        isLoading_mo.value = false;
    }else{
        sendStatus.value = 'error'; 
        isLoading_mo.value = false;

    }
    //sweet alert de carga 
    //cerrar modal y ver como limpiar esos datos del envio de obserbtion
    form.value.content = '<h3>....</h3>';
}
const openModal = (data) => {
    console.log('Opening modal for observation:',data);
    const modal = document.getElementById(`modal_observationREQ_${data}`);
    if (modal) modal.showModal();
};
const closeModal = (data) => {
  const modal = document.getElementById(`modal_observationREQ_${data}`);
  isLoading_mo.value = false;
  sendStatus.value = null; 
  if (modal) modal.close();
};


</script>
<template>
    <ClientOnly>
        <button class="btn"  @click="openModal(requirement_p)">
            <i class="bi bi-envelope-fill icon_size"></i>
            Enviar Observacion
        </button>
        <dialog :id="`modal_observationREQ_${requirement_p}`" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <h3 class="text-lg font-bold text-center">Envio de observación</h3>
            <div class="grid grid-rows-1 grid-flow-col gap-4">
                <div v-if="isLoading_mo">
                    <span class="loading loading-bars loading-lg mt-4"></span>
                </div>
                <div v-else-if="sendStatus === 'success'" class="mt-4 text-center text-green-500">
                        <i class="bi bi-check-circle-fill text-2xl"></i>
                        <p>¡Observación enviada exitosamente!</p>
                </div>
                <div v-else-if="sendStatus === 'error'" class="mt-4 text-center text-red-500">
                        <i class="bi bi-x-circle-fill text-2xl"></i>
                        <p>Error al enviar la observación. Intenta de nuevo.</p>
                </div>
                <div v-else>
                    <form @submit.prevent="sendRequest(requirement_p)">
                        <TiptapEditor v-model="form.content" :isModalOpen="isModalOpen" ></TiptapEditor>
                    </form>
                </div>
            </div>
            <div class="modal-action">
                    <div>
                        <form method="dialog">
                            <button @click="closeModal"  class="btn">Cerrar</button>
                        </form>
                    </div>
            </div>
        </div>
        </dialog>

    </ClientOnly>

</template>
<style>
</style>