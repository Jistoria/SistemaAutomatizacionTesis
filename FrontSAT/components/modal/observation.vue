<script setup>
import { computed, ref } from 'vue';
import { roles } from '~/composables/roles';
import { auth } from '~/stores/auth/auth';
import { observation } from '~/stores/observation/observation';
const showdata = ref(false)
const observationStore = observation();
const observationlist = ref(null)
const authStore = auth()
const rolSelect = ref('')


const showdatafunction = (data)=>{
    console.log(data)
    showdata.value = !showdata.value
}
const deleteObservation = (data)=>{
    console.log(data)
}   
const editObservation = (data)=>{
    console.log(data)
}

onMounted(async ()=>{
    const response = await observationStore.getObservation()
    console.log(observationStore.observationdata);
    rolSelect.value = authStore.role[0]
    console.log(rolSelect.value);
    observationlist.value = observationStore.observationdata
    observationlist.value.forEach(observation => {
        showFullText.value[observation.id] = false;
    });
})


const showFullText = ref({}); 

const toggleText = (id) => {
  showFullText.value[id] = !showFullText.value[id];
};
const getTruncatedText = (content, id, maxChars = 100) => {
  if (showFullText.value[id]) return content;
  return content.length > maxChars ? content.substring(0, maxChars) + '...' : content;
};

</script>
<template>

    <button class="btn" onclick="modal_observation.showModal()">
        <i class="bi bi-newspaper icon_size"></i>
        Obervaciones
    </button>
    <dialog id="modal_observation" class="modal">
        <!-- w-11/12 max-w-5xl -->
    <div class="modal-box w-11/12 max-w-5xl bg-neutral ">
        <div class="flex justify-between">
            <h3 class="text-lg font-bold">Observaciones</h3>
            <button class="btn btn-primary" v-if="rolSelect == roles.rol4">
                <i class="bi bi-bookmark-plus-fill icon_size_2"></i>
            </button>
        </div>
        <div v-for="lista in observationlist">
            <div >
                <div class="btn btn-ghost font-bold  hover:bg-transparent "  @click="toggleText(lista.id)" >
                    <i class="bi bi-caret-down-fill "  style="font-size: 1.2rem;" ></i>
                    Observacion # {{ lista.numobservation }}
                </div>
                <div class="inline-flex " v-if="rolSelect == roles.rol4">
                    <div >
                        <button class="btn bg-primary">
                            <i class="bi bi-trash-fill icon_size_2"></i>
                        </button>
                        <button class="btn bg-primary-neutral ms-2">
                            <i class="bi bi-pencil-square icon_size_2"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="container rounded bg-white  m-3 p-3" >
                <div >
                    <div class=" inline-block	">
                        <i class="bi bi-person-fill icon_size bg-neutral rounded-2xl p-1"></i>
                    </div>
                    <div class="inline-block " >
                        <div>
                            <a class="p-3 font-bold">
                                {{ lista.username }}
                            </a>
                        </div>
                    </div>
                    <div class="inline-block " v-if="showdata">
                            <a class="sm:p-5 italic"  >
                                {{ lista.date }}
                            </a>
                    </div>
                    <div class="sm:p-3 " :class="{'inline-block italic': !showdata, 'block ': showdata}"  >
                        {{ getTruncatedText(lista.content, lista.id) }}

                    </div>
                </div>
            </div>
        </div>

        <div class="modal-action">
        <form method="dialog">
            <!-- if there is a button, it will close the modal -->
            <button class="btn">Close</button>
        </form>
        </div>
    </div>
    </dialog>
    
</template>
<style>

</style>