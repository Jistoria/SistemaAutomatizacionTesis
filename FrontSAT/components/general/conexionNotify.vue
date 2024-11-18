<template>
    <div
      :style="{ top: `calc(${topOffset} - 1px)`, zIndex: isBelowHeader ? 5 : 100 }"
      class="fixed left-0 w-full transition-all duration-300"
    >
      <transition name="fade-slide">
        <div
          v-if="status === 'disconnected'"
          class="bg-gradient-to-r from-red-400 to-red-500 text-white py-1 px-4 text-center shadow-md"
        >
          <p class="font-medium text-sm">No tienes conexión</p>
        </div>
      </transition>
      <transition name="fade-slide">
        <div
          v-if="status === 'reconnecting'"
          class="bg-gradient-to-r from-yellow-300 to-yellow-400 text-black py-1 px-4 text-center shadow-md"
        >
          <p class="font-medium text-sm">Intentando reconectar...</p>
        </div>
      </transition>
      <transition name="fade-slide">
        <div
          v-if="showConnected"
          class="bg-gradient-to-r from-green-400 to-green-500 text-white py-1 px-4 text-center shadow-md"
        >
          <p class="font-medium text-sm">Conexión establecida</p>
        </div>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted, computed } from 'vue';
  import { conexion } from '~/stores/conexion/conexion';
  
  const conexionStore = conexion();
  
  // Asegúrate de usar un `computed` para observar un valor reactivo
  const status = computed(() => conexionStore.status); // Computa el estado reactivo desde el store
  const showConnected = ref(false);
  const topOffset = ref('0px'); // Posición vertical inicial
  const isBelowHeader = defineProps(['isBelowHeader']); // Prop para saber si se encuentra debajo del header
  
  onMounted(() => {
    if (isBelowHeader) {
      const header = document.querySelector('.header');
      if (header) {
        topOffset.value = `${header.offsetHeight}px`; // Ajusta la altura según el header
      }
    }
  });
  
  // Observa el `computed` status
  watch(status, (newStatus) => {
    if (newStatus === 'connected') {
      showConnected.value = true;
      setTimeout(() => {
        showConnected.value = false;
      }, 5000);
    }
  });
  </script>
  
  
  <style scoped>
  /* Transiciones suaves */
  .fade-slide-enter-active,
  .fade-slide-leave-active {
    transition: all 0.4s ease;
  }
  .fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
  }
  .fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
  }
  
  /* Sombras */
  .shadow-md {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  </style>
  