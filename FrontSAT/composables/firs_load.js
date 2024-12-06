import { student } from "~/stores/dashboards/student";
import { auth } from "~/stores/auth/auth";
import { panel } from "~/stores/panel/panel";
import { roles } from "#imports";
import { notifyService } from "~/services/Notify/notify";
import { admin } from "~/stores/dashboards/admin";
export const firstLoad = async () => {
    const studentStore = student();
    const authStore = auth();
    const panelStore = panel();
    const adminStore = admin();

    await notifyService.listenChannel(authStore.user.id);


    const roleActions  = {
        [roles.rol5]: async () => {
            await studentStore.getDataStatus(authStore.token); // Acción específica para rol5
            await adminStore.dashboardAdmin();
        },
        [roles.rol4]: async () => {
            await panelStore.getlistStudents();
            await panelStore.dataDashboard();  

            // Implementar acción para rol4
        },
        [roles.rol3]: async () => {
            // Implementar acción para rol3
            //await adminStore.dashboardAdmin();

        },
        [roles.rol2]: async () => {
            // Implementar acción para rol2
        },
        [roles.rol1]: async () => {
        },
    }
    const role = authStore.role[0];
    if (role in roleActions) {
        await roleActions[role]();
    } else {
        console.warn("Rol no identificado:", role);
    }

    return true;

}