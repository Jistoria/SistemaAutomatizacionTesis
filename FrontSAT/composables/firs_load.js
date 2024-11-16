import { student } from "~/stores/dashboards/student";
import { auth } from "~/stores/auth/auth";
import { panel } from "~/stores/panel/panel";
import { roles } from "#imports";

export const firstLoad = async () => {
    const studentStore = student();
    const authStore = auth();
    const panelStore = panel();
    console.log("Rol actual:", authStore.role[0]);

    const roleActions  = {
        [roles.rol5]: async () => {
            await studentStore.getDataStatus(authStore.token); // Acción específica para rol5
        },
        [roles.rol4]: async () => {
            await panelStore.getlistStudents();
            await panelStore.dataDashboard();  

            console.log("Acción para rol4");
            // Implementar acción para rol4
        },
        [roles.rol3]: async () => {
            console.log("Acción para rol3");
            // Implementar acción para rol3
        },
        [roles.rol2]: async () => {
            console.log("Acción para rol2");
            // Implementar acción para rol2
        },
        [roles.rol1]: async () => {
            console.log("Acción para rol1");
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