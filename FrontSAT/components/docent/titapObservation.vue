<script setup>
import { request } from '~/stores/request/request';
import { StudentDetails } from '~/stores/details/studentDetails';
import { observation } from '~/stores/observation/observation';
const studentDetailsStore = StudentDetails();
const observationStore = observation();
const requestStore = request();
const isModalOpen = ref(false);
const id_studend = ref(null);
onMounted(async () => {
    id_studend.value = studentDetailsStore.selectedStudent.id;
});
defineProps({
    requirement_p: {
        type: String,
        required: true,
    },
});
const form = ref({
    title:null,
    content:'<h3>....</h3>',
})
function sendRequest(data){
    console.log(data);
    console.log(id_studend.value);
    console.log(form.value.content);
    observationStore.sendObservation(id_studend.value,data,form.value.content);
}
const openModal = (data) => {
    console.log('Opening modal for observation:',data);
    const modal = document.getElementById(`modal_observationREQ_${data}`);
    if (modal) modal.showModal();
};
const closeModal = (data) => {
  const modal = document.getElementById(`modal_observationREQ_${data}`);
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
            <h3 class="text-lg font-bold text-center">Envio de observation</h3>
            <div class="grid grid-rows-1 grid-flow-col gap-4">
                <div>
                    <form @submit.prevent="sendRequest(requirement_p)">
                        <TiptapEditor v-model="form.content" :isModalOpen="isModalOpen" ></TiptapEditor>
                    </form>
                </div>
            </div>
            <div class="modal-action">
                    <div>
                        <form method="dialog">
                            <button @click="closeModal"  class="btn">Close</button>
                        </form>
                    </div>
            </div>
        </div>
        </dialog>

    </ClientOnly>

</template>
<style>
</style>