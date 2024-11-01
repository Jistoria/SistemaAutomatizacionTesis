<!-- components/Spinner.vue -->
<template>
    <div class="spinner w-10 h-10 border-4 rounded-full animate-spin"></div>
  </template>
  
  <style scoped>
  .spinner {
    /* Degradado en el borde utilizando los colores personalizados */
    border-top-color: transparent;
    border-right-color: rgba(128, 0, 32, 0.3); /* Borgoña tenue */
    border-bottom-color: rgba(34, 85, 34, 0.3); /* Verde oscuro tenue */
    border-left-color: #205920; /* Verde oscuro fuerte */
    box-shadow: 0 0 10px rgba(34, 85, 34, 0.5); /* Sombra verde para darle profundidad */
  }
  </style>
  