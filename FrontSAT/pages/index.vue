<script setup>
import { auth } from '~/stores/auth/auth';
import { roles } from '~/composables/roles';
const { $echoReady } = useNuxtApp();
const localePath = useLocalePath()
const authStore = auth()
import { useRoute, useRouter } from 'vue-router';
import AdminTesisPanelScreen from './panel/admin_tesis_panel/adminTesisPanelScreen.vue';
import ManagementPanelScreen from './panel/management_panel/managementPanelScreen.vue';
import StudentPanelScreen from './panel/student_panel/studentPanelScreen.vue';
import TeacherPanelScreen from './panel/teacher_panel/teacherPanelScreen.vue';
import CourtPanelScreen from './panel/court_panel/courtPanelScreen.vue';
const route = useRoute();
const router = useRouter();
const rol_main = ref('');
const rolSelect = ref('')

const componentMap ={
    [roles.rol1]: AdminTesisPanelScreen,
    [roles.rol2]: CourtPanelScreen,
    [roles.rol3]: ManagementPanelScreen,
    [roles.rol4]: TeacherPanelScreen,
    [roles.rol5]: StudentPanelScreen,
}


onMounted(async () => {
    await $echoReady
    
    rolSelect.value = authStore.role[0]
    
})
const Logout = async () => {
    await authStore.logout()
}


</script>
<template>
    
    <div class="container mx-auto">
        <component :is="componentMap[rolSelect]"></component>
    </div>
</template>

<style>

</style>