<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
const route = useRoute();

const showHeaderFooter = computed(() => {
    if(route.path === '/login/loginScreen' ){
        return false
    }else{
        return true
    }
});
</script>
<template>
 <div  class="layout">
    <div  v-if="showHeaderFooter" class="header">
      header
    </div>
    <div class="content" >
        <div class="my-48">
            <slot />
        </div>
    </div>
    <div v-if="showHeaderFooter" class="footer">
      footer
    </div>
  </div>
</template>
<style>
.layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh; 
}

.header {
  flex-shrink: 0; 
}

.content {
  flex-grow: 1; 
  overflow-y: auto; 
}

.footer {
  flex-shrink: 0; 
}

.bg-green {
  background-color: green;
}

.bg-default {
  background-color: white; 
}
</style>