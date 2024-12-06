<template>
  <div class=" flex justify-start gap-2 p-2 bg-white rounded-lg  mx-auto">
    <!-- Input de Búsqueda -->
    <div class="relative">
      <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
        <i class="bi bi-search"></i>
      </span>
      <input
        type="text"
        v-model="searchQuery"
        class="w-40 pl-10 pr-4 py-2 text-sm rounded-md border border-gray-300 focus:w-60 transition-all duration-300 ease-in-out focus:ring-primary focus:outline-none"
        placeholder="Buscar..."
        @focus="isFocused = true"
        @blur="isFocused = false"
      />
      <button
        v-if="searchQuery"
        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-red-500 transition-colors"
        @click="clearSearch"
      >
        <i class="bi bi-x-circle"></i>
      </button>
    </div>
    <!-- Select de Orden -->
    <div>
      <select
        v-model="selectedOrder"
        class="text-sm py-2 px-3 rounded-md border border-gray-300 focus:ring-primary focus:outline-none"
        @change="handleFilter"
      >
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
      </select>
    </div>
    <!-- Botón de Búsqueda -->
    <button
      class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md focus:ring-2 focus:ring-red-500 focus:outline-none transition-transform transform hover:scale-105"
      @click="handleSearch"
    >
      <i class="bi bi-funnel-fill"></i>
      Aplicar
    </button>
  </div>



</template>
<script setup>
import { ref } from 'vue';
import { usePaginationStore } from '~/stores/pagination/pagination';

const { openAnimation, closeAnimation } = inject('requestAnimation');

const paginationStore = usePaginationStore();

// Variables de estado local
const searchQuery = ref('');
const selectedOrder = ref('asc'); // Valor inicial preseleccionado

// Función para manejar la búsqueda
const handleSearch = async () => {
  openAnimation('spinner'); // Iniciar animación
    await paginationStore.applyFiltersAndSearch({filter: selectedOrder.value, search: searchQuery.value });
  closeAnimation(); // Detener animación
};

// Función para manejar los cambios en los filtros
const handleFilter = async () => {
  openAnimation('spinner'); // Iniciar animación
    await paginationStore.applyFiltersAndSearch({ filter: selectedOrder.value, search: searchQuery.value });
  closeAnimation(); // Detener animación
};
const clearSearch = async () => {
  searchQuery.value = '';
  selectedOrder.value = 'asc';
  openAnimation('spinner'); // Iniciar animación
    await paginationStore.applyFiltersAndSearch({ search: '', filter: selectedOrder.value });
  closeAnimation(); // Detener animación
};
</script>

<style>
/* Borde uniforme y limpio */
input,
select {
  border: 1px solid #d1d5db; /* Color del borde gris */
  box-shadow: none;
}

</style>

  