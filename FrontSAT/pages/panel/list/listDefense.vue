<script setup>
    import { court } from '~/stores/court/court';
    import CourtCreateScreen from '../court_panel/courtCreateScreen.vue';
    import CourtPanelScreen from '../court_panel/courtPanelScreen.vue';
    const courtStore = court();
    const observationlistcourt = ref(null)
    onMounted(async () => {
        const response = await courtStore.getCourtList();
    
        observationlistcourt.value = courtStore.courtlist;
    });
    
    </script>
    <template>
        <div class="container mx-auto p-4 my-10" >
            <div class="container mx-auto pt-4 ">
                <h2 class="text-2xl font-bold text-center sm:text-left pb-1 ">
                    Tribunal
                </h2>
            </div>
            <div v-if="observationlistcourt && observationlistcourt.length > 0" class="container rounded-lg shadow-xl mx-auto px-4 py-3  bg-neutral border border-slate-300 mt-4 mb-4">
                <div v-for="(listCourt, index) in observationlistcourt" class="bg-gray-300 mt-4 rounded-lg grid grid-cols-5 gap-4 items-center">
                    <div class="col-span-3 text-gray-800 p-4">
                        <h2 class="font-bold uppercase text-xs leading-8 italic">
                            {{ listCourt?.observation_data?.title || "Sin título" }}
                        </h2>
                        <div class="flex items-center mt-2 space-x-4 text-gray-600 text-xs">
                            <div class="flex items-center space-x-1">
                                <i class="bi bi-person-fill icon_size_2"></i>
                                <span>
                                    {{ listCourt?.observation_data?.studentInfo?.name || "Sin nombre" }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="bi bi-calendar-fill icon_size_2 pe-2"></i>
                                <span>
                                    {{ listCourt?.observation_data?.studentInfo?.tribunalDate || "Sin fecha" }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow grid grid-cols-3 gap-2 ">
                        <div v-for="(grade, gradeIndex) in listCourt?.observation_data?.grades || []" 
                            :key="gradeIndex" 
                            class="text-center">
                            <span class="block text-gray-500 text-xs">{{ grade?.label || "Sin etiqueta" }}</span>
                            <span :class="[grade?.color || 'bg-gray-200 text-gray-800', 'block text-lg font-bold rounded p-1']">
                                {{ grade?.value || 0 }}
                            </span>
                        </div>
                    </div>
                    <div >
                        <div class="justify-self-center">
                            <div class="flex justify-center font-bold mt-2">
                                <a>Promedio:</a>
                            </div>
                            <div class="flex justify-center pb-3 pt-3 ">
                                <button>
                                    {{ listCourt?.observation_data?.average || 0 }}
                                </button>
                            </div>
                            <div class="items-center mb-3">
                                <div class="flex justify-center mb-2">
                                    <button class="btn neutral">
                                        Ver mas <i class="bi bi-info-lg"></i>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button class="btn neutral">
                                        <NuxtLink to="/panel/court_panel/courtCreateScreen"> 
                                            Asignar Tribunal
                                        </NuxtLink>
                                    </button>
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
    
    