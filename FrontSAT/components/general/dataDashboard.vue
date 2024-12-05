<script setup>
import { defineProps } from 'vue';
import { admin } from '~/stores/dashboards/admin';
import { dashboardData } from '~/composables/dashboardData';
const adminStore = admin();
const datashow = ref(true);
const data_dashboard = ref([]);
const data_dashboard_aprobed = ref([]);
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
    
    if(props.data == 'Estudiantes'){
        const response = await adminStore.dashboardAdmin();
        
        data_dashboard.value = response.data.phasesApprovedData;
        data_dashboard_aprobed.value = response.data.phasesInProcessData;
        const totalApproved = data_dashboard.value.reduce(
        (sum, phase) => sum + phase.thesis_process_phase_count,
        0
        );
        const totalInProcess = data_dashboard_aprobed.value.reduce(
        (sum, phase) => sum + phase.thesis_process_phase_count,
        0
        );
        emit('update-total', { approved: totalApproved, inProcess: totalInProcess });
    }
})

const emit = defineEmits(['update-total']);

const phaseStyles = {
  'Fase de Planificación': { icon: 'bi bi-gear', bg: 'bg-gray-200' },
  'Fase Diseño': { icon: 'bi bi-pencil', bg: 'bg-blue-200' },
  'Fase Resultado': { icon: 'bi bi-bar-chart', bg: 'bg-green-200' },
  'Fase Evaluación': { icon: 'bi bi-check-circle', bg: 'bg-yellow-200' },
};
const filteredPhases = computed(() =>
  data_dashboard.value.filter(phase => phase.name !== 'Fase de Planificación')
);

</script>
<template>

    <div v-if="adminStore.isLoaded" class=" bg-neutral border border-slate-200 text-neutral-content p-4 mt-3">
            <div>
                <button @click="toggleDataShow" class="btn btn-ghost ">
                    <i  class="bi bi-caret-down-fill icon_size"></i>
                </button>
                <a class="font-serif text-3xl">
                    {{ props.data }}
                </a>
            </div>
            <div >
                <div  v-if="datashow">
                    <div>
                        <h1 class="p-4 ">
                            <a class="p-3 bg-primary text-white rounded-full">
                                Aprobados:
                            </a>
                        </h1>
                        <div class="grid grid-cols-4 gap-4 border-2 border-stone-300 bg-white p-4 rounded-full">
                            <div v-for="phase in data_dashboard">
                                <div>
                                    <div class="text-center" >
                                        {{ phase.name }}
                                        <i :class="[phaseStyles[phase.name]?.icon, 'text-xl me-4']"></i>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a
                                            :class="['p-2  rounded-lg shadow  items-center', phaseStyles[phase.name]?.bg || 'bg-white']"
                                        >
                                            {{ phase.thesis_process_phase_count }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="p-4" >
                            <a class="p-3 bg-primary text-white rounded-full">
                                En proceso:
                            </a>
                        </h1>
                        <div class="grid grid-cols-4 gap-4 border-2 border-stone-300 bg-white p-4 rounded-full">
                            <div 
                                v-for="phase in data_dashboard_aprobed"
                                
                            >
                                <div class="text-center" >
                                        {{ phase.name }}
                                        <i :class="[phaseStyles[phase.name]?.icon, 'text-xl me-2']"></i>
                                </div>
                                <div class="text-center mt-3">
                                    <a 
                                        :class="['p-2 rounded-lg shadow  items-center', phaseStyles[phase.name]?.bg || 'bg-white']"
                                    >
                                        {{ phase.thesis_process_phase_count }}
                                    </a>
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