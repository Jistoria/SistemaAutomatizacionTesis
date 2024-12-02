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
    <div class="hidden">
        <div class="container mx-4 border_y">
            <NuxtLink to="/panel/court_panel/courtCreateScreen"> Asignar Tribunal</NuxtLink>
        </div>
        <div class="container rounded-lg shadow-xl mx-auto px-4 py-4  bg-neutral border border-slate-300">
            <div v-for="listCourt in observationlistcourt" class="bg-gray-300 mt-4 rounded-lg grid grid-cols-5 gap-4 items-center">
                <div class="col-span-3 text-gray-800 p-4">
                    <h2 class="font-bold uppercase text-xs leading-8 italic">
                        {{ listCourt.title }}
                    </h2>
                    <div class="flex items-center mt-2 space-x-4 text-gray-600 text-xs">
                        <div class="flex items-center space-x-1">
                            <i class="bi bi-person-fill icon_size_2"></i>
                            <span>
                                {{ listCourt.studentInfo.name }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="bi bi-calendar-fill icon_size_2 pe-2"></i>
                            <span>{{ listCourt.studentInfo.tribunalDate }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-lg shadow grid grid-cols-3 gap-2 ">
                    <div class="text-center">
                        <span class="block text-gray-500 text-xs">{{ listCourt.grades[0].label }}</span>
                        <span class="block text-lg font-bold bg-green-200 text-green-800 rounded p-1">{{ listCourt.grades[0].value }}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-gray-500 text-xs">{{ listCourt.grades[1].label }}</span>
                        <span class="block text-lg font-bold bg-pink-200 text-pink-800 rounded p-1">{{ listCourt.grades[1].value }}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-gray-500 text-xs">{{ listCourt.grades[2].label }}</span>
                        <span class="block text-lg font-bold bg-red-200 text-red-800 rounded p-1">{{ listCourt.grades[2].value }}</span>
                    </div>
                </div>
                <div >
                    <div class="justify-self-center">
                        <div class="flex justify-center font-bold mt-2">
                            <a>Promedio:</a>
                        </div>
                        <div class="flex justify-center pb-3 pt-3 ">
                            <button>
                                {{ listCourt.average }}
                            </button>
                        </div>
                        <div class="flex justify-center mb-3">
                            <button class="btn neutral">
                                Ver mas <i class="bi bi-info-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <CourtPanelScreen></CourtPanelScreen>
    </div>
</template>
<style>

</style>

