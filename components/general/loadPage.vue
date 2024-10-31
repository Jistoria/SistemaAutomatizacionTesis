<!-- components/LoadingScreen.vue -->
<template>
    <div v-if="isLoading" class="load_setup">
        <div class="spinner"></div>
    </div>
</template>
  
  <script setup lang="ts">
 import { useLoading } from '~/composables/loadingPage';
  const {isLoading} =  useLoading();
  
  </script>
  
  <style scoped>
  .load_setup{
      position: absolute;
      top:0px;
      background: rgb(0, 0, 0);
      width: 100%;
      height: 100vh;
      display:flex;
      z-index: 20;
  }
  .spinner{
      height: 120px;
      width: 120px;
      border: 6px solid white;
      margin:auto;
      border-color: white transparent white transparent;
      border-radius: 50%;
      animation: spin 1s linear infinite;
  }
  @keyframes spin{
      to{
          transform: rotate(360deg);
      }
  }
  </style>
  