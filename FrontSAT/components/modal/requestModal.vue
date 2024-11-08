<script setup>

import TiptapEditor from '../general/tiptap-editor.vue';

const form = ref({
    title:null,
    content:'<h1>hola esto es un emit prop</h1>',
})
function sendRequest(){
    console.log(form.value.content);
}
</script>
<template>
    <ClientOnly>
        <form @submit.prevent="sendRequest">

            <TiptapEditor v-model="form.content" ></TiptapEditor>
        </form>
    </ClientOnly>
</template>
<style>

</style>