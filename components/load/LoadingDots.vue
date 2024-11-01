<!-- components/LoadingDots.vue -->
<template>
    <div class="flex space-x-1.5">
      <div class="dot w-2.5 h-2.5 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full shadow-md" style="animation-delay: 0s"></div>
      <div class="dot w-2.5 h-2.5 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full shadow-md" style="animation-delay: 0.15s"></div>
      <div class="dot w-2.5 h-2.5 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full shadow-md" style="animation-delay: 0.3s"></div>
    </div>
  </template>
  
  <style scoped>
  .dot {
    /* Tamaño más pequeño, sombra y degradado */
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    box-shadow: 0 0 4px rgba(52, 143, 226, 0.4);
    animation: subtleBounce 0.8s ease-in-out infinite;
  }
  
  /* Rebote y escala sutil */
  @keyframes subtleBounce {
    0%, 100% {
      transform: translateY(0) scale(1);
    }
    50% {
      transform: translateY(-3px) scale(1.1); /* Rebote más suave y escala menor */
    }
  }
  </style>
  