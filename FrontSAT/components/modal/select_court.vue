<script setup>
import { courtSet } from '~/composables/courtSet';
defineProps({
    actions:{
        type: String,
        required: true
    },
    icon:{
        type: String,
        default: 'bi bi-person-fill'
    },
    //esto es para enviar al tutor y cuando deba buscar a los otros jueces
    tutor:{
        type: String,
        default: 'Nombre del tutor'
    }

})
const openModal_getdata = (data) => {
    
    const modal = document.getElementById(`modal_court_${data}`);
    get_data(data);
    if (modal) modal.showModal();
}
const closeModal = (data) => {
  const modal = document.getElementById(`modal_court_${data}`);
  if (modal) modal.close();
};
//
const setperson = (data)=>{
    //peticion para cambiar el estado de la persona a eligida y no eligida para ese tribunal
    console.log('setting person:',data);
}
const get_data = async(data)=>{
    //aqui debo hacer un await de un dato que me envie para seteart los datos respectivos
    //tanto para el para los jueces como para los secretarios

    console.log('getting data:',data);
}
</script>
<template>
    <i class="cursor-pointer" @click="openModal_getdata(actions)" >
        <i  :class="icon" ></i>
    </i>
    <dialog :id="`modal_court_${actions}`" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">

            <h3 class="text-lg font-bold">Seleccionar {{ actions }}(s)</h3>
            <div class="flex " :class="{ 'justify-between':courtSet.Juez.name }" >
                <div class="flex pb-3 pt-4" v-if="actions === courtSet.Juez.name">
                    <h1>Tutor del alumnado:</h1>
                    <p class="ms-3 italic">{{ tutor }}</p>
                </div>
                <div class="grow flex justify-end" >
                    <button class="btn btn-success">
                        Guardar cambios
                    </button>
                </div>
            </div>
            <div class="overflow-y-auto grow " style="max-height: calc(80vh - 120px);" >
                    <div v-for="data in 10" class="p-4 bg-neutral mt-4">
                        <!-- debe haber dos estados -->
                        <div class="mt-3 bg-info rounded">
                            <div class="flex p-3 ">
                                <i class="bi bi-person-circle icon_size_2"></i>
                                <a class="ms-3"> nombre de todos los tutores o secretaria</a>
                                <div @click="setperson(actions)" class="grow flex justify-end">
                                    <i class="bi bi-record-circle-fill icon_size_2"></i>
                                    <i class="bi bi-circle-fill icon_size_2"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <div class="modal-action">
            <form method="dialog">
                <!-- if there is a button, it will close the modal -->
                <button @click="closeModal(actions)" class="btn">Close</button>
            </form>
            </div>
        </div>

    </dialog>
    
</template>
<style>

</style>