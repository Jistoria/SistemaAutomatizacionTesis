<script setup>
import { defineProps } from 'vue';
const datashow = ref(false);
import { dashboardData } from '~/composables/dashboardData';

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
</script>
<template>
    <div class=" bg-neutral border border-slate-200 text-neutral-content p-4 mt-3">
            <div>
                <button @click="toggleDataShow" class="btn btn-ghost ">
                    <i  class="bi bi-caret-down-fill icon_size"></i>
                </button>
                <a class="font-serif text-3xl">
                    {{ sectionData.title }}
                </a>
            </div>
            <div >
                <div class="grid grid-cols-4 gap-4" v-if="datashow">
                    <div class="p-4" v-for="(dashboard, items) in sectionData.items" >
                        <div class="text-center ">
                            <i :class="`bi ${dashboard.icon} icon_size`"></i> <a>{{ dashboard.label }}</a>
                        </div>
                        <div class="text-center p-4">
                            <a class="text-info  pe-2 ps-2  rounded-md font-bold" :class="dashboard.color" >

                                {{ dashboard.value }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
</template>
<style>
</style>