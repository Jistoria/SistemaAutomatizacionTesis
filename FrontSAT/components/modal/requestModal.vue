<script setup>

import TiptapEditor from '../general/tiptap-editor.vue';
import { request } from '~/stores/request/request';

const requestStore = request();
const isModalOpen = ref(false);

const form = ref({
    title:null,
    content:'<h3>....</h3>',
})
function sendRequest(){
    requestStore.sendRequest(form.value.content);
    
}
function openModal() {
    
  isModalOpen.value = true;
}

function closeModal() {
    
    form.content = '<h3>....</h3>';
    
    isModalOpen.value = false;
}

</script>
<template>
    <ClientOnly>
        <button class="btn" onclick="modal_request.showModal()" @click="openModal">
            <i class="bi bi-envelope-fill icon_size"></i>
            Enviar Solicitud
        </button>
        <dialog id="modal_request" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <h3 class="text-lg font-bold text-center">Envio de solicitud</h3>
            <p class="py-2">Razon de solicitud:</p>
            <div class="grid grid-rows-1 grid-flow-col gap-4">
                <div >
                    <form @submit.prevent="sendRequest">
                        <TiptapEditor v-model="form.content" :isModalOpen="isModalOpen" ></TiptapEditor>
                    </form>
                </div>
            </div>
            <div class="modal-action">
                    <div >
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