<script setup>
import { defineProps } from 'vue';
import { admin } from '~/stores/dashboards/admin';
import { dashboardData } from '~/composables/dashboardData';
const adminStore = admin();
const datashow = ref(true);
const data_dashboard = ref([]);

const props = defineProps({
    data:{
        type: String,
        required: true,
    }
})
const toggleDataShow = () =>{
    datashow.value = !datashow.value;
}
const sectionData = computed(() => {
    return dashboardData.value[props.data] || { title: 'Sin datos', items: [] };
});;  
onMounted(async()=>{
    console.log(props.data);
    if(props.data == 'Estudiantes'){
        const response = await adminStore.dashboardAdmin();
        console.log(response.data);
        data_dashboard.value = response.data;
    }
})

</script>
<template>
    <div class=" bg-neutral border border-slate-200 text-neutral-content p-4 mt-3">
            <div>
                <button @click="toggleDataShow" class="btn btn-ghost ">
                    <i  class="bi bi-caret-down-fill icon_size"></i>
                </button>
                <a class="font-serif text-3xl">
                    {{ props.data }}
                </a>
            </div>
            <div >
                <div class="grid grid-cols-4 gap-4" v-if="datashow">
                    <div v-for="dashboard in data_dashboard.phasesInProcessData"  >
                        {{ dashboard }}
                    </div>
                    <div v-for="dashboard in data_dashboard.phasesApprovedData" >
                        {{ dashboard }}
                    </div>
                </div>
            </div>
        </div>    
</template>
<style>
</style>