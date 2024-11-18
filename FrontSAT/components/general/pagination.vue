<template>
  <div v-if="totalPages > 1" class="flex justify-center items-center space-x-6 mt-4">
    <!-- Botón Anterior -->
    <button
      class="btn btn-outline"
      :class="{ 'btn-disabled': !canGoBack, 'btn-primary': canGoBack }"
      @click="handlePreviousPage"
      :disabled="!canGoBack"
    >
      <i class="bi bi-chevron-left"></i>
    </button>

    <!-- Indicador de Páginas -->
    <div class="flex items-center text-sm font-medium space-x-2">
      <span class="text-base-content text-primary">Página</span>
      <span class="font-bold text-primary">{{ currentPage }}</span>
      <span class="text-base-content text-primary">de</span>
      <span class="font-bold text-primary">{{ totalPages }}</span>
    </div>

    <!-- Botón Siguiente -->
    <button
      class="btn btn-outline"
      :class="{ 'btn-disabled': !canGoNext, 'btn-primary': canGoNext }"
      @click="handleNextPage"
      :disabled="!canGoNext"
    >
      <i class="bi bi-chevron-right"></i>
    </button>
  </div>
</template>


<script setup>
import { computed, inject } from 'vue';
import { usePaginationStore } from '~/stores/pagination/pagination';

const paginationStore = usePaginationStore();
const { openAnimation, closeAnimation } = inject('requestAnimation');

// Computados para navegación
const currentPage = computed(() => paginationStore.current_page);
const totalPages = computed(() => paginationStore.frontend_pages);
const canGoBack = computed(() => currentPage.value > 1);
const canGoNext = computed(() => currentPage.value < totalPages.value);

// Métodos con Animación
const handlePreviousPage = async () => {
  if (canGoBack.value) {
    openAnimation('spinner'); // Iniciar animación
    await paginationStore.previousPage();
    closeAnimation(); // Detener animación
  }
};

const handleNextPage = async () => {
  if (canGoNext.value) {
    openAnimation('spinner'); // Iniciar animación
    await paginationStore.nextPage();
    closeAnimation(); // Detener animación
  }
};
</script>