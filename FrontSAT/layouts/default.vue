<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import LoadPage from '~/components/load/loadPage.vue';
import { provide } from 'vue';
import { useRequestAnimation } from '~/composables/useRequestAnimation';
import LoadingAnimation from '~/components/load/LoadingAnimation.vue';
const colorMode = useColorMode()

const requestAnimation = useRequestAnimation();
provide('requestAnimation', requestAnimation);
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

 <div class="layout">
  
    <ClientOnly>
      <div v-if="showHeaderFooter"  class="header">
        <HeaderComp></HeaderComp>
      </div>
    </ClientOnly>
    <div class="content " >
        <div>
          <ClientOnly>
            <slot />
          </ClientOnly>
          <LoadingAnimation />
          <LoadPage /> 
        </div>
    </div>
    <ClientOnly>
      <div v-if="showHeaderFooter"   class="footer">
        <FooterComp></FooterComp>
      </div>
    </ClientOnly>
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

</style>