<!-- components/LoadingDots.vue -->
<template>
    <div class="flex space-x-2">
      <div class="dot bg-blue-500 w-3 h-3 rounded-full animate-bounce" style="animation-delay: 0s"></div>
      <div class="dot bg-blue-500 w-3 h-3 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
      <div class="dot bg-blue-500 w-3 h-3 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
    </div>
  </template>
  
  <style scoped>
  .dot:nth-child(1) { animation-delay: 0s; }
  .dot:nth-child(2) { animation-delay: 0.2s; }
  .dot:nth-child(3) { animation-delay: 0.4s; }
  </style>
  