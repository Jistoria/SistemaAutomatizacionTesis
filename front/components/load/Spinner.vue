<template>
  <div class="spinner w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
</template>

<style scoped>
.spinner {
  /* Opcional: Puedes personalizar la animación con una duración específica si es necesario */
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

  