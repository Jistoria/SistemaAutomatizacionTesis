<script setup>
import { panel } from '~/stores/panel/panel';
const panelStore = panel();
const studentlist= ref(null)
onMounted(async () => {
    const response = await panelStore.getlistStudents();
    studentlist.value = panelStore.stuendent_data;
});
const chageDatacolor = (data)=>{
    if(data == 'Habilitado'){
        return  'bg-success p-2 rounded-lg' 
    }else{
        return  'bg-neutral p-2 rounded-lg' 
    }
}

</script>
<template>
    <div class="container mx-auto  mt-10">
        <div v-for="dataList in studentlist" class="bg-gray-300  rounded-lg grid grid-cols-3 gap-4 items-center p-4 mt-2 mb-2">
            <div class=" col-span-2 bg-white ms-2 rounded-lg">
                <div class="flex items-center mt-5 space-x-4 text-gray-600 text-xs ps-6 pt-3 ">
                    <div class="flex items-center space-x-1 ">
                        <i class="bi bi-person-fill icon_size"></i>
                        <span>
                            {{ dataList.user.name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <i class="bi bi-calendar-fill icon_size pe-2"></i>
                        <span>{{ dataList.user.phase }}</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        
                        <span :class="chageDatacolor(dataList.user.status)" >{{ dataList.user.status }}</span>
                    </div>
                </div>
                <div class="p-6 italic">
                    {{ dataList.description}}
                </div>
            </div>
            <div class=" justify-center p-1 hidden">
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <div class="text-center border border-gray-900">
                            <a>Diseño</a>
                        </div>
                        <div >
                            <div class="overflow-x-auto ">
                                <table class="table">
                                    <!-- head -->
                                    <thead >
                                    <tr class="text-center border bg-gray-400 border-gray-900">
                                        <th>Parcial 1</th>
                                        <th>Parcial 2</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- row 1 -->
                                    <tr class="text-center border border-gray-900">
                                        <td>{{ dataList.evaluations[0].scores[0].score }}</td>
                                        <td>{{ dataList.evaluations[0].scores[1].score }}</td>
                                    </tr>
                                   </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-center border border-gray-900">
                            <a>Resultado</a>
                        </div>
                        <div >
                            <div class="overflow-x-auto ">
                                <table class="table">
                                    <!-- head -->
                                    <thead >
                                    <tr class="text-center border bg-gray-400 border-gray-900">
                                        <th>Parcial 1</th>
                                        <th>Parcial 2</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- row 1 -->
                                    <tr class="text-center border border-gray-900">
                                        <td>{{ dataList.evaluations[1].scores[0].score }}</td>
                                        <td>{{ dataList.evaluations[1].scores[1].score }}</td>
                                    </tr>
                                   </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div >
                    <div class=" text-center ">
                        <a class="bg-white rounded  p-2">
                            Ver detalles <i class="bi bi-info-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>

</style>