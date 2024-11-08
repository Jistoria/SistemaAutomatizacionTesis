<script setup>

import TiptapEditor from '../general/tiptap-editor.vue';

const form = ref({
    title:null,
    content:'<h3>....</h3>',
})
function sendRequest(){
    //peticion de enviar documento
    console.log(form.value.content);
}
</script>
<template>
    <ClientOnly>
        <button class="btn" onclick="my_modal_4.showModal()">open modal</button>
        <dialog id="my_modal_4" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <h3 class="text-lg font-bold text-center">Envio de solicitud</h3>
            <p class="py-2">Razon de solicitud:</p>
            <div class="grid grid-rows-1 grid-flow-col gap-4">
                <div >
                    <form @submit.prevent="sendRequest">
                        <TiptapEditor v-model="form.content" ></TiptapEditor>
                    </form>
                </div>
            </div>
            <div class="modal-action">
                    <div >
                        <form method="dialog">
                            <button class="btn">Close</button>
                        </form>
                    </div>
            </div>
        </div>
        </dialog>

    </ClientOnly>
</template>
<style>
.border_r{
    border-right: 5px solid red;
}
</style>