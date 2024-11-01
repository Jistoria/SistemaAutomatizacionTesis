<!-- components/ProgressBar.vue -->
<template>
    <div class="w-full bg-gray-300 h-2 rounded">
      <div class="bg-blue-500 h-full rounded" :style="{ width: progress + '%' }"></div>
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
        if (this.progress < 100) this.progress += 10;
        else clearInterval(this.interval);
      }, 200);
    },
    beforeUnmount() {
      clearInterval(this.interval);
    },
  };
  </script>
  