<!-- components/ProgressBar.vue -->
<template>
    <div class="progress-container bg-gray-300 h-2 rounded overflow-hidden shadow-inner">
      <div class="progress-bar h-full rounded transition-all duration-500 ease-in-out" :style="{ width: progress + '%' }"></div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return { progress: 0, interval: null };
    },
    mounted() {
      this.progress = 0;
      this.interval = setInterval(() => {
        if (this.progress < 100) {
          this.progress += 5; // Avance más lento para mejorar la fluidez
        } else {
          clearInterval(this.interval);
        }
      }, 100); // Intervalo más corto para una actualización más frecuente
    },
    beforeUnmount() {
      clearInterval(this.interval);
    },
  };
  </script>
  
  <style scoped>
  .progress-container {
    /* Sombra interior para dar profundidad */
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
  }
  
  .progress-bar {
    /* Degradado animado para un efecto visual atractivo */
    background: linear-gradient(90deg, #3b82f6, #2563eb, #3b82f6);
    background-size: 200% 100%;
    animation: gradientAnimation 1.5s infinite linear;
    box-shadow: 0 0 8px rgba(59, 130, 246, 0.5); /* Sombra para darle más profundidad */
    border-radius: 4px; /* Bordes redondeados */
  }
  
  @keyframes gradientAnimation {
    0% {
      background-position: 0% 50%;
    }
    100% {
      background-position: 100% 50%;
    }
  }
  </style>
  