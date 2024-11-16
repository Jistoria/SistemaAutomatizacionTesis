<script setup>
import { StudentDetails } from '~/stores/details/studentDetails';
const studentDetailsStore = StudentDetails();
const requerimets_details = ref(null);
onMounted(async () => {
    requerimets_details.value = studentDetailsStore.RequerimentsSelected;
});

</script>
<template>
<div>
     {{ studentDetailsStore.selectedStudent }}
     {{ requerimets_details }}
    <div>prueba</div>
</div>

</template>
<style>

</style>