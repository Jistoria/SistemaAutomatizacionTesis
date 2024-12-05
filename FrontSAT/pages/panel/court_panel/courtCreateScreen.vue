<script setup>
import Calendar from '~/components/general/calendar.vue';
import { courtSet } from '~/composables/courtSet';

const onDataSaved = async (data) => {
    //console.log('rango desde el CourtCreateScreen',data);
};
</script>
<template>
    <div class="mx-auto px-4 py-12">
        <div class="grid grid-cols-2 grid-flow-col py-2">
            <div class=" p-4 ">
                <div class="p-4 bg-teal-600 rounded ">
                    <div class="p-4" >
                        <div>
                            <div class="flex justify-between">
                                <div class="p-4"> 
                                        <div class="mb-3">
                                            <a class="pt-3 ">
                                                Nombre
                                            </a>
                                        </div>
                                    <div class="flex p-3 bg-neutral rounded">
                                            <a class="ms-0"> Es individual o en grupo?</a>
                                            <div class="ms-3">
                                                        <i class="bi bi-record-circle-fill icon_size_2"></i>
                                                        <i class="bi bi-circle-fill icon_size_2"></i>
                                            </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-end">
                                        <div>
                                            <a class="ms-0">Tutor</a>
                                            <div class="pt-3">
                                                <div class="flex justify-between p-4 bg-neutral rounded">
                                                    <a class="me-2">
                                                        Nombre del tutor
                                                    </a>   
                                                    <div>
                                                        <i class="bi bi-person-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div>
                                        <a class="ms-3">Fecha</a>
                                        <div class="ps-3 pt-3">
                                            <div class="flex justify-between p-4 bg-neutral rounded">
                                                <a class="me-2">
                                                    fecha
                                                </a>   
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex ps-3 pt-3" >
                                <div class="bg-neutral rounded ms-2" v-for="user in 2">
                                            <div class="flex p-3">
                                                <a class="me-2">
                                                    Nombre del estudiante
                                                </a> 
                                                <div >
                                                    <i class="bi bi-person-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3">
                                        <a>
                                                <SelectCourt :actions="courtSet.student.name" :icon="courtSet.plus" ></SelectCourt>
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inline-flex ps-8">
                        <div class="p-2 bg-neutral mt-4 rounded">
                            Categoria
                        </div>
                        <div class="p-2 bg-neutral mt-4 ms-3 rounded">
                            Periodo
                        </div>
                    </div>
                    <div class="mt-4 ps-8">
                        <div class="bg-neutral p-4 rounded">
                            Tema de tesis
                        </div>
                    </div>
                    <!-- Jurado -->
                    <div class="p-4 mt-3 rounded">
                        <h1>Jurado:</h1>
                        <div>
                            <!-- secretaria -->
                            <div class="ps-4  rounded ">
                                <div class="pt-4 pb-4">
                                    <a>Secretaria: </a>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div class="col-span-2">
                                        <div class="flex">
                                            <i class="bi bi-circle-fill pt-4 me-3"></i>
                                            <div class="p-3 bg-neutral me-3 rounded">
                                                Secretaria
                                            </div>
                                            <div class="p-3 bg-neutral rounded">
                                                Correo
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex mt-4 justify-end">
                                            <div class="me-3" >
                                                <i class="bi bi-check-circle-fill icon_size_2"></i>
                                                <i class="bi bi-x-circle-fill icon_size_2"></i>
                                            </div>
                                            <div>
                                                <!-- componete reusable debe pedir el icono necesario -->
                                                 <SelectCourt :actions="courtSet.Secretary.name" :icon="courtSet.Secretary.icon" ></SelectCourt>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- jueces -->
                            <div class="ps-4 pt-0 rounded">
                                <div class="pt-4 pb-4">
                                    <a>Jueces: </a>
                                </div>
                                <!-- deben ser minimo 3 -->
                                <div class="grid grid-cols-3">
                                    <div class="col-span-2">
                                        <div class="flex">
                                            <i class="bi bi-circle-fill pt-4 me-3"></i>
                                            <div class="p-3 bg-neutral me-3 rounded">
                                                Juez
                                            </div>
                                            <div class="p-3 bg-neutral rounded">
                                                Correo
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex mt-4 justify-end">
                                            <div class="me-3" >
                                                <i class="bi bi-check-circle-fill icon_size_2"></i>
                                                <i class="bi bi-x-circle-fill icon_size_2"></i>
                                            </div>
                                            <div>
                                                <!-- componente un modal -->
                                                <SelectCourt :actions="courtSet.Juez.name" :icon="courtSet.Juez.icon" tutor="Soy un tutor de prueba no lo olvides" ></SelectCourt>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <div>
                                    <div class="p-4 bg-neutral mt-4 rounded">
                                        <div class="flex justify-between">
                                            <div class="italic">
                                                Anadir mas jueces
                                            </div>
                                            <div>
                                                <SelectCourt :actions="courtSet.Tutor.name" :icon="courtSet.plus" ></SelectCourt>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- invitados -->
                    <div class="p-4">
                        <a >
                            Invitados
                        </a>
                        <div class="mt-4">
                            <div class="p-4 bg-neutral rounded">
                                Nombre del Invitado
                            </div>
                            <div>
                                    <div class="p-4 bg-neutral mt-4 rounded">
                                        <div class="flex justify-between">
                                            <div class="italic">
                                                Anadir mas Invitados
                                            </div>
                                            <div>
                                                <SelectCourt :actions="courtSet.Invited.name" :icon="courtSet.plus" ></SelectCourt>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 " v-if="true">
                        <div class="bg-success p-4 rounded text-center" :class="{'opacity-50':true}">
                            <a >
                                Asignar Tribunal 
                                <i class="bi bi-check-circle-fill ms-2"></i>
                                <i class="bi bi-x-circle-fill ms-2"></i>
                            </a >
                        </div>
                    </div>
                </div>
            </div>
            <div >
                <Calendar @data-saved="onDataSaved"></Calendar>
            </div>
        </div> 

    </div>   

</template>

<style>
</style>