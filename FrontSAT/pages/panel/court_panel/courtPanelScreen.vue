<script setup>
const data_place = ref(
    [
        {
            student:'FLORES CEDEÑO WILLIAM ANDRES',
            status:'pendiente',
            email:'e1316612603@live.uleam.edu.ec',
            Carrera:'TI',
            tutor:'ING ARMANDO FRANCO',
            modal:'Proyecto Integrador',
        }
    ],
)
const openModal= async(data)=>{
    const modal = document.getElementById(`modal_Prerequeriments_${data}`);
    if (modal) modal.showModal()
}
const closeModal= (data)=>{
    const modal = document.getElementById(`modal_Prerequeriments_${data}`);
    if (modal) modal.close();
}
</script>
<template>

    <!-- Para Tribunales -->
    <div class="container  mx-auto p-4 hidden">
        <div class="p-4 bg-neutral rounded">
            <div>
                 <div class="flex p-4" >
                    <div class="bg-white p-4">
                        <a>Fecha y hora: 19-08-2024 8:30</a>
                    </div>
                    <div class="bg-white p-4 ms-3">
                        <a>Aula: 202</a>
                    </div>
                 </div>   
            </div>
            <div class="grid grid-cols-4 gap-4 ">
                <div class="p-4">
                    <a>Tema:</a>
                    <div class="p-4 rounded bg-white">
                        <a>DASHBOARD DE ANALISIS DE INDICADORES DE DESEMPEÑO PARA LA GESTIÒN DE SRVICIOS DEL TALLER 
                            BROTHERSMOTORSMANTA</a>
                    </div>
                </div>
                <div class="p-4">
                    <a>Estudiante(s):</a>
                    <div class="p-4 bg-white rounded" >
                        <ol class="list-decimal pb-4 ps-4 ">
                            <li class="pb-2" >FLORES CEDEÑO WILLIAM ANDRES</li>
                            <li>RODRIGUEZ SAN LUCAS GUSTAVO EMILIO</li>
                        </ol>
                    </div>
                </div>
                <div>
                    <div class="p-">
                        <a>Tutor</a>
                        <div class="p-4 bg-white rounded">
                            <a>ING. JOSE ARTEAGA VERA, PHD.</a>
                        </div>
                    </div>
                    <div class="p-">
                        <a>Presidente</a>
                        <div class="p-4 bg-white rounded">
                            <a>ING ARMANDO FRANCO MG.</a>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="p-">
                        <a>Tribunal 1</a>
                        <div class="p-4 bg-white rounded">
                            <a>ING. ALEX SANTAMARIA, PHD</a>
                        </div>
                    </div>
                    <div class="p-">
                        <a>Tribunal 2</a>
                        <div class="p-4 bg-white rounded">
                            <a>ING. JUAN CARLOS SENDON MG.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Para Asignar un Tribunal-->
     <div class="container mx-auto p-4 my-8">
        <div class="container mx-auto ">
            <h2 class="text-2xl font-bold text-center sm:text-left pb-1 ps-4 pt-5">
                Sustentación
            </h2>
        </div>
        <div v-for="data in 4" class="bg-neutral p-4 border-2 border-stone-300 mt-2">
            <div class="grid grid-cols-4 gap-4" >
                    <div class="col-span-3">
                        <div class="flex">
                            <div class="p-4 bg-white ms-2 rounded-xl border-2 border-stone-300">
                                  Carrera: Ti
                            </div>
                            <div class="p-4 bg-white ms-2 rounded-xl border-2 border-stone-300" >
                                  Modal: PROYECTO INTEGRADOR
                            </div>

                        </div>
                        <div class="mt-4 rounded" >
                            <div class="flex" >
                                <div v-for="data in 2" class="ms-2 " >
                                    <div>
                                        <div class="pt-3 pb-3 ps-2  " >
                                            <a class="bg-primary p-2 rounded-l-lg   ">
                                                Cedula 1:
                                            </a>
                                            <a class="bg-white pt-2 pb-2 ps-1 pe-2 rounded-r-lg border-r-2 border-b-2 border-t-2 border-stone-300 " >
                                                1313597773
                                            </a>
                                        </div>
                                        <div class="pt-3 pb-3 ps-2 ">
                                            <a class="bg-primary p-2 rounded-l-lg ">
                                                Estudiante:
                                            </a>
                                            <a  class="bg-white pt-2 pb-2 ps-1 pe-2 rounded-r-lg border-r-2 border-b-2 border-t-2 border-stone-300">
                                                AVILA ZAMBRANO RUBEN ARIEL
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 ">
                            <a class="bg-primary pe-4 ps-4 pt-2   rounded-t-lg ">
                                Tema:
                            </a>
                            <div class="bg-white p-4 rounded-b-lg  border-r-2 border-t-2 border-b-2 border-l-2 border-stone-300">
                                <p>
                                    DESARROLLO DE UN SISTEMA WEBPARA LA GESTION DEL FLUJO DE INFORMACIÒN EN EL TALLER AUTOMOTRIZ SISTEMA DE INYECCIONES FRANCO
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="col-span-1">
                        <div class="rounded  ">
                            <div v-for="data in 3" class="flex justify-between pb-4 mt-3">
                                <div>
                                    <a class="bg-primary ps-4 pe-4 rounded-badge">
                                        Tribunal 1:
                                    </a>
                                    <p class="pe-5 ps-3 pt-2 rounded-badge pb-2 mt-1 bg-white border-2 border-stone-300 " >
                                        Ing. Patricia Quiroz
                                    </p>
                                </div>
                                <div class="content-end ">
                                    <i class="bi bi-check-circle-fill icon_size"></i>    
                                    <i class="bi bi-x-circle-fill icon_size hidden"></i>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
     </div>
     <!-- Para Analista de Carrera -->
</template>
<style>

</style>