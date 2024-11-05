<!-- components/LoadingAnimation.vue -->
<template>
  <div v-if="isAnimating" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-[9999]">
    <div :class="['p-4 bg-white rounded-lg shadow-lg', animationContainerClass]">
      <component :is="animationComponent" />
    </div>
  </div>
</template>

<script setup>
import { computed, inject } from 'vue';
import Spinner from '~/components/load/Spinner.vue';
import LoadingDots from '~/components/load/LoadingDots.vue';
import ProgressBar from '~/components/load/ProgressBar.vue';

const { isAnimating, currentAnimationType } = inject('requestAnimation');

const animationComponent = computed(() => {
  switch (currentAnimationType.value) {
    case 'progress-bar':
      return ProgressBar;
    case 'dots':
      return LoadingDots;
    case 'spinner':
    default:
      return Spinner;
  }
});

const animationContainerClass = computed(() => {
  return currentAnimationType.value === 'progress-bar'
    ? 'w-1/2 max-w-sm mx-auto' // Contenedor más pequeño para la barra de progreso
    : 'w-24 h-24 flex items-center justify-center'; // Dimensiones para spinner y puntos
});
</script>

<style scoped>
.bg-black.bg-opacity-60 {
  background-color: rgba(0, 0, 0, 0.6); /* Fondo un poco más oscuro */
}
</style>
