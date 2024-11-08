<script setup>

</script>
<template>

    <button class="btn" onclick="modal_observation.showModal()">
        <i class="bi bi-newspaper icon_size"></i>
        Obervaciones
    </button>
    <dialog id="modal_observation" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <h3 class="text-lg font-bold">Observation</h3>
        <div class="border_y">
            <div class="container ">

            </div>
            <p class="py-4">Click the button below to close</p>
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