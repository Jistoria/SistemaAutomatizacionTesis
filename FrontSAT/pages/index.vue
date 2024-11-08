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
import RequestModal from '~/components/modal/requestModal.vue';
import UploadFiles from '~/components/general/uploadFiles.vue';
const route = useRoute();
const router = useRouter();
const rol_main = ref('');
const rolSelect = ref('')

onMounted(async () => {
    await $echoReady
    rolSelect.value = authStore.role[0]
    console.log(rolSelect.value);
})
const Logout = async () => {
    await authStore.logout()
}


</script>
<template>
    <div class="hiddn">
        <!-- <ChangeLenguaje></ChangeLenguaje>
        <client-only>
            <button v-if="authStore.session" @click="Logout()">Logout</button>
        </client-only> -->
        <RequestModal></RequestModal>
        <Observation></Observation>
        <div v-if="rolSelect == roles.rol1" >
            <AdminTesisPanelScreen></AdminTesisPanelScreen>
        </div>
        <div v-if="rolSelect == roles.rol2" >
            <CourtPanelScreen></CourtPanelScreen>
        </div>
        <div v-if="rolSelect == roles.rol3" >
            <ManagementPanelScreen></ManagementPanelScreen>
        </div>
        <div v-if="rolSelect == roles.rol4" >
            <StudentPanelScreen></StudentPanelScreen>
        </div>
        <div v-if="rolSelect == roles.rol5" >
            <TeacherPanelScreen></TeacherPanelScreen>
        </div>
    </div>


</template>

<style>

</style>