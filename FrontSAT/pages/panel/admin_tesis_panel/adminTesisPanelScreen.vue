<script setup>
import studentAdmin from '~/components/admin/studentAdmin.vue';
import docenteAdmin from '~/components/admin/docenteAdmin.vue';
import procesosAdmin from '~/components/admin/procesosAdmin.vue';

</script>
<template>
    <div class="container mx-auto mt-4">
        <div class=" grid grid-rows-2 grid-flow-col gap-4 p-3">
            <div>
                <UploadFiles></UploadFiles>
            </div>
            <div>
                contenido del dashboard
            </div>
        </div>
        <!-- <studentAdmin></studentAdmin> -->
        <!-- <docenteAdmin></docenteAdmin> -->
         <procesosAdmin></procesosAdmin>
    </div>
</template>
<style>
</style>