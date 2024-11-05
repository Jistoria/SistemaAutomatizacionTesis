<!-- components/LoadingScreen.vue -->
<template>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center bg-white z-[9999]">
      <div class="flex space-x-2">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useLoading } from '~/composables/loadingPage';
  const { isLoading } = useLoading();
  </script>
  
  <style scoped>
  .dot {
    width: 14px;
    height: 14px;
    background-color: #348FE2;
    border-radius: 50%;
    animation: dot-jump 0.6s ease-in-out infinite;
  }
  
  .dot:nth-child(2) {
    animation-delay: 0.2s;
  }
  
  .dot:nth-child(3) {
    animation-delay: 0.4s;
  }
  
  @keyframes dot-jump {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-15px);
    }
  }
  </style>
  